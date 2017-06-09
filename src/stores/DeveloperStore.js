/**
 * Created by Raphson on 10/16/16.
 */
import BaseStore from './BaseStore'
let AppConstants = require('../constants/AppConstants'),
    AppDispatcher = require('../dispatcher/AppDispatcher');

if (!Object.assign) {
    Object.assign = require('object-assign');
}
let DeveloperStore = Object.assign({}, BaseStore, {
    developers: null,
    developer: null,

    setDevelopers(developers) {
        this.developers = developers;
        this.emitChange('fetchDevelopers');
    },

    getDevelopers()  {
        return this.developers
    },

    setDeveloper(developer) {
        this.developer = developer;
        this.emitChange('fetchDeveloper');
    },

    getDeveloper()  {
        return this.developer
    },
});

AppDispatcher.register((action) => {
    switch (action.actionType){
        case AppConstants.GET_DEVELOPERS :
            DeveloperStore.setDevelopers(action.data);
            break;
        case AppConstants.GET_DEVELOPER :
            DeveloperStore.setDeveloper(action.data);
            break;
        default:
        // no default action
    }
    return true;
});

module.exports = DeveloperStore;