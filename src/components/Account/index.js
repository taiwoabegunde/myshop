/**
 * Created by Raphson on 9/28/16.
 */
import React from 'react';
import { Link, hashHistory } from 'react-router';
import NavBar from '../NavBar/index';
import Footer from '../Footer/Index';
import UserStore from '../../stores/UserStore';
import ProjectStore from '../../stores/ProjectStore';
import UserActions from '../../actions/UserActions';
import ProjectActions from '../../actions/ProjectActions';
import Auth from '../../utils/auth';
import marked from 'marked';
import moment from 'moment';
import L from 'leaflet'
import Modal from 'boron/FlyModal';
import CreateIndex from '../Project/CreateIndex';
import Alert from 'react-s-alert';
import 'react-s-alert/dist/s-alert-default.css';
import 'react-s-alert/dist/s-alert-css-effects/bouncyflip.css';

var contentStyle = {
    height: '100%',
    width: '600px'
};
export default class Account extends React.Component {
    constructor() {
        super();
        L.Icon.Default.imagePath = "//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/";
        this.state = {
            geocoder: new google.maps.Geocoder(),
            token: Auth.getToken(),
            displayImage: '',
            fullName: '',
            hireStatus: 'No',
            twitter:'',
            website: '',
            github: '',
            bio: '',
            address: '',
            username: '',
            registered_on: '1454521239279',
            longitude: 3.540790900000047300,
            latitude: 6.523276500000000000,
            zoom: 12,
            showModal: false
        }
    }

    componentDidMount() {
        UserActions.fetchAuthUser(this.state.token);
        UserStore.addChangeListener(this.handleAuthUserFetch);
        ProjectStore.addChangeListener(this.handleShareProjectResult, 'shareProject');
    }

    componentWillUnmount(){
        UserStore.removeChangeListener(this.handleAuthUserFetch);
        ProjectStore.removeChangeListener(this.handleShareProjectResult, 'shareProject');
    }

    handleAuthUserFetch = () => {
        let authUser = UserStore.getAuthUserResult();
        Auth.checkAuthRequired(authUser);
        this.setState({
            fullName: authUser.data.fullname,
            hireStatus: authUser.data.hire_status,
            twitter: authUser.data.twitter_handle,
            website: authUser.data.website,
            github: authUser.data.github_profile,
            bio: authUser.data.bio,
            address: authUser.data.address,
            displayImage: authUser.data.user_avi,
            registered_on: authUser.data.registered_on,
            username: authUser.data.username
        });
        this.handleAddressResolve();
    }

    handleShareProjectResult = () => {
        let result = ProjectStore.getShareProjectResult();
        Auth.checkAuthRequired(result);
        if(result.status == 500){
            Alert.error(result.data.message, { position: 'top-right',  effect: 'bouncyflip'});
        } else {
            if(result.data.success){
                Alert.success(result.data.message, { position: 'top-right',  effect: 'bouncyflip'});
                this.refs.modal.hide();
            } else {
                Alert.error(result.data.message, { position: 'top-right',  effect: 'bouncyflip'});
            }

        }
    }

    handleAddressResolve = () => {
        this.state.geocoder.geocode({'address': this.state.address}, this.handleAddressResolveSuccess);
    }

    handleAddressResolveSuccess = (results, status) => {
        if (status == google.maps.GeocoderStatus.OK) {
            let result = results[0].geometry.location;
            let map = L.map("map", {center: [this.state.latitude, this.state.longitude],zoom: this.state.zoom});
            L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {attribution: "OpenStreetMap"}).addTo(map);
            let marker = L.marker([result.lat(), result.lng()]).addTo(map);
            marker.bindPopup("<strong>You are here!</strong>").openPopup();
        }
    }

    showModal = (e) => {
        e.preventDefault();
        this.refs.modal.show();
    }

    hideModal = (e) =>{
        e.preventDefault();
        this.refs.modal.hide();
    }

    handleProjectShare = (data) => {
        console.log(JSON.stringify(data));
        var projectPayLoad = {
            name: data.project_name,
            url: data.project_url,
            description: data.project_description
        };
        ProjectActions.shareProject(projectPayLoad, this.state.token);
    }


    render(){
        return (
            <span>
                <NavBar />
                <div style={{minHeight: 580}} className="main-container">
                    <section className="header header-12">
                        <div className="container">
                            <div className="row">
                                <div className="col-sm-12 text-white">
                                    <h4 className="text-white">{this.state.fullName}</h4>
                                    <ul>
                                        <li><i className="fa fa-clock-o" /> Member since
                                            <span> { moment(this.state.registered_on, "x").format("DD MMM YYYY")} </span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section className="faq faq-1">
                        <div className="container">
                            <div className="row">
                                <div className="col-sm-6">
                                    <div className="faq">
                                        <img width={200} height={200} src={ this.state.displayImage }
                                             alt={this.state.fullName} className="img-rounded" />
                                    </div>
                                    <div className="faq">
                                        <h5>{this.state.fullName}</h5>
                                        <ul>
                                            {(this.state.github != '') ?
                                                <li><a target="_blank" href={this.state.github}>
                                                    <i className="fa fa-github" /> GitHub</a></li>
                                                : null }
                                            {(this.state.website != '') ?
                                                <li><a target="_blank" href={this.state.website}>
                                                    <i className="fa fa-globe" /> Website / Blog</a></li>
                                                : null }

                                        </ul>
                                        <br />
                                        <ul>
                                            {(this.state.hireStatus == 'yes') ?
                                            <li ><i className="fa fa-suitcase" /> Not Available for Hire</li>
                                                :
                                            <li><i className="fa fa-suitcase" /> Available for Hire</li> }
                                            <br />
                                        </ul>
                                        <ul>
                                            <li><i className="fa fa-project" />
                                                <a onClick={this.showModal}
                                                   className="btn btn-default">Share Project</a>
                                                <Modal ref="modal" contentStyle={contentStyle}>
                                                    <CreateIndex onClose={this.hideModal}
                                                         onDataSubmit={this.handleProjectShare} />
                                                </Modal>
                                            </li>
                                            <br />
                                        </ul>
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="faq">
                                        <h5>Tell Us About Yourself</h5>
                                        <p dangerouslySetInnerHTML={{__html: marked(this.state.bio) }} />
                                    </div>
                                    <div className="faq">
                                        <h5>Location</h5>
                                        <div id="map" className="leaflet-container"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <Footer />
            </span>
        );
    }
}