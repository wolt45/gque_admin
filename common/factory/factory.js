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

    obj.CheckPxDsig = function(PIN, callback) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCheckPxDsig&PIN=' + PIN,
        });
    };

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

    obj.getRequestForModifAlter = function(PxRID) {
        return $http({
            method : 'GET',
            url : serviceBase + 'apiGetRequestForModifAlter&PxRID=' + PxRID,
        });
    }

    obj.insertRequestForModifAlter = function(RequestForModifAlterObj, userPxRID) {
        var RequestForModifAlterData = {
            "EnteredBy": userPxRID
            , "requestAlterModRID": RequestForModifAlterObj.requestAlterModRID
            , "requestType": RequestForModifAlterObj.requestType
            , "requestDescription": RequestForModifAlterObj.requestDescription
            , "disApprovedDescription": RequestForModifAlterObj.disApprovedDescription
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertRequestForModifAlter',
            responseType: 'json',
            data: RequestForModifAlterData,
            cache: true
        });
    }

    obj.signRequestedByRequestForModifAlter = function(requestAlterModRID, PxRID) {
        var RequestedByRequestForModifAlterdata = {
            "requestAlterModRID": requestAlterModRID
            , "PxRID": PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignRequestedByRequestForModifAlter',
            responseType: 'json',
            data: RequestedByRequestForModifAlterdata,
            cache: true
        });
    };

    obj.signApprovedByRequestForModifAlter = function(requestAlterModRID, requestStatus, PxRID) {
        var RequestedByRequestForModifAlterdata = {
            "requestAlterModRID": requestAlterModRID
            , "PxRID": PxRID
            , "requestStatus": requestStatus
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignApprovedByRequestForModifAlter',
            responseType: 'json',
            data: RequestedByRequestForModifAlterdata,
            cache: true
        });
    };

    obj.signDisapprovedByRequestForModifAlter = function(requestAlterModRID, requestStatus, PxRID) {
        var RequestedByRequestForModifAlterdata = {
            "requestAlterModRID": requestAlterModRID
            , "PxRID": PxRID
            , "requestStatus": requestStatus
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignDisapprovedByRequestForModifAlter',
            responseType: 'json',
            data: RequestedByRequestForModifAlterdata,
            cache: true
        });
    };



    // floor
    return obj;
}]);