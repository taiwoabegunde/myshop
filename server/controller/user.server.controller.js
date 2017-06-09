/**
 * Created by Raphson on 6/30/16.
 */
var User = require('../model/user.server.model');
var token = require('../../config/token');
var gravatar = require('gravatar');
var _  = require('lodash');
var cloudinary  = require('cloudinary');
var  multiparty  = require('multiparty');
var generatePassword = require('password-generator');
var helper = require('sendgrid').mail;
var sg = require('sendgrid')(process.env.SENDGRID_API_KEY);
module.exports = {

    /*
     *   Welcome Note!
     *   @param req
     *   @param res
     *  @return json
     */
    welcome: function(req, res){
        return res.status(200).json({message : 'Welcome to MERN MAP API'});
    },

    /*
    * Register User with username and other infomation provided
    * @param req
    * @param res
    *
    */
    registerUser: function(req, res){
        var user = new User();
        var secureImageUrl = gravatar.url(req.body.email, {s: '200', r: 'x', d: 'retro'}, true);
        user.username       = req.body.username;
        user.fullname       = req.body.fullname;
        user.email          = req.body.email;
        user.password       = req.body.password;
        user.website        = req.body.website;
        user.github_profile = req.body.github_profile;
        user.address        = req.body.address;
        user.user_avatar    = secureImageUrl;

        user.save(function(err, result){
            if(err){
                if(err.name == 'MongoError' && err.message.indexOf('$email_1') > 0 ) {
                    return res.status(200).json({ success: false,
                        Error: 'Email is already registered. Please choose another' });
                } else if ( err.name == 'MongoError' && err.message.indexOf('$username_1') > 0) {
                    return res.status(200).json({ success: false,
                        Error: 'Username is already taken. Please choose another' });
                }
            } else {
                return res.status(200).json({ success: true,
                    message: "User Registered successfully. Please, login and be MERN" });
            }
        });
    },

    /*
     * Authenticate a user using Email and password
     * @param req
     * @param res
     * @return json
     */
    auth: function(req, res){
        User.findOne({email : req.body.email}, function(err, loginUser){
            if(!loginUser)
                return res.status(401).json({message : "Invalid Email"});


            loginUser.comparePassword(req.body.password, function(err, isMatch){
                if(!isMatch){
                    return res.status(401).json({message : "Invalid Password"});
                }

                var currUser   = _.pick(loginUser, '_id', 'fullname', 'user_avi', 'username');
                return res.status(200).send({token : token.createJWT(loginUser), user: currUser});
            });
        });
    },

    /*
    * get current logged-in user
    * @param req
    * @param res
    * @return json
     */
    getCurrentLoggedUser: function(req, res){
        var query = User.findById(req.user)
            .select('username fullname hire_status address twitter_handle website github_profile bio ' +
                'user_avi registered_on');

        query.exec(function(err, result){
            return res.status(200).send(result);
        });
    },

    /*
    * update current logged user's detail
    * @param req
    * @param res
    * @return json
     */

    updateLoggedInUserDetail: function(req, res){
        var userDetails = {
            fullname: req.body.fullname,
            website: req.body.website,
            github_profile: req.body.github_profile,
            address: req.body.address,
            hire_status: req.body.hire_status,
            bio: req.body.bio,
            twitter_handle: req.body.twitter_handle
        };
        console.log(userDetails);

        if(req.body.uploadedFileURL)
            userDetails.user_avi = req.body.uploadedFileURL;

        //console.log(userDetails);
        User.findByIdAndUpdate({_id: req.user}, userDetails, function(err){
            if(err){
                return res.status(404).json({message : 'user\s detail not found'})
            }
            return res.status(200).json({message: 'Update Successful'});
        });
    },

    getAllUsers: (req, res) => {
        var query = User.find().select('username fullname twitter_handle website github_profile user_avi address');

        query.exec(function(err, result){
            return res.status(200).send(result);
        });
    },

    getEachUserByUsername: (req, res, next) => {
        var userReal = req.params.username;

        User.find({username: userReal}, function (err, user) {
            if(err) {
                return res.status(404).json({err: err});
            }

            if(user.length === 0){
                return res.json({ success: false, message: 'User not found.' });
            }
            else if(user.length == 1) {
                var userDetails = {};
                userDetails.id              = user[0]._id;
                userDetails.email           = user[0].email;
                userDetails.fullname        = user[0].fullname;
                userDetails.username        = user[0].username;
                userDetails.user_avatar     = user[0].user_avi;
                userDetails.admin           = user[0].admin;
                userDetails.bio             = user[0].bio;
                userDetails.hire_status     = user[0].hire_status;
                userDetails.address         = user[0].address;
                userDetails.github_profile  = user[0].github_profile;
                userDetails.website         = user[0].website;
                userDetails.registered      = user[0].registered_on;

                return res.json({success: true, user: userDetails});
            }
        });
    },

    changePassword: (req, res) => {
        User.findById(req.user, (err, user) => {
            if(err){
                return res.status(404).json({message : 'user\s detail not found'})
            }

            user.comparePassword(req.body.oldPassword, function(err, isMatch){
                if(!isMatch){
                    return res.status(200).json({success: false, message : "Old Password is wrong"});
                }

                user.password = req.body.newPassword;
                user.save(function(err) {
                    if (err) throw err;
                    console.log('password updated');
                    return res.status(200).json({success: true, message: "Password Changed"});
                });
            });
        });
    },

    resetUserPassword : (req, res) =>{
        let userEmail = req.body.email;

        User.find({email: userEmail}, (err, user) => {
            if(err) {
                return res.status(404).json({ err: err , req: req.body})
            }

            if(user.length === 0){
                return res.status(200).json({success: true, message: 'User not found'});
            } else  {
                var newPassword = generatePassword(8);
                user[0].password = newPassword;
                user[0].save(function(err) {
                    if (err) throw err;

                    let from_email = new helper.Email('admin@mernmap.com');
                    let to_email = new helper.Email(user[0].email);
                    let subject = 'Password Reset';
                    let content = new helper.Content('text/html',
                        "<h2>MERNMAP Password Reset</h2><br />" +
                        "Your new MERNMAP password is <br/>" +
                        "<strong>" + newPassword + "</strong>");

                    let mail = new helper.Mail(from_email, subject, to_email, content);
                    let request = sg.emptyRequest({
                        method: 'POST',
                        path: '/v3/mail/send',
                        body: mail.toJSON()
                    });

                    sg.API(request, function(error, response) {
                        console.log(response.statusCode);
                        console.log(response.body);
                        console.log(response.headers);
                        console.log('mail sent!');
                        return res.status(200).json({success: true, message: "New password was sent to your email!"});
                    });
                });
            }
        })
    }
};
