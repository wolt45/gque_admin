gmmrApp.factory("dbServices", ['$http', function($http) {

    var serviceBase = 'services/';

    var obj = {};


    obj.login = function(Username, Password) {
        var UserData = {
            "Username": Username
            , "Password": Password
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiLogin',
            responseType: 'json',
            data: UserData,
            cache: true
        });
    }

    obj.logout = function(userID) {
        var UserData = {
            "userID": userID
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiLogout',
            responseType: 'json',
            data: UserData,
            cache: true
        });
    }


    obj.getUserProfile = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetUserProfile&userPxRID=' + userPxRID,
        });
    }

    obj.checkSysDoorKeys = function(PxRID, DoorKnob) {
        return $http({
            method : 'GET',
            url : serviceBase + 'apiCheckSysDoorKeys&PxRID=' + PxRID + '&DoorKnob=' + DoorKnob,
        });
    }

    obj.checkAcctSysDoorKeys = function(PxRID) {
        return $http({
            method : 'GET',
            url : serviceBase + 'apiCheckAcctSysDoorKeys&PxRID=' + PxRID,
        });
    }



    // floor
    return obj;
}]);