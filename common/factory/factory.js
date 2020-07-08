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


    obj.getUserAccounts = function() {
        return $http({
            method : 'GET',
            url : serviceBase + 'apiGetUserAccounts'
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

    obj.getAllFollowUpSchedNotes = function() {
        return $http.get(serviceBase + 'apiGetAllFollowUpSchedNotes');
    };

    obj.changeStatFlag = function (wrid, columnValue, columnToChange) {
        var StatFlagData = {
          "wrid" : wrid
          , "columnToChange" : columnToChange
          , "columnValue" : columnValue
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiChangeStatFlag'
          ,responseType: 'json'
          ,data: StatFlagData
          ,cache:true
        });
    };







        obj.getDrugDepartment = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDrugDepartment',
        });
    }

    obj.getDrugList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDrugList',
        });
    }


    obj.insertMedicine = function(DrugObj) {
        var DrugData = {
            "DrugRID": DrugObj.DrugRID
            , "GlobalRID": DrugObj.GlobalRID
            , "MIMSid": DrugObj.MIMSid
            , "GenericName": DrugObj.GenericName
            , "BrandName": DrugObj.BrandName
            , "qtyOnHand": DrugObj.qtyOnHand
            , "OnOrder": DrugObj.OnOrder
            , "ReOrderPoint": DrugObj.ReOrderPoint
            , "Packaging": DrugObj.Packaging
            , "PreparationQty": DrugObj.PreparationQty
            , "PreparationUnit": DrugObj.PreparationUnit
            , "AdvertiserTag": DrugObj.AdvertiserTag
            , "DrugUnitRID": DrugObj.DrugUnitRID
            , "DefDosage": DrugObj.DefDosage
            , "DefDrugDesperseRID": DrugObj.DefDrugDesperseRID
            , "DefMedBagnosRID": DrugObj.DefMedBagnosRID
            , "DefMedBagnosis": DrugObj.DefMedBagnosis
            , "DefIntervalRID": DrugObj.DefIntervalRID
            , "DefXDays": DrugObj.DefXDays
            , "EnteredBy": DrugObj.EnteredBy
            , "ModifiedBy": DrugObj.ModifiedBy
            , "DateModified": DrugObj.DateModified
            , "Manufacturer": DrugObj.Manufacturer
            , "Distributor": DrugObj.Distributor
            , "Marketer": DrugObj.Marketer
            , "Contents": DrugObj.Contents
            , "Indications": DrugObj.Indications
            , "Dosage": DrugObj.Dosage
            , "Overdosage": DrugObj.Overdosage
            , "Administration": DrugObj.Administration
            , "Contraindications": DrugObj.Contraindications
            , "SpecialPrecautions": DrugObj.SpecialPrecautions
            , "AdverseDrugReactions": DrugObj.AdverseDrugReactions
            , "PregnancyCategory": DrugObj.PregnancyCategory
            , "Storage": DrugObj.Storage
            , "Description": DrugObj.Description
            , "MechanismofAction": DrugObj.MechanismofAction
            , "ATCClassification": DrugObj.ATCClassification
            , "PoisonSchedule": DrugObj.PoisonSchedule
            , "Presentation": DrugObj.Presentation
            , "DeptCode": DrugObj.DeptCode
            , "InSynched": DrugObj.InSynched
            , "InActive": DrugObj.InActive
            
            
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertMedicine',
            responseType: 'json',
            data: DrugData,
            cache: true
        });
    };






    // messages

    obj.getNewMessages = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNewMessages&userPxRID=' + userPxRID,
        });

    };


    obj.sendMessage = function (byRID, toRID, newMessageObj) {
        var MessageData = {
          "byRID" : byRID
          , "toRID" : toRID
          , "messageSubject" : newMessageObj.messageSubject
          , "messageContent" : newMessageObj.messageContent
          , "messageBoxRID" : newMessageObj.messageBoxRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiSendMessage'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };

    obj.autoSaveNewMessage = function (newMessageObj) {
        var MessageData = {
          "messageSubject" : newMessageObj.messageSubject
          , "messageContent" : newMessageObj.messageContent
          , "messageBoxRID" : newMessageObj.messageBoxRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiAutoSaveNewMessage'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };

    obj.getMessages = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetMessages&userPxRID=' + userPxRID,
        });

    };

    obj.getDraftMessages = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDraftMessages&userPxRID=' + userPxRID,
        });

    };

    obj.viewMessages = function (messageBoxRID, toRID) {
        var MessageData = {
          "messageBoxRID" : messageBoxRID
          , "toRID" : toRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiViewMessages'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };

    obj.alertMessages = function (messageBoxRID, toRID) {
        var MessageData = {
          "messageBoxRID" : messageBoxRID
          , "toRID" : toRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiAlertMessages'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };

    obj.deleteMessage = function (messageBoxRID) {
        var MessageData = {
          "messageBoxRID" : messageBoxRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiDeleteMessage'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };


    obj.getMessageWhereToAttachFile = function(userPxRID, PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetMessageWhereToAttachFile&userPxRID=' + userPxRID + '&PxRID=' + PxRID,
        });

    };

    var formAttachFileToMessage = [];
    var filesAttachFileToMessage = [];

    obj.sendAttachFileToMessage = function (messageBoxRID, filesAttachFileToMessage) {

            formAttachFileToMessage.image = filesAttachFileToMessage[0];
            // console.log(formAttachFileToMessage.image);
              if (filesAttachFileToMessage.length > 0) {
                return $http({
                    method  : 'POST',
                    url: serviceBase + 'apiSendAttachFileToMessage',
                    processData: false,
                    transformRequest: function (data) {
                        var formData = new FormData();
                        formData.append("image", formAttachFileToMessage.image); 
                        formData.append("messageBoxRID",  messageBoxRID);  
                        return formData;  
                        console.log(formData);
                    },  
                    data : formAttachFileToMessage,
                    headers: {
                           'Content-Type': undefined
                    }
                });

            }else{
                alert("No file!");
            }
    };



    obj.createNewMessage = function(userPxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCreateNewMessage&userPxRID=' + userPxRID,
        });

    };


    obj.getNewMessageAttachFile = function(messageBoxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNewMessageAttachFile&messageBoxRID=' + messageBoxRID,
        });

    };

    

    obj.removeNewMessageAttachedFile = function (messageAttachFileRID) {
        var MessageData = {
          "messageAttachFileRID" : messageAttachFileRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiRemoveNewMessageAttachedFile'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };


    obj.getNewMessageRecipient = function(messageBoxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNewMessageRecipient&messageBoxRID=' + messageBoxRID,
        });

    };


    obj.insertNewMessageRecipient = function (messageBoxRID, toRID) {
        var MessageData = {
          "messageBoxRID" : messageBoxRID
          , "toRID" : toRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiInsertNewMessageRecipient'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };


    obj.removeNewMessageRecipient = function (messageRecipientRID) {
        var MessageData = {
          "messageRecipientRID" : messageRecipientRID
          
        }

        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiRemoveNewMessageRecipient'
          ,responseType: 'json'
          ,data: MessageData
          ,cache:true
        });
    };


   // Medical Record request
    obj.getMedRequestList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetMedRequestList',
        });
    }


    obj.ReleaseSignRequest = function(releaseStatus, releaseDate, releasePxRID, requestmedRecordRID) {
        console.log(releaseDate);

            var requestmedRecordOBJData = {
                "releaseStatus": releaseStatus,
                "releaseDate": releaseDate,
                "releasePxRID": releasePxRID,
                "requestmedRecordRID": requestmedRecordRID

            };

            return $http({
                method: 'POST',
                url: serviceBase + 'apiReleaseSignRequest',
                responseType: 'json',
                data: requestmedRecordOBJData,
                cache: true
            });
        }


    // Surgical forms fixer
    obj.getOperatingRoomScheduleReportAllList = function(fromDate, toDate) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetOperatingRoomScheduleReportAllList&fromDate=' + fromDate + '&toDate=' + toDate,
        });
    };


    obj.insertSurgerySchedule = function (surgerySchedlistOBJ) {
        console.log(surgerySchedlistOBJ);
        var SurgeryScheduleData = {
          "wrid" : surgerySchedlistOBJ.wrid
          , "orCaseRID" : surgerySchedlistOBJ.orCaseRID
          , "ClinixRID" : surgerySchedlistOBJ.ClinixRID
          , "HospRID" : surgerySchedlistOBJ.HospRID
          , "diagnosis" : surgerySchedlistOBJ.diagnosis
          , "SurgeryType" : surgerySchedlistOBJ.SurgeryType
          , "SurgeryDate" : surgerySchedlistOBJ.SurgeryDate
          , "SurgeryTime" : surgerySchedlistOBJ.SurgeryTime
          , "Surgeon" : surgerySchedlistOBJ.Surgeon
          , "SurgeryTimeEnd" : surgerySchedlistOBJ.SurgeryTimeEnd
          , "Cardio" : surgerySchedlistOBJ.Cardio
          , "Assistant" : surgerySchedlistOBJ.Assistant
          , "Anesthesio" : surgerySchedlistOBJ.Anesthesio
          , "AnesthesiaType" : surgerySchedlistOBJ.AnesthesiaType
          , "circulatingNurse" : surgerySchedlistOBJ.circulatingNurse
          , "scrubNurse" : surgerySchedlistOBJ.scrubNurse
          , "Others" : surgerySchedlistOBJ.Others
          , "operatingRoom" : surgerySchedlistOBJ.operatingRoom

        }


        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiInsertSurgerySchedule'
          ,responseType: 'json'
          ,data: SurgeryScheduleData
          ,cache:true
        });
    };


    obj.getPxPreopDiagnosis = function(HospRID, ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetPxPreopDiagnosis&HospRID=' + HospRID + '&ClinixRID=' + ClinixRID,
        });
    };

    obj.getLastORCaseNumber = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetLastORCaseNumber'
        });
    };


    obj.signSurgerySchedule = function (wrid, signedPxRID) {
        var SurgeryScheduleData = {
          "wrid" : wrid
          , "signedPxRID" : signedPxRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiSignSurgerySchedule'
          ,responseType: 'json'
          ,data: SurgeryScheduleData
          ,cache:true
        });
    };

    obj.signOrNurseSurgerySchedule = function (wrid, signedPxRID) {
        var SurgeryScheduleData = {
          "wrid" : wrid
          , "signedPxRID" : signedPxRID
        }
        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiSignOrNurseSurgerySchedule'
          ,responseType: 'json'
          ,data: SurgeryScheduleData
          ,cache:true
        });
    };


    obj.getFinalORcaseList = function(toDate) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetFinalORcaseList&toDate=' + toDate,
        });
    };

    obj.updateOtherSurgicalFormsAction = function (relateSurgerySchedOBJ) {
        console.log(relateSurgerySchedOBJ);
        var otherSurgeryScheduleData = {
          "wrid" : relateSurgerySchedOBJ.wrid
          , "orCaseRID" : relateSurgerySchedOBJ.orCaseRID
          , "ClinixRID" : relateSurgerySchedOBJ.ClinixRID
          , "HospRID" : relateSurgerySchedOBJ.HospRID
        }


        return $http({
           method: 'POST'
          ,url: serviceBase + 'apiupdateOtherSurgicalFormsAction'
          ,responseType: 'json'
          ,data: otherSurgeryScheduleData
          ,cache:true
        });
    };



    // end messages


    // floor
    return obj;
}]);