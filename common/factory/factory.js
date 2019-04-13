gmmrApp.factory("dbServices", ['$http', function($http) {

    var serviceBase = 'services/';

    var obj = {};

    obj.getMyAccess = function() {
        return $http({
            method: 'GET',
            url: '../../myConfig/myAccessLocation.txt',
        });
    }

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

    obj.getNotifications = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNotifications&userPxRID=' + userPxRID,
        });
    }

    obj.getNotificationsBirthdays = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNotificationsBirthdays',
        });
    }

    obj.getNotificationsRequestForModifAlter = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNotificationsRequestForModifAlter&userPxRID=' + userPxRID,
        });
    }

    obj.getNotificationsFollowUpSched = function() {
        return $http.get(serviceBase + 'apiGetNotificationsFollowUpSched');
    };

    obj.checkAccount = function(oldAccountObj, userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCheckAccount&username=' + oldAccountObj.username + '&userPassword=' + oldAccountObj.userPassword + '&userPxRID=' + userPxRID,
        });
    }

    obj.renewAccount = function(newAccountObj, userPxRID) {
        var UserData = {
            "userPxRID": userPxRID
            , "username": newAccountObj.username
            , "userPassword": newAccountObj.userPassword
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiRenewAccount',
            responseType: 'json',
            data: UserData,
            cache: true
        });
    }

    obj.CheckPxDsig = function(PIN) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCheckPxDsig&PIN=' + PIN,
        });
    };

    obj.CheckPxDsigAcct = function(PIN, PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCheckPxDsigAcct&PIN=' + PIN + '&PxRID=' + PxRID ,
        });
    };

    obj.renewCheckDuplicatePxDsigAcct = function(PIN) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiRenewCheckDuplicatePxDsigAcct&PIN=' + PIN,
        });
    };

    obj.renewCheckPxDsigAcct = function(PIN, PxRID) {
        var UserData = {
            "PIN": PIN
            , "PxRID": PxRID
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiRenewCheckPxDsigAcct',
            responseType: 'json',
            data: UserData,
            cache: true
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

    obj.signApprovedByRequestForModifAlter = function(requestAlterModRID, requestStatus, requestStatusDesc, PxRID) {
        var RequestedByRequestForModifAlterdata = {
            "requestAlterModRID": requestAlterModRID
            , "PxRID": PxRID
            , "requestStatus": requestStatus
            , "requestStatusDesc": requestStatusDesc
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignApprovedByRequestForModifAlter',
            responseType: 'json',
            data: RequestedByRequestForModifAlterdata,
            cache: true
        });
    };

    obj.signDisapprovedByRequestForModifAlter = function(requestAlterModRID, requestStatus, disApprovedDescription, requestStatusDesc, PxRID) {
        var RequestedByRequestForModifAlterdata = {
            "requestAlterModRID": requestAlterModRID
            , "PxRID": PxRID
            , "requestStatus": requestStatus
            , "disApprovedDescription": disApprovedDescription
            , "requestStatusDesc": requestStatusDesc
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignDisapprovedByRequestForModifAlter',
            responseType: 'json',
            data: RequestedByRequestForModifAlterdata,
            cache: true
        });
    };



    //================
    // Operating Room Disinfection
    //================

    obj.getOperatingRoomDisinfectionDetail = function(operatingDisinfectCheckRID) {
        return $http.get(serviceBase + 'apiGetOperatingRoomDisinfectionDetail&operatingDisinfectCheckRID='+ operatingDisinfectCheckRID);
    };

    obj.getOperatingRoomDisinfection = function() {
        return $http.get(serviceBase + 'apiGetOperatingRoomDisinfection');
    };


    obj.insertOperatingRoomDisinfectionDetail = function (operatingDisinfectCheckRID, OperatingRoomDisinfectionObj) {
        var OperatingRoomDisinfectionData = {
            "operatingDisinfectCheckRID" : operatingDisinfectCheckRID
          , "operatingDisinfectCheckDetailRID" : OperatingRoomDisinfectionObj.operatingDisinfectCheckDetailRID
          , "dateTimeEntered" : OperatingRoomDisinfectionObj.dateTimeEntered
          , "wall" : OperatingRoomDisinfectionObj.wall
          , "anesthesiaMachine" : OperatingRoomDisinfectionObj.anesthesiaMachine
          , "orBed" : OperatingRoomDisinfectionObj.orBed
          , "suctionMachine" : OperatingRoomDisinfectionObj.suctionMachine
          , "electrocauteryMachine" : OperatingRoomDisinfectionObj.electrocauteryMachine
          , "orLight" : OperatingRoomDisinfectionObj.orLight
          , "suppliesCabinet" : OperatingRoomDisinfectionObj.suppliesCabinet
          , "equipmentCabinet" : OperatingRoomDisinfectionObj.equipmentCabinet
          , "floor" : OperatingRoomDisinfectionObj.floor
          , "others" : OperatingRoomDisinfectionObj.others
          , "remarks" : OperatingRoomDisinfectionObj.remarks
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiInsertOperatingRoomDisinfectionDetail'
          ,responseType: 'json'
          ,data: OperatingRoomDisinfectionData
          ,cache:true
        });
    };


    obj.insertOperatingRoomDisinfection = function (OperatingRoomDisinfectionObjMain) {
        var OperatingRoomDisinfectionData = {
          "room" : OperatingRoomDisinfectionObjMain.room
          , "operatingDisinfectCheckRID" : OperatingRoomDisinfectionObjMain.operatingDisinfectCheckRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiInsertOperatingRoomDisinfection'
          ,responseType: 'json'
          ,data: OperatingRoomDisinfectionData
          ,cache:true
        });
    };

    obj.signOperatingRoomDisinfection = function (operatingDisinfectCheckDetailRID, initialPxRID) {
        var ConsentForAdmissionOBJ = {
          "operatingDisinfectCheckDetailRID" : operatingDisinfectCheckDetailRID
          , "initialPxRID" : initialPxRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiSignOperatingRoomDisinfection'
          ,responseType: 'json'
          ,data: ConsentForAdmissionOBJ
          ,cache:true
        });
    };

    obj.removeOperatingRoomDisinfectionDetail = function (operatingDisinfectCheckDetailRID) {
        var OperatingRoomDisinfectionData = {
          "operatingDisinfectCheckDetailRID" : operatingDisinfectCheckDetailRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiRemoveOperatingRoomDisinfectionDetail'
          ,responseType: 'json'
          ,data: OperatingRoomDisinfectionData
          ,cache:true
        });
    };

    obj.newOperatingRoomDisinfection = function() {
        return $http.get(serviceBase + 'apiNewOperatingRoomDisinfection');
    };

    obj.removeOperatingRoomDisinfection = function (operatingDisinfectCheckRID) {
        var OperatingRoomDisinfectionData = {
          "operatingDisinfectCheckRID" : operatingDisinfectCheckRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiRemoveOperatingRoomDisinfection'
          ,responseType: 'json'
          ,data: OperatingRoomDisinfectionData
          ,cache:true
        });
    };


    //================
    // End Operating Room Disinfection
    //================


    obj.getAllFollowUpSched = function() {
        return $http.get(serviceBase + 'apiGetAllFollowUpSched');
    };

    obj.changeStatFlag = function (wrid, columnValue, columnToChange) {
        var StatFlagData = {
          "wrid" : wrid
          , "columnToChange" : columnToChange
          , "columnValue" : columnValue
        }
        // console.log(StatFlagData);
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiChangeStatFlag'
          ,responseType: 'json'
          ,data: StatFlagData
          ,cache:true
        });
    };



    // floor
    return obj;
}]);