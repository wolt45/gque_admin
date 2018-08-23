gmmrApp.factory("dbServices", ['$http', function($http) {

    var myIP = '127.0.0.1';
    // var myIP = '192.168.0.120';

    var serviceBase = '../services/';

    //use custom servicebase for todaycollection
    var serviceBaseNew = 'services/';
    //end

    var obj = {};


    obj.getMyIP = function() {
        return myIP;
    }

    obj.LoginAccount = function(Username, Password) {
        var UserData = {
            "Username": Username,
            "Password": Password
        };

        return $http({
            method: 'POST',
            url: serviceBaseNew + 'apiLogin',
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

    obj.getPxList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiPxList',
        });
    }


    obj.getNonPxList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiNonPxList',
        });
    }

    obj.getPxItem = function(pxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiPxItem&id=' + pxrid,
        });
    }

    obj.getCLINIXItem = function(clnxrid) {

        return $http({
            method: 'GET',
            url: serviceBase + 'apiClinixItem&id=' + clnxrid,
        });
    }


    obj.getHMOList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiHMOList',
        });
    }

    obj.getDoctorsList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDoctorsList',
        });
    }

    obj.getPxClass = function() {
        return $http.get(serviceBase + 'apigetPxClass');
    }

    obj.getPxClassTotal = function() {
        return $http.get(serviceBase + 'apigetPxTotalClass');
    }

    obj.authenticateUser = function(Username, Password) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiAuthenticateUser&Username=' + Username + '&Password=' + Password,
        });
    }

    obj.getPxHISTORY = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiPxHistory&id=' + PxRID,
        });
    }

    obj.updatePxHISTORY = function(p_pxrid, callback) {
        $http({
            method: 'GET',
            url: serviceBase + 'apiUpdatePxHistory&id=' + p_pxrid,
            responseType: 'json',
            cache: true
        }).success(callback);
    }

    obj.getCLINIXcharges = function(clnxrid, callback) {
        $http.get(serviceBase + 'apiClinixCharges&id=' + clnxrid).success(callback);
    }

    obj.deletePEChargeItem = function(PEChargesRID, callback) {
        $http({
            method: 'DELETE',
            url: serviceBase + 'apiDeletePECharges&id=' + PEChargesRID,
            cache: true
        }).success(callback);
    }

    obj.getLASTPxItem = function(callback) {
        $http.get(serviceBase + 'apiLASTPxItem').success(callback);
    }


    obj.setAppointment = function(appointmentOBJ, PxRID, DOB, TranStatus) {

        var APPTdata = {
            "ClinixRID": appointmentOBJ.ClinixRID,
            "DOB": DOB,
            "PxRID": PxRID,
            "AppDateSet": appointmentOBJ.AppDateSet,
            "DateVisit": appointmentOBJ.DateVisit,
            "AppTimeSet": appointmentOBJ.AppTimeSet,
            "AppArivalTimeSet": appointmentOBJ.AppArivalTimeSet,
            "PurposeOfVisit": appointmentOBJ.PurposeOfVisit,
            "DokPxRID": appointmentOBJ.DokPxRID,
            "assistingphysic": appointmentOBJ.assistingphysic,
            "HospitalRID": appointmentOBJ.HospitalRID,
            "Hospital": appointmentOBJ.Hospital,
            "TranStatus": TranStatus
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiSetAppointment',
            responseType: 'json',
            data: APPTdata,
            cache: true
        });
    }


    // CANCEL APPOINTMENT
    obj.cancelAppointment = function(ClinixRID) {
        var pxdata = {
            "ClinixRID": ClinixRID
        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiCancelAppointment',
            responseType: 'json',
            data: pxdata,
            cache: true
        });
    }



    obj.InsUserItem = function(username) {

        var pxdataInfo = {
            "username": username
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsUserItem',
            responseType: 'json',
            data: pxdataInfo,
            cache: true
        });
    }

    obj.InsUserItemSave = function(pxrid, username, password) {

        var pxdataInfo = {
            "pxrid": pxrid,
            "username": username,
            "password": password,
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsUserItemSave',
            responseType: 'json',
            data: pxdataInfo,
            cache: true
        });
    }

    obj.getPxDoc = function(PxRID, callback) {
        //alert ("HIT factory getTodayList !");
        $http.get(serviceBase + 'apiLoadpxdoc&id=' + PxRID).success(callback);
    }


    obj.UpUserItem = function(pxrid, newusername, newpassword) {
        var pxdataInfo = {
            "pxrid": pxrid,
            "newusername": newusername,
            "newpassword": newpassword,
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpUserItem',
            responseType: 'json',
            data: pxdataInfo,
            cache: true
        });
    }

    obj.GetUserData = function(PxRID) {
        return $http.get(serviceBase + 'apiGetUserData&PxRID=' + PxRID);
    }

    obj.CheckUserData = function(txtUser, txtPWD, callback) {
        var checkUser = {
            "txtUser": txtUser,
            "txtPWD": txtPWD
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiCheckUser',
            data: checkUser,
            cache: true
        }).success(callback);
    };

    obj.CheckUserDataExist = function(pxrid, txtUser, txtPWD) {
        var checkUser = {
            "pxrid": pxrid,
            "txtUser": txtUser,
            "txtPWD": txtPWD
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiCheckUserExist',
            data: checkUser,
            cache: true
        });
    };

    obj.insertPxItem = function(RegDateAge, pxdataObj, userPxRID) {

        var pxdata = {
            "PxRID": pxdataObj.PxRID,
            "RegDate": pxdataObj.RegDate,
            "LastName": pxdataObj.LastName,
            "FirstName": pxdataObj.FirstName,
            "MiddleName": pxdataObj.MiddleName,
            "Street": pxdataObj.Street,
            "City": pxdataObj.City,
            "Province": pxdataObj.Province,
            "DOB": pxdataObj.DOB,
            "Sex": pxdataObj.Sex,
            "pxClassification": pxdataObj.pxClassification,
            "TIN": pxdataObj.TIN,
            "SSS": pxdataObj.SSS,
            "GSIS": pxdataObj.GSIS,
            "PagIBIG": pxdataObj.PagIBIG,
            "PhilHealth": pxdataObj.PhilHealth,
            "MobileCon": pxdataObj.MobileCon,
            "OfficeCon": pxdataObj.OfficeCon,
            "HomeCon": pxdataObj.HomeCon,
            "Employer": pxdataObj.Employer,
            "Email": pxdataObj.Email,
            "Occupation": pxdataObj.Occupation,
            "MaritalStatus": pxdataObj.MaritalStatus,
            "SpouseName": pxdataObj.SpouseName,
            "FamilyDoctor": pxdataObj.FamilyDoctor,
            "FamilyDoctorSpecialty": pxdataObj.FamilyDoctorSpecialty,
            "FamilyDoctorPhone": pxdataObj.FamilyDoctorPhone,
            "NearestRelative": pxdataObj.NearestRelative,
            "RelativeCon": pxdataObj.RelativeCon,
            "PersonDataType": pxdataObj.PersonDataType,
            "ReferralType": pxdataObj.ReferralType,
            "ReferredBy": pxdataObj.ReferredBy,
            "RegBy": userPxRID,
            "RegDateAge": RegDateAge
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertPxItem',
            responseType: 'json',
            data: pxdata,
            cache: true
        });
    };

    obj.insertPersonelItem = function(pxdata, callback) {
        var pxdata = {
            "PxRID": pxdata.PxRID,
            "RegDate": pxdata.RegDate,
            "LastName": pxdata.LastName,
            "FirstName": pxdata.FirstName,
            "MiddleName": pxdata.MiddleName,
            "Street": pxdata.Street,
            "City": pxdata.City,
            "Province": pxdata.Province,
            "DOB": pxdata.DOB,
            "Sex": pxdata.Sex,
            "TIN": pxdata.TIN,
            "SSS": pxdata.SSS,
            "GSIS": pxdata.GSIS,
            "PagIBIG": pxdata.PagIBIG,
            "PhilHealth": pxdata.PhilHealth,
            "MobileCon": pxdata.MobileCon,
            "OfficeCon": pxdata.OfficeCon,
            "HomeCon": pxdata.HomeCon,
            "Email": pxdata.Email,
            "Occupation": pxdata.Occupation,
            "MaritalStatus": pxdata.MaritalStatus,
            "SpouseName": pxdata.SpouseName,
            "PersonDataType": pxdata.PersonDataType,
            "ReferredBy": pxdata.ReferredBy,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertPersonelItem',
            data: pxdata,
            cache: true
        }).success(callback);
    };


    obj.updatePxItem = function(id, pxdata) {

        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdatePxItem&id=' + id,
            responseType: 'json',
            data: pxdata,
            cache: true
        });
    };


    obj.deletePxItem = function(id, callback) {
        $http({
            method: 'DELETE',
            url: serviceBase + 'apiDeletePxItem&id=' + id,
            cache: true
        }).success(callback);
    };


    // obj.saveHMOpx = function (hmodata, callback) {
    //   $http({ 
    //     method : 'POST'
    //     ,url: serviceBase + 'apiInsertHMOItem'
    //     ,data : hmodata
    //     ,cache : true
    //   }).success(callback);
    // };





    // Digital Signatures 2016
    // obj.dsigSavePIN = function (pxrid, PIN, callback) {
    //   // just reconstruct the disgObj
    //   var dsigObj = {
    //     "PxRID":  pxrid
    //     , "PIN":  PIN
    //   };
    //   // alert("PxRID: "+dsigObj.PxRID);
    //   // alert("PIN: "+p_dsigObj.PIN);
    //   $http({ 
    //     method : 'POST'
    //     ,url: serviceBase + 'apiSaveDsigPIN'
    //     ,responseType: 'json'
    //     ,data : dsigObj
    //     ,cache : true
    //   }).success(callback);
    // };

    obj.dsigSavePIN = function(PxRID, PIN) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSaveDsigPIN&PIN=' + PIN + '&PxRID=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };


    // TODAY Sections

    obj.getTodayList = function() {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiTDYCollection',
        });
    }

    // TranStatuses 
    obj.getTranStatusArray = function(callback) {
        //alert ("HIT factory  !");
        $http.get(serviceBase + 'apiTranSttsCollection').success(callback);
    }

    // Inert Body parts, delete old one first
    obj.UpdateBodyParts = function(bparts, callback) {
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertBodyParts',
            data: bparts,
            cache: true
        }).success(callback);
    };


    // PEChargesLkUp 
    obj.getPEChargesLkUp = function(callback) {
        //alert ("HIT factory  !");
        $http.get(serviceBase + 'apiPEChargesLkUpCollection').success(callback);
    }

    obj.insertPEChargeItem = function(PECharge, callback) {
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertPECharge',
            data: PECharge,
            cache: true
        }).success(callback);
    };

    obj.updatePEChargesWithPayments = function(PEPayments, callback) {
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdatePEChargesWithPayments',
            data: PEPayments,
            cache: true
        }).success(callback);
    };

    // CLOSE TRANSACTION
    // CLOSE TRANSACTION
    // CLOSE TRANSACTION

    obj.CloseTranService = function(peObj, callback) {
        $http({
            method: 'POST',
            url: serviceBase + 'apiCloseTrans',
            data: peObj,
            cache: true
        }).success(callback);
    };

    obj.getOrgData = function(callback) {
        $http({
            method: 'GET',
            url: serviceBase + 'apiGetOrgData',
            responseType: 'json',
            cache: true
        }).success(callback);
    };





    obj.Update_zPxRIDNOW = function(pxrid, callback) {
        $http({
            method: 'GET',
            url: serviceBase + 'apiInsertZPxRIDNOW?zpxRID=' + pxrid,
            cache: true
        }).success(callback);
    };

    obj.Update_zclinixNOW = function(clnxrid, callback) {
        $http({
            method: 'GET',
            url: serviceBase + 'apiInsertZClinixNOW?zclinixRID=' + clnxrid,
            cache: true
        }).success(callback);
    };






    obj.getZClinix = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetZClinix&zclinixRID=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    // INITIAL INTERVIEW
    // INITIAL INTERVIEW

    obj.getChiefComplaint = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetChiefComplaint&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getEthiology = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetEthiology&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPastTreatment = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPastTreatment&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPrevSurgeries = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPrevSurgeries&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getLabs = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetLabs&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getWhatLabs = function(clnxrid, WhatLabs) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetLabs_WhatLabs&id=' + clnxrid + '&whatLabs=' + WhatLabs,
            responseType: 'json',
            cache: true
        });
    };



    obj.getMedHist = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetMedHist&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getWhatMedHist = function(clnxrid, WhatMedHist) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetMedHist_WhatMedHist&id=' + clnxrid + '&whatMedHist=' + WhatMedHist,
            responseType: 'json',
            cache: true
        });
    };



    // AMBULATORY STATUS
    // AMBULATORY STATUS
    // AMBULATORY STATUS

    obj.getAmbulatoryStatus = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAmbulatoryStatus&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    // HIP 
    // HIP 
    // HIP 
    obj.getHipMeasures = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipMeasures&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getWhatHipMeasures = function(clnxrid, WhatHipMeasures) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipMeasures_WhatHipMeasures&id=' + clnxrid + '&whatHipMeasures=' + WhatHipMeasures,
            responseType: 'json',
            cache: true
        });
    };


    obj.getHipStanding = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipStanding&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getWhatHipStanding = function(clnxrid, WhatHipStanding) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipStanding_WhatHipStanding&id=' + clnxrid + '&whatHipStanding=' + WhatHipStanding,
            responseType: 'json',
            cache: true
        });
    };


    obj.getHipRangeOfMotion = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipRangeOfMotion&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getWhatHipRangeOfMotion = function(clnxrid, WhatHipRangeOfMotion) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipRangeOfMotion_WhatHipRangeOfMotion&id=' + clnxrid + '&whatHipRangeOfMotion=' + WhatHipRangeOfMotion,
            responseType: 'json',
            cache: true
        });
    };


    obj.getHipXRays = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHipXRays&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    // obj.getWhatHipXRays = function (clnxrid, WhatHipXRays){
    //    return $http({
    //      method: 'GET'
    //     ,url: serviceBase + 'apiGetHipXRays_WhatHipXRays&id=' + clnxrid + '&whatHipXRays=' + WhatHipXRays
    //     ,responseType: 'json'
    //     ,cache:true
    //   });
    // };


    // KNEE
    // KNEE
    // KNEE
    obj.getKNEEMeasures = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetKNEEMeasures&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getKNEEappearance = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetgetKNEEappearance&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getWhatKNEEappearance = function(clnxrid, WhatKNEEappearance) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetgetKNEEappearance_WhatKNEEappearance&id=' + clnxrid + '&whatKNEEappearance=' + WhatKNEEappearance,
            responseType: 'json',
            cache: true
        });
    };

    obj.getKNEEalignment = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetgetKNEEalignment&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getKNEEmotionrange = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetgetKNEEmotionrange&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getKNEExrays = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetgetKNEExrays&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };



    obj.getTraumaLongBone = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetTraumaLongBone&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getTraumaLongBone2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetTraumaLongBone2&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };







    // DIAGNOISIS
    // DIAGNOISIS
    // DIAGNOISIS
    obj.getDiagnosis = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagnosis&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getDiagsManagement = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsMgmt&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getWhatDiagsManagement = function(clnxrid, WhatDiagsManagement) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsMgmt_WhatDiagsManagement&id=' + clnxrid + '&whatDiagsManagement=' + WhatDiagsManagement,
            responseType: 'json',
            cache: true
        });
    };




    obj.getDiagsMedication = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsMedication&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getDiagsSchedForSurgery = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsSchedForSurgery&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.getDiagsSchedForSurgeryAll = function(fromDate, toDate) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsSchedForSurgeryAll&fromDate=' + fromDate + '&toDate=' + toDate,
            responseType: 'json',
            cache: true
        });
    }

    obj.getDiagsSchedForSurgeryPatient = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsSchedForSurgeryPatient&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getDiagsDisposition = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsDisposition&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getDiagsNotes = function(clnxrid) {
        // alert(clnxrid);
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetDiagsNotes&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.updateNarr_ChiefCompALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_ChiefCompALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_ChiefComp = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_ChiefComp',
            data: JSONObj,
            cache: true
        });
    };


    obj.updateNarr_EthiologyALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_EthiologyALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_Ethiology = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_Ethiology',
            data: JSONObj,
            cache: true
        });
    };


    obj.updateNarr_PastTreatsALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_PastTreatsALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_PastTreats = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_PastTreats',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_PrevSurgALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_PrevSurgALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_PrevSurg = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_PrevSurg',
            data: JSONObj,
            cache: true
        });
    };


    obj.updateNarr_LABSALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_LABSALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_LABS = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_LABS',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_MedHistALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_MedHistALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_MedHist = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_MedHist',
            data: JSONObj,
            cache: true
        });
    };

    // AMBULATORY STATUS
    // AMBULATORY STATUS
    // AMBULATORY STATUS

    obj.updateNarr_AMBUStatusALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_AMBUSttsALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_AMBUStatus = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_AMBUStts',
            data: JSONObj,
            cache: true
        });
    };

    // H  I P
    //    H I P
    //      H I P
    obj.updateNarr_HIPMeasuresALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPMeasuresALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_HIPMeasures = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPMeasures',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_HIPstandALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPstandALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_HIPstand = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPstand',
            data: JSONObj,
            cache: true
        });
    };


    obj.updateNarr_HIPromALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPromALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_HIProm = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIProm',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_HIPxrayALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPxrayALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_HIPxray = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_HIPxray',
            data: JSONObj,
            cache: true
        });
    };

    // K N E E
    //    K N E E
    //       K N E E
    obj.updateNarr_KNEEMeasureALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEMeasuresALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_KNEEMeasure = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEMeasures',
            data: JSONObj,
            cache: true
        });
    };

    // Appearance
    obj.updateNarr_KNEEappearanceALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEappearanceALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_KNEEappearance = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEappearance',
            data: JSONObj,
            cache: true
        });
    };

    // Alignment
    obj.updateNarr_KNEEalignALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEalignmentALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_KNEEalign = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEalignment',
            data: JSONObj,
            cache: true
        });
    };

    // KNEE Range of Motion
    obj.updateNarr_KNEEmotionrangeALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEmotionRangeALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_KNEEmotionrange = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEEmotionRange',
            data: JSONObj,
            cache: true
        });
    };

    // KNEE XRays
    obj.updateNarr_KNEExrayALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEExrayALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_KNEExray = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_KNEExray',
            data: JSONObj,
            cache: true
        });
    };


    // DIAGNOSIS
    // DIAGNOSIS
    // DIAGNOSIS
    obj.updateNarr_DiagnosisALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagnosisALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_Diagnosis = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_Diagnosis',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_DiagsMGMTALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsMGMTALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_DiagsMGMT = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsMGMT',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_DiagsMedsALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsMEDSALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_DiagsMeds = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsMEDS',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_DiagsSchedSurgALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsSchedSurgALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_DiagsSchedSurg = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsSchedSurg',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_DiagsDispoALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsDISPOALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_DiagsDispo = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsDISPO',
            data: JSONObj,
            cache: true
        });
    };

    obj.updateNarr_DiagsNOTESALLOFF = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsNOTESALLOFF',
            data: JSONObj,
            cache: true
        });
    };
    obj.updateNarr_DiagsNOTES = function(JSONObj) {
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarr_DiagsNOTES',
            data: JSONObj,
            cache: true
        });
    };





    obj.getGENORTHOappearance = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGENORTHOappearance&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getGENORTHOXrayOrder = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetGENORTHOXrayOrder&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getGENORTHOXrayFindings = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetGENORTHOXrayFinding&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getGENORTHODiagnosis = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGENORTHODiagnosis&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getGENORTHOLaboratoryExamination = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGENORTHOLaboratoryExamination&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getGENORTHOPreviousSurgery = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGENORTHOPreviousSurgery&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };




    obj.getFOOTANKLEHistoryOfPresentIllness = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleHistoryOfPresentIllness&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLEPastMedicalHistory = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnklePastMedicalHistory&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLExtremities = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnklextremities&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLExtremities2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnklextremities2&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLERangeofMotion = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleRangeofMotion&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLEVascular = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleVascular&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLEXray = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleXray&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLEMRI = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleMRI&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getFOOTANKLEAsManDis = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiFootAnkleAsManDis&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.getSKELTRAUMAmbulatoryStatus = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSKELTRAUMAmbulatoryStatus&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getSKELTRAUMXrayOrdered = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSKELTRAUMAXrayOrdered&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.getSPORTSKNEESystemic = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEESystemic&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getSPORTSKNEExtremities = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEExtremities&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEExtremities2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEExtremities2&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEErangemotion = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEErangemotion&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEEvascular = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEEvascular&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEESpecialTest = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEESpecialTest&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEEgrosspic = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEEgrosspic&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSKNEEassmandispo = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSKNEEassmandispo&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };



    obj.getSPORTSHOULDERHPI = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERHPI&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getSPORTSHOULDERPMH = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERPMH&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERExtrimities = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERExtrimities&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERExtrimities2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERExtrimities2&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERRangeofMotion = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERRangeofMotion&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERVascular = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERVascular&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERspecialtest = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERspecialtest&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERGrossPictures = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERGrossPictures&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPORTSHOULDERAssDisMan = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPORTSHOULDERAssDisMan&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    obj.getSPINEEvalForm = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPINEEvalForm&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.getSPINEOnsetofspine = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPINEOnsetofspine&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPINEactivities = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPINEactivities&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };
    obj.getSPINECheckAll = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiSPINECheckAll&id=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    //Narrative Report

    obj.getNarrativeMedicalReport = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNarMedRep&clinixrid=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };


    obj.getNarMedRepNurse = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetNarMedRepNurse&clinixrid=' + clnxrid,
            responseType: 'json',
            cache: true
        });
    };

    //================
    //Anesthesia
    //================

    obj.GetSafeSurg = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apisafetySurgShow&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetIntraMonitor = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiIntraMonitor&ClinixRID=' + ClinixRID,
        });

    };

    obj.GetShowIntraAnestNotes = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiIntraNotesShow&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetShowCareUnitAnest1 = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCareUnitAnest1&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetShowCareUnitAnest2 = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCareUnitAnest2&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetShowCareUnitAnest3 = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCareUnitAnest3&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetShowCareUnitAnest4 = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCareUnitAnest4&ClinixRID=' + ClinixRID,
        });
    };

    obj.GetShowCareUnitAnest5 = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCareUnitAnest5&ClinixRID=' + ClinixRID,
        });
    };

    obj.InsertSafeSurg = function(ClinixRID, PxRID, SafeSurgOBJ) {
        var safsurgdata = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "PatientIdentity": SafeSurgOBJ.PatientIdentity,
            "Antimicrobial": SafeSurgOBJ.Antimicrobial,
            "ProcedureConcent": SafeSurgOBJ.ProcedureConcent,
            "Oximeter": SafeSurgOBJ.Oximeter,
            "Marketing": SafeSurgOBJ.Marketing,
            "Allergies": SafeSurgOBJ.Allergies,
            "Intervention": SafeSurgOBJ.Intervention,
            "BloodAvail": SafeSurgOBJ.BloodAvail,
            "PatientReconfirm": SafeSurgOBJ.PatientReconfirm,
            "ImageFilms": SafeSurgOBJ.ImageFilms,
            "MemberIntroduction": SafeSurgOBJ.MemberIntroduction,
            "AnticipatedEvents": SafeSurgOBJ.AnticipatedEvents,
            "ProcedureRecord": SafeSurgOBJ.ProcedureRecord,
            "InstrumentUsed": SafeSurgOBJ.InstrumentUsed,
            "SpecimenLabel": SafeSurgOBJ.SpecimenLabel,
            "ConcernAddress": SafeSurgOBJ.ConcernAddress,
            "AnestNotes": SafeSurgOBJ.AnestNotes
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSafeSurg',
            responseType: 'json',
            data: safsurgdata,
            cache: true
        });
    };

    obj.InsertIntraAnest = function(ClinixRID, PxRID, IntraOBJ, dateIntra, timeIntra) {
        var Intradata = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "dateIntra": dateIntra,
            "timeIntra": timeIntra,
            "BP": IntraOBJ.BP,
            "PR": IntraOBJ.PR,
            "RR": IntraOBJ.RR,
            "TEMP": IntraOBJ.TEMP,
            "SaO2": IntraOBJ.SaO2,
            "REMARKS": IntraOBJ.REMARKS
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiIntraAnest',
            responseType: 'json',
            data: Intradata,
            cache: true
        });
    };

    obj.IntraAnestRemove = function(IntraAnestID) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDeleteIntraAnest&ID=' + IntraAnestID,
            cache: true
        });
    }

    obj.InsIntraNotes = function(ClinixRID, PxRID, IntraAnestNotes, IntraAnestDate) {
        console.log("IntraAnestNotes: " + IntraAnestNotes + " IntraAnestDate: " + IntraAnestDate);
        var IntraNotes = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "IntraAnestDate": IntraAnestDate,
            "IntraAnestNotes": IntraAnestNotes
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsIntraNotes',
            responseType: 'json',
            data: IntraNotes,
            cache: true
        });

        alert('Intra-Op Monitoring Data Saved');
    };

    obj.UpAnestCareUnit1 = function(ClinixRID, PxRID, newrecord) {
        var AnestCareUnit = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "Operation": newrecord.Operation,
            "OperationDate": newrecord.OperationDate,
            "TimeArrivalIN": newrecord.TimeArrivalIN,
            "TimeArrivalOUT": newrecord.TimeArrivalOUT,
            "Anesthesia": newrecord.Anesthesia,
            "AnesthesiaOthers": newrecord.AnesthesiaOthers,
            "AnesthesiaMSo4": newrecord.AnesthesiaMSo4,
            "TimeIN": newrecord.TimeIN,
            "TimeOUT": newrecord.TimeOUT

        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpAnestCareUnit1',
            responseType: 'json',
            data: AnestCareUnit,
            cache: true
        });

    };

    obj.DelAnestCareUnit1 = function(wrid) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDelAnestCareUnit1&wrid=' + wrid,
            cache: true
        });
    }

    obj.UpAnestCareUnit2 = function(ClinixRID, PxRID, newrecord) {
        var AnestCareUnit = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "TimeCareUnit": newrecord.TimeCareUnit,
            "BP": newrecord.BP,
            "PR": newrecord.PR,
            "Temp": newrecord.Temp,
            "Sa02": newrecord.Sa02

        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpAnestCareUnit2',
            responseType: 'json',
            data: AnestCareUnit,
            cache: true
        });

    };

    obj.DelAnestCareUnit2 = function(wrid) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDelAnestCareUnit2&wrid=' + wrid,
            cache: true
        });
    }

    obj.UpAnestCareUnit3 = function(ClinixRID, PxRID, newrecord) {
        var AnestCareUnit = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "Medication": newrecord.Medication,
            "TimeInitial": newrecord.TimeInitial
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpAnestCareUnit3',
            responseType: 'json',
            data: AnestCareUnit,
            cache: true
        });

    };

    obj.DelAnestCareUnit3 = function(wrid) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDelAnestCareUnit3&wrid=' + wrid,
            cache: true
        });

    }

    obj.UpAnestCareUnit4 = function(ClinixRID, PxRID, newrecord) {
        var AnestCareUnit = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "Oxygen": newrecord.Oxygen,
            "Minutes": newrecord.Minutes,
            "NasalMask": newrecord.NasalMask,
            "NasalMaskOther": newrecord.NasalMaskOther,
            "Position": newrecord.Position,
            "PositionOther": newrecord.PositionOther,
            "SafetyDevice": newrecord.SafetyDevice,
            "SafetyDeviceOthers": newrecord.SafetyDeviceOthers,
            "RestrainNeeds": newrecord.RestrainNeeds,
            "AreaValue": newrecord.AreaValue
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpAnestCareUnit4',
            responseType: 'json',
            data: AnestCareUnit,
            cache: true
        });

    };

    obj.DelAnestCareUnit4 = function(wrid) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDelAnestCareUnit4&wrid=' + wrid,
            cache: true
        });

    }


    obj.UpAnestCareUnit5 = function(ClinixRID, PxRID, newrecord) {
        var AnestCareUnit = {
            "ClinixRID": ClinixRID,
            "PxRID": PxRID,
            "ActIn": newrecord.ActIn,
            "Act1hr": newrecord.Act1hr,
            "ActOut": newrecord.ActOut,
            "LarmSolu": newrecord.LarmSolu,
            "LarmVol": newrecord.LarmVol,
            "LarmInfu": newrecord.LarmInfu,
            "LarmLeft": newrecord.LarmLeft,
            "RarmSolu": newrecord.RarmSolu,
            "RarmVol": newrecord.RarmVol,
            "RarmInfu": newrecord.RarmInfu,
            "RarmLeft": newrecord.RarmLeft,
            "StartSolu": newrecord.StartSolu,
            "StartVol": newrecord.StartVol,
            "StartInfu": newrecord.StartInfu,
            "StartLeft": newrecord.StartLeft,
            "InRES": newrecord.InRES,
            "HrRES": newrecord.HrRES,
            "OutRES": newrecord.OutRES,
            "BLOODSolu": newrecord.BLOODSolu,
            "BLOODVol": newrecord.BLOODVol,
            "BLOODInfu": newrecord.BLOODInfu,
            "BLOODLeft": newrecord.BLOODLeft,
            "BLOODStartSolu": newrecord.BLOODStartSolu,
            "BLOODStartVol": newrecord.BLOODStartVol,
            "BLOODStartInfu": newrecord.BLOODStartInfu,
            "BLOODStartLeft": newrecord.BLOODStartLeft,
            "InCIR": newrecord.InCIR,
            "HrCIR": newrecord.HrCIR,
            "OutCIR": newrecord.OutCIR,
            "FrORSolu": newrecord.FrORSolu,
            "FrORVol": newrecord.FrORVol,
            "FrORInfu": newrecord.FrORInfu,
            "FrORLeft": newrecord.FrORLeft,
            "FrORStartedSolu": newrecord.FrORStartedSolu,
            "FrORStartedVol": newrecord.FrORStartedVol,
            "FrORStartedInfu": newrecord.FrORStartedInfu,
            "FrORStartedLeft": newrecord.FrORStartedLeft,
            "LarmSolu": newrecord.LarmSolu,
            "LarmVol": newrecord.LarmVol,
            "LarmInfu": newrecord.LarmInfu,
            "LarmLeft": newrecord.LarmLeft,
            "InCON": newrecord.InCON,
            "HrCON": newrecord.HrCON,
            "OutCON": newrecord.OutCON,
            "Urine": newrecord.Urine,
            "NGT": newrecord.NGT,
            "TTUDE": newrecord.TTUDE,
            "Hemovac": newrecord.Hemovac,
            "Others": newrecord.Others,
            "OxyIn": newrecord.OxyIn,
            "OxyHr": newrecord.OxyHr,
            "OxyOut": newrecord.OxyOut,
            "Dressing": newrecord.Dressing,
            "AnestNotes": newrecord.AnestNotes
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpAnestCareUnit5',
            responseType: 'json',
            data: AnestCareUnit,
            cache: true
        });

    };

    obj.DelAnestCareUnit5 = function(wrid) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDelAnestCareUnit5&wrid=' + wrid,
            cache: true
        });
    }

    //================
    //End Anesthesia
    //================

    //Consent
    // by delo 180215

    obj.getConsentSurgery = function(ClinixRID) {

        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetConsentSurgery&ClinixRID=' + ClinixRID,
        });
    };

    obj.insertConsentSurgery = function(ConsentSurgeryOBJ) {
        console.log(ConsentSurgeryOBJ);
        var consentSurgerydata = {
            "ClinixRID": ConsentSurgeryOBJ.ClinixRID,
            "PxRID": ConsentSurgeryOBJ.PxRID,
            "nameOfSurgery": ConsentSurgeryOBJ.nameOfSurgery,
            "relationToPatient": ConsentSurgeryOBJ.relationToPatient,
            "dayOfSurgery": ConsentSurgeryOBJ.dayOfSurgery,
            "dateOfSurgery": ConsentSurgeryOBJ.dateOfSurgery,
            "locationOfSurgery": ConsentSurgeryOBJ.locationOfSurgery
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertConsentSurgery',
            responseType: 'json',
            data: consentSurgerydata,
            cache: true
        });
    };


    obj.signWitness1ConsentSurgery = function(ClinixRID, witness1PxRID) {
        var consentSurgerydata = {
            "ClinixRID": ClinixRID,
            "witness1PxRID": witness1PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness1ConsentSurgery',
            responseType: 'json',
            data: consentSurgerydata,
            cache: true
        });
    };


    obj.signWitness2ConsentSurgery = function(ClinixRID, witness2PxRID) {
        var consentSurgerydata = {
            "ClinixRID": ClinixRID,
            "witness2PxRID": witness2PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness2ConsentSurgery',
            responseType: 'json',
            data: consentSurgerydata,
            cache: true
        });
    };


    obj.signresponsiblePersonConsentSurgery = function(ClinixRID, responsiblePersonPxRID) {
        var consentSurgerydata = {
            "ClinixRID": ClinixRID,
            "responsiblePersonPxRID": responsiblePersonPxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignresponsiblePersonConsentSurgery',
            responseType: 'json',
            data: consentSurgerydata,
            cache: true
        });
    };


    obj.getConsentTreatment = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetConsentTreatment&ClinixRID=' + ClinixRID,
        });
    };

    obj.insertConsentTreatment = function(ConsentTreatmentOBJ) {
        var ConsentTreatmentdata = {
            "ClinixRID": ConsentTreatmentOBJ.ClinixRID,
            "PxRID": ConsentTreatmentOBJ.PxRID,
            "nameOfTreatment": ConsentTreatmentOBJ.nameOfTreatment,
            "relationToPatient": ConsentTreatmentOBJ.relationToPatient,
            "dayOfTreatment": ConsentTreatmentOBJ.dayOfTreatment,
            "dateOfTreatment": ConsentTreatmentOBJ.dateOfTreatment,
            "locationOfTreatment": ConsentTreatmentOBJ.locationOfTreatment
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertConsentTreatment',
            responseType: 'json',
            data: ConsentTreatmentdata,
            cache: true
        });
    };


    obj.signWitness1ConsentTreatment = function(ClinixRID, witness1PxRID) {
        var ConsentTreatmentdata = {
            "ClinixRID": ClinixRID,
            "witness1PxRID": witness1PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness1ConsentTreatment',
            responseType: 'json',
            data: ConsentTreatmentdata,
            cache: true
        });
    };


    obj.signWitness2ConsentTreatment = function(ClinixRID, witness2PxRID) {
        var ConsentTreatmentdata = {
            "ClinixRID": ClinixRID,
            "witness2PxRID": witness2PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness2ConsentTreatment',
            responseType: 'json',
            data: ConsentTreatmentdata,
            cache: true
        });
    };


    obj.signresponsiblePersonConsentTreatment = function(ClinixRID, responsiblePersonPxRID) {
        var ConsentTreatmentdata = {
            "ClinixRID": ClinixRID,
            "responsiblePersonPxRID": responsiblePersonPxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignresponsiblePersonConsentTreatment',
            responseType: 'json',
            data: ConsentTreatmentdata,
            cache: true
        });
    };


    //consent End



    //Home Instruction
    // by delo 180215

    obj.GetHomeInstruction = function(ClinixRID, PxRID) {
        return $http.get(serviceBase + 'apiGetHomeInstruction&ClinixRID=' + ClinixRID + '&PxRID=' + PxRID);
    };

    obj.InserthomeInstructionSave = function(clinix, pxrid, homeInstruction) {
        var consentSurgerydata = {
            "ClinixRID": clinix,
            "PxRID": pxrid,
            "roomNo": homeInstruction.roomNo,
            "dateOfDischarge": homeInstruction.dateOfDischarge,
            "nursingUnit": homeInstruction.nursingUnit,
            "followUpPhysician": homeInstruction.followUpPhysician,
            "followUpLocation": homeInstruction.followUpLocation,
            "followUpDateTime": homeInstruction.followUpDateTime,
            "toOtherCenters": homeInstruction.toOtherCenters,
            "followingDischargeInstruction": homeInstruction.followingDischargeInstruction,
            "generalOrFullDiet": homeInstruction.generalOrFullDiet,
            "softDiet": homeInstruction.softDiet,
            "lowSaltDiet": homeInstruction.lowSaltDiet,
            "lowFatDiet": homeInstruction.lowFatDiet,
            "othersDiet": homeInstruction.othersDiet,
            "detailsDiet": homeInstruction.detailsDiet,
            "noRestrictions": homeInstruction.noRestrictions,
            "withRestrictions": homeInstruction.withRestrictions,
            "detailsExerciseOrActivity": homeInstruction.detailsExerciseOrActivity,
            "specialHomeInstructions": homeInstruction.specialHomeInstructions,
            "instructionGivenBy": homeInstruction.instructionGivenBy,
            "instructionGivenByDate": homeInstruction.instructionGivenByDate,
            "instructionReceivedBy": homeInstruction.instructionReceivedBy,
            "instructionReceivedByDate": homeInstruction.instructionReceivedByDate

        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertHomeInstruction&id=' + clinix,
            responseType: 'json',
            data: consentSurgerydata,
            cache: true
        });
    };


    obj.InserthomeInstructionMedicationSave = function(clinix, pxrid, homeInstructionID, homeInstructionMedication) {
        var homeInstructionMedication = {
            "ClinixRID": clinix,
            "PxRID": pxrid,
            "homeInstructionID": homeInstructionID,
            "medicineName": homeInstructionMedication.medicineName,
            "sixam": homeInstructionMedication.sixam,
            "eightam": homeInstructionMedication.eightam,
            "twelvenn": homeInstructionMedication.twelvenn,
            "fourpm": homeInstructionMedication.fourpm,
            "sixpm": homeInstructionMedication.sixpm,
            "eightpm": homeInstructionMedication.eightpm,
            "tenpm": homeInstructionMedication.tenpm

        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInserthomeInstructionMedication&id=' + clinix,
            responseType: 'json',
            data: homeInstructionMedication,
            cache: true
        });


        alert('Medication Data Saved');
    };

    obj.GetHomeInstructionMedication = function(ClinixRID) {
        return $http.get(serviceBase + 'apiGetHomeInstructionMedication&ClinixRID=' + ClinixRID);
    };

    obj.DeletehomeInstructionMedication = function(homeInstructionMedID) {
        return $http({
            method: 'DELETE',
            url: serviceBase + 'apiDeletehomeInstructionMedication&homeInstructionMedID=' + homeInstructionMedID,
            cache: true
        });

        alert('Medication Data Deleted');
    }

    //Home Instruction end

    obj.getConsentAdminAnesthesia = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetConsentAdminAnesthesia&ClinixRID=' + ClinixRID,
        });
    };

    obj.insertConsentAdminAnesthesia = function(ConsentAdminAnesthesiaOBJ) {
        var ConsentAdminAnesthesiadata = {
            "ClinixRID": ConsentAdminAnesthesiaOBJ.ClinixRID,
            "PxRID": ConsentAdminAnesthesiaOBJ.PxRID,
            "typeOfAnesthesia": ConsentAdminAnesthesiaOBJ.typeOfAnesthesia,
            "anesthesiologist": ConsentAdminAnesthesiaOBJ.anesthesiologist,
            "nameOfTreatment": ConsentAdminAnesthesiaOBJ.nameOfTreatment,
            "dayOfTreatment": ConsentAdminAnesthesiaOBJ.dayOfTreatment,
            "dateOfTreatment": ConsentAdminAnesthesiaOBJ.dateOfTreatment
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertConsentAdminAnesthesia',
            responseType: 'json',
            data: ConsentAdminAnesthesiadata,
            cache: true
        });
    };


    obj.signWitness1ConsentAdminAnesthesia = function(ClinixRID, witness1PxRID) {
        var ConsentAdminAnesthesiadata = {
            "ClinixRID": ClinixRID,
            "witness1PxRID": witness1PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness1ConsentAdminAnesthesia',
            responseType: 'json',
            data: ConsentAdminAnesthesiadata,
            cache: true
        });
    };


    obj.signWitness2ConsentAdminAnesthesia = function(ClinixRID, witness2PxRID) {
        var ConsentAdminAnesthesiadata = {
            "ClinixRID": ClinixRID,
            "witness2PxRID": witness2PxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignWitness2ConsentAdminAnesthesia',
            responseType: 'json',
            data: ConsentAdminAnesthesiadata,
            cache: true
        });
    };

    obj.signresponsiblePersonConsentAdminAnesthesia = function(ClinixRID, responsiblePersonPxRID) {
        var ConsentAdminAnesthesiadata = {
            "ClinixRID": ClinixRID,
            "responsiblePersonPxRID": responsiblePersonPxRID
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiSignresponsiblePersonConsentAdminAnesthesia',
            responseType: 'json',
            data: ConsentAdminAnesthesiadata,
            cache: true
        });
    };

    //followup Notes

    obj.getFollowUpNotes = function(ClinixRID, callback) {
        return $http.get(serviceBase + 'apiGetFollowUpNotes&ClinixRID=' + ClinixRID);
    };

    //end followup Notes

    // admit


    obj.insertHosptialChart = function(HospObj, callback) {

        // alert(pxrid+" "+username+" "+password);
        var AdmissionData = {
            "AdmissionOrder": HospObj.AdmissionOrder,
            "PxRID": HospObj.PxRID,
            "ClinixRID": HospObj.ClinixRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertHosptialChart',
            responseType: 'json',
            data: AdmissionData,
            cache: true
        }).success(callback);
    };

    obj.insertAdmissionOrders = function(HospObj, PxRID, ClinixRID, callback) {

        // alert(pxrid+" "+username+" "+password);
        var AdmissionData = {
            "AdmissionOrder": HospObj.AdmissionOrder,
            "PxRID": PxRID,
            "ClinixRID": ClinixRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertAdmissionOrders',
            responseType: 'json',
            data: AdmissionData,
            cache: true
        }).success(callback);
    };

    obj.getAdmissionOrders = function(ClinixRID, callback) {
        $http.get(serviceBase + 'apiGetAdmissionOrders&ClinixRID=' + ClinixRID).success(callback);
    };

    obj.signAdmissionOrders = function(UserPxRID, HospRID, callback) {

        // alert(pxrid+" "+username+" "+password);
        var AdmissionData = {
            "UserPxRID": UserPxRID,
            "HospRID": HospRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiSignAdmissionOrders',
            responseType: 'json',
            data: AdmissionData,
            cache: true
        }).success(callback);
    };

    //admint end


    // supplemental

    obj.insertSupplemental = function(SupplementalRID, HospRID, PxRID, ClinixRID, callback) {

        // alert(pxrid+" "+username+" "+password);
        var SupplementalData = {
            "HospRID": HospRID,
            "PxRID": PxRID,
            "ClinixRID": ClinixRID,
            "SupplementalRID": SupplementalRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertSupplemental',
            responseType: 'json',
            data: SupplementalData,
            cache: true
        }).success(callback);
    };

    obj.getSupplemental = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetSupplemental&HospRID=' + HospRID).success(callback);
    };

    obj.insertSupplementalDetails = function(SupplementalDetailsObj, SupplementalRID, callback) {

        // alert(pxrid+" "+username+" "+password);
        var SupplementalData = {
            "SupplementalDetailRID": SupplementalDetailsObj.SupplementalDetailRID,
            "ItemDescription": SupplementalDetailsObj.ItemDescription,
            "Qty": SupplementalDetailsObj.Qty,
            "SupplementalRID": SupplementalRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'insertSupplementalDetails',
            responseType: 'json',
            data: SupplementalData,
            cache: true
        }).success(callback);
    };


    obj.getSupplementalDetails = function(SupplementalRID, callback) {
        $http.get(serviceBase + 'apiGetSupplementalDetails&SupplementalRID=' + SupplementalRID).success(callback);
    };


    obj.signSupplemental = function(UserPxRID, SupplementalRID, callback) {
        var SupplementalData = {
            "UserPxRID": UserPxRID,
            "SupplementalRID": SupplementalRID,
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiSignSupplemental',
            responseType: 'json',
            data: SupplementalData,
            cache: true
        }).success(callback);
    };


    obj.DelSupplementalDetails = function(SupplementalDetailRID, callback) {
        var DelSupplementalDetail = {
            "SupplementalDetailRID": SupplementalDetailRID
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelSupplementalDetails',
            responseType: 'json',
            data: DelSupplementalDetail,
            cache: true
        }).success(callback);
    };

    // end supplemental


    //referral

    obj.insertReferral = function(ReferalObj, PxRID, ClinixRID, HospRID, callback) {
        var ReferralData = {

            "PxRID": PxRID,
            "ClinixRID": ClinixRID,
            "HospRID": HospRID,
            "roomNo": ReferalObj.roomNo,
            "impression": ReferalObj.impression,
            "ReferTo": ReferalObj.ReferTo,
            "ReferFor": ReferalObj.ReferFor,
            "ReferralNotes": ReferalObj.ReferralNotes
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertReferral',
            responseType: 'json',
            data: ReferralData,
            cache: true
        }).success(callback);
    };

    obj.getReferral = function(ClinixRID, callback) {
        $http.get(serviceBase + 'apiGetReferal&ClinixRID=' + ClinixRID).success(callback);
    };

    //end referral



    //common to everyone

    obj.CheckPxDsig = function(PIN) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiCheckPxDsig&PIN=' + PIN,
        });
    };

    //preop

    obj.getHipOtherPreOpOrders = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetHipOtherPreOpOrders&HospRID=' + HospRID).success(callback);
    };

    obj.updatePreOpOrders = function(wrid, HospRID, callback) {
        var PreOP = {
            "wrid": wrid,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdatePreOpOrders',
            data: PreOP,
            cache: true
        }).success(callback);
    };

    obj.DelOtherPreOpOrders = function(wrid, callback) {
        var DelOtherPreOpOrders = {
            "wrid": wrid
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelOtherPreOpOrders',
            responseType: 'json',
            data: DelOtherPreOpOrders,
            cache: true
        }).success(callback);
    };

    obj.getHipPreOpOrders = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetHipPreOpOrders&PxRID=' + PxRID).success(callback);
    };

    obj.getKneeOtherPreOpOrders = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetKneeOtherPreOpOrders&HospRID=' + HospRID).success(callback);
    };

    obj.updateKneePreOpOrders = function(wrid, HospRID, callback) {
        var PreOP = {
            "wrid": wrid,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateKneePreOpOrders',
            data: PreOP,
            cache: true
        }).success(callback);
    };

    obj.getKneePreOpOrders = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetKneePreOpOrders&PxRID=' + PxRID).success(callback);
    };

    obj.DelKneeOtherPreOpOrders = function(wrid, callback) {
        var DelOtherPreOpOrders = {
            "wrid": wrid
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelKneeOtherPreOpOrders',
            responseType: 'json',
            data: DelOtherPreOpOrders,
            cache: true
        }).success(callback);
    };

    //admitting

    obj.getOtherAdmittingOrders = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetOtherAdmittingOrders&HospRID=' + HospRID).success(callback);
    };

    obj.updateAdmittingOrders = function(AdmitRID, HospRID, callback) {
        var PreOP = {
            "AdmitRID": AdmitRID,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateAdmittingOrders',
            data: PreOP,
            cache: true
        }).success(callback);
    };

    obj.DelOtherAdmittingOrders = function(AdmitRID, callback) {
        var DelOtherAdmittingOrders = {
            "AdmitRID": AdmitRID
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelOtherAdmittingOrders',
            responseType: 'json',
            data: DelOtherAdmittingOrders,
            cache: true
        }).success(callback);
    };

    obj.getAdmittingOrders = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetAdmittingOrders&PxRID=' + PxRID).success(callback);
    };

    //refer to

    obj.getOtherReferralOrders = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetOtherReferralOrders&HospRID=' + HospRID).success(callback);
    };

    obj.updateReferralOrders = function(wrid, HospRID, callback) {
        var PreOP = {
            "wrid": wrid,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateReferralOrders',
            data: PreOP,
            cache: true
        }).success(callback);
    };

    obj.DelOtherReferralOrders = function(wrid, callback) {
        var DelOtherReferralOrders = {
            "wrid": wrid
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelOtherReferralOrders',
            responseType: 'json',
            data: DelOtherReferralOrders,
            cache: true
        }).success(callback);
    };

    obj.getReferralOrders = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetReferralOrders&PxRID=' + PxRID).success(callback);
    };

    //================
    // Schedule Surgery
    //================

    obj.getOtherSurgerySchedule = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetOtherSurgerySchedule&HospRID=' + HospRID).success(callback);
    };

    obj.updateSurgerySchedule = function(wrid, HospRID, callback) {
        var SurgerySchedule = {
            "wrid": wrid,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateSurgerySchedule',
            data: SurgerySchedule,
            cache: true
        }).success(callback);
    };

    obj.DelOtherSurgerySchedule = function(wrid, callback) {
        var DelOtherSurgerySchedule = {
            "wrid": wrid
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelOtherSurgerySchedule',
            responseType: 'json',
            data: DelOtherSurgerySchedule,
            cache: true
        }).success(callback);
    };

    obj.getSurgerySchedule = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetSurgerySchedule&PxRID=' + PxRID).success(callback);
    };

    //================
    // End Schedule Surgery
    //================


    //================
    // Narraitve
    //================

    obj.getOtherNarrative = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetOtherNarrative&HospRID=' + HospRID).success(callback);
    };

    obj.updateNarrative = function(ClinixRID, HospRID, callback) {
        var Narrative = {
            "ClinixRID": ClinixRID,
            "HospRID": HospRID
        }
        $http({
            method: 'POST',
            url: serviceBase + 'apiUpdateNarrative',
            data: Narrative,
            cache: true
        }).success(callback);
    };

    obj.DelOtherNarrative = function(ClinixRID, callback) {
        var DelOtherNarrative = {
            "ClinixRID": ClinixRID
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelOtherNarrative',
            responseType: 'json',
            data: DelOtherNarrative,
            cache: true
        }).success(callback);
    };

    obj.getNarrative = function(PxRID, callback) {
        $http.get(serviceBase + 'apiGetNarrative&PxRID=' + PxRID).success(callback);
    };

    //================
    // End Narraitve
    //================

    //attachments

    obj.getAttachments = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetAttachments&HospRID=' + HospRID).success(callback);
    };

    obj.DelAttachments = function(aRID, callback) {
        var DelAttachmentsData = {
            "aRID": aRID
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelAttachments',
            responseType: 'json',
            data: DelAttachmentsData,
            cache: true
        }).success(callback);
    };


    //vitals

    obj.getLkupVitals = function(callback) {
        $http.get(serviceBase + 'apiGetLkupVitals').success(callback);
    };

    obj.getVitals = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetVitals&HospRID=' + HospRID).success(callback);
    };

    obj.DelVitals = function(CVitRID, callback) {
        var DelVitalsData = {
            "CVitRID": CVitRID
        };
        $http({
            method: 'POST',
            url: serviceBase + 'apiDelVitals',
            responseType: 'json',
            data: DelVitalsData,
            cache: true
        }).success(callback);
    };

    //charges
    obj.getHospitalcharges = function(HospRID, callback) {
        $http.get(serviceBase + 'apiGetHospitalcharges&HospRID=' + HospRID).success(callback);
    }

    obj.getCLINIXchargesTariff = function(callback) {
        $http.get(serviceBase + 'apiClinixChargesTariff').success(callback);
    }

    obj.InsertHospitalcharges = function(newrecord, callback) {

        var ChargesData = {
            "HospRID": newrecord.HospRID,
            "PxRID": newrecord.PxRID,
            "FeeRID": newrecord.FeeRID,
            "Description": newrecord.Description,
            "Tariff": newrecord.Tariff,
            "ChargeAmount": newrecord.ChargeAmount,
            "Discount": newrecord.Discount,
            "NetAmount": newrecord.NetAmount,
            "SynchStatus": newrecord.SynchStatus
        };

        $http({
            method: 'POST',
            url: serviceBase + 'apiInsertHospitalcharges',
            responseType: 'json',
            data: ChargesData,
            cache: true
        }).success(callback);
        //&dob=' + dob + '&pxid=' + pxid + '&dt=' + dt + '&purp=' + purp + '&dok=' + dok + '&hospR=' + hospRID + '&hosp=' + hosp
    }

    obj.DelHospitalcharges = function(PEChargesRID, callback) {
        var Hospitalcharges = {
            "PEChargesRID": PEChargesRID
        };

        $http({
            method: 'POST',
            url: serviceBase + 'apiDelHospitalcharges',
            responseType: 'json',
            data: Hospitalcharges,
            cache: true
        }).success(callback);
        //&dob=' + dob + '&pxid=' + pxid + '&dt=' + dt + '&purp=' + purp + '&dok=' + dok + '&hospR=' + hospRID + '&hosp=' + hosp
    }


    obj.getReferTo = function(ClinixRID) {
        return $http.get(serviceBase + 'apiGetReferTo&id=' + ClinixRID);
    };

    obj.getDoctorList = function() {
        return $http.get(serviceBase + 'apiGetDoctorsList');
    }

    obj.getPxListReport = function(DokPxRID, fromDate, toDate) {
        return $http.get(serviceBase + 'apiGetPxListReport&DokPxRID=' + DokPxRID + '&fromDate=' + fromDate + '&toDate=' + toDate);
    }

    obj.getAllPxListReport = function(fromDate, toDate) {
        return $http.get(serviceBase + 'apiGetAllPxListReport&fromDate=' + fromDate + '&toDate=' + toDate);
    }


    obj.getdate = function(DateFrom, DateTo) {
        var classificationData = {

            "DateFrom": DateFrom,
            "DateTo": DateTo,
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiclassgetDate',
            responseType: 'json',
            data: classificationData,
            cache: true
        });
    };


    //nurses NOtes

    obj.getNursesNotes = function(ClinixRID) {
        return $http.get(serviceBase + 'apiGetNursesNotes&ClinixRID=' + ClinixRID);
    }



    //============
    //knee score
    //============

    obj.getKneeScore = function(ClinixRID, PxRID) {
        return $http.get(serviceBase + 'apiGetKneeScore&ClinixRID=' + ClinixRID + '&PxRID=' + PxRID);
    };

    obj.insertKneeScore = function(hipScoreRID) {
        var KneeScoreOBJ = {

            "ClinixRID": ClinixRID,
            "pain": kneeScoreOBJ.pain,
            "flexionContracture": kneeScoreOBJ.flexionContracture,
            "extensionLag": kneeScoreOBJ.extensionLag,
            "rangeOfFlexion": kneeScoreOBJ.rangeOfFlexion,
            "alignment": kneeScoreOBJ.alignment,
            "anteriorPosterior": kneeScoreOBJ.anteriorPosterior,
            "mediolateral": kneeScoreOBJ.mediolateral,
            "walking": kneeScoreOBJ.walking,
            "stairs": kneeScoreOBJ.stairs,
            "walkingAids": kneeScoreOBJ.walkingAids
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertKneeScore',
            responseType: 'json',
            data: KneeScoreOBJ,
            cache: true
        });
    };

    obj.removeKneeScore = function(kneeScoreRID) {
        var KneeScoreOBJ = {

            "kneeScoreRID": kneeScoreRID
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiRemoveKneeScore',
            responseType: 'json',
            data: KneeScoreOBJ,
            cache: true
        });
    };

    //end knee score


    //============
    //Hip score
    //============

    obj.getHipScore = function(ClinixRID, PxRID) {
        return $http.get(serviceBase + 'apiGetHipScore&ClinixRID=' + ClinixRID + '&PxRID=' + PxRID);
    };

    obj.insertHipScore = function(ClinixRID, hipScoreOBJ) {
        var HipScoreOBJ = {

            "ClinixRID": ClinixRID,
            "pain": hipScoreOBJ.pain,
            "limp": hipScoreOBJ.limp,
            "support": hipScoreOBJ.support,
            "distanceWalked": hipScoreOBJ.distanceWalked,
            "sitting": hipScoreOBJ.sitting,
            "publicTranspo": hipScoreOBJ.publicTranspo,
            "stairs": hipScoreOBJ.stairs,
            "shoesSocks": hipScoreOBJ.shoesSocks,
            "deformity": hipScoreOBJ.deformity,
            "rangeOfMotion": hipScoreOBJ.rangeOfMotion,
            "flexion": hipScoreOBJ.flexion,
            "abduction": hipScoreOBJ.abduction,
            "externalRotation": hipScoreOBJ.externalRotation,
            "adduction": hipScoreOBJ.adduction,
            "studyHip": hipScoreOBJ.studyHip,
            "intervalHip": hipScoreOBJ.intervalHip
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertHipScore',
            responseType: 'json',
            data: HipScoreOBJ,
            cache: true
        });
    };

    obj.removeHipScore = function(hipScoreRID) {
        var HipScoreOBJ = {

            "hipScoreRID": hipScoreRID
        }

        return $http({
            method: 'POST',
            url: serviceBase + 'apiRemoveHipScore',
            responseType: 'json',
            data: HipScoreOBJ,
            cache: true
        });
    };

    //end hip score


    //hxVists

    obj.getAllHxVisits = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllHxVisits&PxRID=' + PxRID,
        });
    };

    obj.getHxVisits = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHxVisits&ClinixRID=' + ClinixRID,
        });
    };

    obj.getHxSchedSurgery = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHxSchedSurgery&PxRID=' + PxRID,
        });
    };

    //pxChart
    obj.getAllPxChart = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllPxChart&PxRID=' + PxRID,
        });
    };




    //Hip X-Rays and Video
    //Hip X-Rays and Video
    //Hip X-Rays and Video

    obj.getPREOpHIPXray = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpHIPXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });

    };

    obj.getPREOpHIPImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPreOpHIPImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpHIPVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPreOpHIPVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpHipXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPostOpHIPXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPostOpHIPImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPostOpHIPImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPostOpHIPVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPostOpHIPVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };
    //End Hip X-Rays and Video
    //End Hip X-Rays and Video
    //End Hip X-Rays and Video




    //Knee X-Rays and Video
    //Knee X-Rays and Video
    //Knee X-Rays and Video
    obj.getPreOPKNEEXray = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpKNEEXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPreOPKNEEImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpKNEEImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPreOPKNEEVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpKNEEVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPostOPKNEEXray = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpKNEEXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPostOPKNEEImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpKNEEImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPostOPKNEEVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpKNEEVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END Knee X-Rays and Video
    //END Knee X-Rays and Video
    //END Knee X-Rays and Video




    //SPINE X-Rays and Video
    //SPINE X-Rays and Video
    //SPINE X-Rays and Video
    obj.getPREOpSPINXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSPINXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpSPINImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSPINImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpSPINVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSPINVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSPINXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSPINXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSPINImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSPINImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSPINVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSPINVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END SPINE X-Rays and Video
    //END SPINE X-Rays and Video
    //END SPINE X-Rays and Video





    //GENORTHO X-Rays and Video
    //GENORTHO X-Rays and Video
    //GENORTHO X-Rays and Video

    obj.getPREOpGENORTHOXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpGENORTHOXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpGENORTHOImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpGENORTHOImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpGENORTHOVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpGENORTHOVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpGENORTHOXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpGENORTHOXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpGENORTHOImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpGENORTHOImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpGENORTHOVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpGENORTHOVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END GENORTHO X-Rays and Video
    //END GENORTHO X-Rays and Video
    //END GENORTHO X-Rays and Video




    //SKELTRAUMA X-Rays and Video
    //SKELTRAUMA X-Rays and Video
    //SKELTRAUMA X-Rays and Video

    obj.getPREOpSKELTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSKELTraumaXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpSKELTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSKELTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpSKELTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpSKELTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSKELTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSKELTraumaXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSKELTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSKELTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpSKELTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpSKELTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END SKELTRAUMA X-Rays and Video
    //END SKELTRAUMA X-Rays and Video
    //END SKELTRAUMA X-Rays and Video



    //PelvicHipTRAUMA X-Rays and Video
    //PelvicHipTRAUMA X-Rays and Video
    //PelvicHipTRAUMA X-Rays and Video

    obj.getPREOpPelvicHipTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpPelvicHipTraumaXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpPelvicHipTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpPelvicHipTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpPelvicHipTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpPelvicHipTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpPelvicHipTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpPelvicHipTraumaXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpPelvicHipTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpPelvicHipTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpPelvicHipTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpPelvicHipTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END PelvicHipTRAUMA X-Rays and Video
    //END PelvicHipTRAUMA X-Rays and Video
    //END PelvicHipTRAUMA X-Rays and Video


    //WristHandTRAUMA X-Rays and Video
    //WristHandTRAUMA X-Rays and Video
    //WristHandTRAUMA X-Rays and Video

    obj.getPREOpWristHandTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpWristHandTraumaXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpWristHandTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpWristHandTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpWristHandTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpWristHandTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpWristHandTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpWristHandTraumaXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpWristHandTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpWristHandTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpWristHandTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpWristHandTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END WristHandTRAUMA X-Rays and Video
    //END WristHandTRAUMA X-Rays and Video
    //END WristHandTRAUMA X-Rays and Video


    //HipjointTRAUMA X-Rays and Video
    //HipJointTRAUMA X-Rays and Video
    //HipJointTRAUMA X-Rays and Video

    obj.getPREOpHipJointTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpHipJointTraumaXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpHipJointTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpHipJointTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpHipJointTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpHipJointTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpHipJointTraumaXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpHipJointTraumaXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpHipJointTraumaImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpHipJointTraumaImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpHipJointTraumaVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpHipJointTraumaVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END HipJointTRAUMA X-Rays and Video
    //END HipJointTRAUMA X-Rays and Video
    //END HipJointTRAUMA X-Rays and Video


    //Infection X-Rays and Video
    //Infection X-Rays and Video
    //Infection X-Rays and Video

    obj.getPREOpInfectionXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpInfectionXray&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpInfectionImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpInfectionImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPREOpInfectionVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREOpInfectionVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpInfectionXRays = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpInfectionXRays&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpInfectionImg = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpInfectionImg&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    obj.getPOSTOpInfectionVid = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTOpInfectionVid&id=' + PxRID,
            responseType: 'json',
            cache: true
        });
    };

    //END Infection X-Rays and Video
    //END Infection X-Rays and Video
    //END Infection X-Rays and Video



    //Trauma modules

    //**********UPPER EXTREMITIES***********

    //Trauma Clavicle
    obj.getClavicleTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetClavicleTrauma&id=' + PxRID);
    };

    obj.getXrayClavicleTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetXrayClavicleTrauma&id=' + PxRID);
    };

    obj.getPREClavicleTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREClavicleTraumaXRays&id=' + PxRID);
    };

    obj.getPREClavicleTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREClavicleTraumaImg&id=' + PxRID);
    };

    obj.getPREClavicleTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREClavicleTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTClavicleTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTClavicleTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTClavicleTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTClavicleTraumaImg&id=' + PxRID);
    };

    obj.getPOSTClavicleTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTClavicleTraumaVideo&id=' + PxRID);
    };

    //End-Trauma Clavicle


    //Trauma Scapula Acetabulum
    obj.getScapulaTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetScapulaTrauma&id=' + PxRID);
    };

    obj.getXrayScapulaTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetXrayScapulaTrauma&id=' + PxRID);
    };

    obj.getPREScapulaTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREScapulaTraumaXRays&id=' + PxRID);
    };

    obj.getPREScapulaTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREScapulaTraumaImg&id=' + PxRID);
    };

    obj.getPREScapulaTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREScapulaTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTScapulaTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTScapulaTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTScapulaTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTScapulaTraumaImg&id=' + PxRID);
    };

    obj.getPOSTScapulaTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTScapulaTraumaVideo&id=' + PxRID);
    };

    //End-Trauma Scapula Acetabulum

    //Trauma Shoulder
    obj.getShoulderTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetshoulderTrauma&id=' + PxRID);
    }

    obj.getXrayShoulderTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apigetXrayShoulderTrauma&id=' + PxRID);
    }

    obj.getPREShoulderTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREShoulderTraumaXRays&id=' + PxRID);
    };

    obj.getPREShoulderTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREShoulderTraumaImg&id=' + PxRID);
    };

    obj.getPREShoulderTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREShoulderTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTShoulderTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTShoulderTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTShoulderTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTShoulderTraumaImg&id=' + PxRID);
    };

    obj.getPOSTShoulderTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTShoulderTraumaVideo&id=' + PxRID);
    };
    //End-Trauma Shoulder


    //Trauma Humeral Shaft
    obj.getHumeralshaftInspection = function(PxRID) {
        return $http.get(serviceBase + 'apigetHumeralshaftInspection&id=' + PxRID);
    };

    obj.getHumeralshaftXray = function(PxRID) {
        return $http.get(serviceBase + 'apigetHumeralshaftXray&id=' + PxRID);
    };

    obj.getPREHumeralShaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHumeralShaftTraumaXRays&id=' + PxRID);
    };

    obj.getPREHumeralShaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHumeralShaftTraumaImg&id=' + PxRID);
    };

    obj.getPREHumeralShaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHumeralShaftTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTHumeralShaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHumeralShaftTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTHumeralShaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHumeralShaftTraumaImg&id=' + PxRID);
    };

    obj.getPOSTHumeralShaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHumeralShaftTraumaVideo&id=' + PxRID);
    };
    //END - Trauma Humeral Shaft

    //Trauma Distal Humerus
    obj.getDestalhumerusTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apiDestalhumerusTrauma&id=' + PxRID);
    };

    obj.getDistalhumerusXray = function(PxRID) {
        return $http.get(serviceBase + 'apiDestalhumerusTraumaXray&id=' + PxRID);
    };

    obj.getPREdistalhumerusTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREdistalhumerusTraumaXRays&id=' + PxRID);
    };

    obj.getPREdistalhumerusTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREdistalhumerusTraumaImg&id=' + PxRID);
    };

    obj.getPREdistalhumerusTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREdistalhumerusTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTdistalhumerusTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTdistalhumerusTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTdistalhumerusTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTdistalhumerusTraumaImg&id=' + PxRID);
    };

    obj.getPOSTdistalhumerusTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTdistalhumerusTraumaVideo&id=' + PxRID);
    };
    //End-Trauma Distal Humerus

    //Trauma Elbow
    obj.getElbowTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apiElbowTrumaApp&id=' + PxRID);
    };

    obj.getElbowTraumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiElbowTrumaXrayFindings&id=' + PxRID);
    };

    obj.getPREElbowTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREElbowTraumaXRays&id=' + PxRID);
    };

    obj.getPREElbowTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREElbowTraumaImg&id=' + PxRID);
    };

    obj.getPREElbowTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREElbowTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTElbowTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTElbowTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTElbowTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTElbowTraumaImg&id=' + PxRID);
    };

    obj.getPOSTElbowTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTElbowTraumaVideo&id=' + PxRID);
    };
    //End-Trauma Elbow

    //Trauma ForeArm
    obj.getForearmTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apiForearmTrumaApp&id=' + PxRID);
    };

    obj.getForearmTraumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiForearmTrumaXrayFindings&id=' + PxRID);
    };

    obj.getPREForearmTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREForearmTraumaXRays&id=' + PxRID);
    };

    obj.getPREForearmTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREForearmTraumaImg&id=' + PxRID);
    };

    obj.getPREForearmTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREForearmTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTForearmTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTForearmTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTForearmTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTForearmTraumaImg&id=' + PxRID);
    };

    obj.getPOSTForearmTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTForearmTraumaVideo&id=' + PxRID);
    };
    //End-Trauma ForeArm

    //Trauma Wrist Hand
    obj.getWristhandTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apiWristhandTrumaApp&id=' + PxRID);
    };

    obj.getWristhandTraumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiWristhandTrumaXrayFindings&id=' + PxRID);
    };

    obj.getPREWristjointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREWristjointTraumaXRays&id=' + PxRID);
    };

    obj.getPREWristjointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREWristjointTraumaImg&id=' + PxRID);
    };

    obj.getPREWristjointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREWristjointTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTWristjointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTWristjointTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTWristjointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTWristjointTraumaImg&id=' + PxRID);
    };

    obj.getPOSTWristjointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTWristjointTraumaVideo&id=' + PxRID);
    };

    //End-Trauma Wrist Hand

    //Trauma Hand
    obj.getHandTrauma = function(PxRID) {
        return $http.get(serviceBase + 'apiHandTrumaApp&id=' + PxRID);
    };

    obj.getHandTraumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiHandTrumaXrayFindings&id=' + PxRID);
    };

    obj.getPREHandTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHandTraumaXRays&id=' + PxRID);
    };

    obj.getPREHandTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHandTraumaImg&id=' + PxRID);
    };

    obj.getPREHandTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHandTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTHandTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHandTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTHandTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHandTraumaImg&id=' + PxRID);
    };

    obj.getPOSTHandTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHandTraumaVideo&id=' + PxRID);
    };
    //End-Trauma Hand

    //**********END - UPPER EXTREMITIES***********


    //**********LOWER EXTREMITIES***********

    //Trauma Pelvic Acetabulum
    obj.getPelvicHipTrumaApp = function(PxRID) {
        return $http.get(serviceBase + 'apiPelvicHipTrumaApp&id=' + PxRID);
    };

    obj.getPelvicHipTrumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiPelvicHipTrumaXray&id=' + PxRID);
    };

    obj.getPREPelvicacetabulumTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREPelvicacetabulumTraumaXRays&id=' + PxRID);
    };

    obj.getPREPelvicacetabulumTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREPelvicacetabulumTraumaImg&id=' + PxRID);
    };

    obj.getPREPelvicacetabulumTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREPelvicacetabulumTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTPelvicacetabulumTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTPelvicacetabulumTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTPelvicacetabulumTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTPelvicacetabulumTraumaImg&id=' + PxRID);
    };

    obj.getPOSTPelvicacetabulumTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTPelvicacetabulumTraumaVideo&id=' + PxRID);
    };
    //End-Trauma Pelvic Acetabulum


    //Trauma Hip Joint
    obj.getHipJointApp = function(PxRID) {
        return $http.get(serviceBase + 'apiHipJointTrumaApp&id=' + PxRID);
    };

    obj.getHipJointXray = function(PxRID) {
        return $http.get(serviceBase + 'apiHipJointTrumaXray&id=' + PxRID);
    };

    obj.getPREHipjointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHipjointTraumaXRays&id=' + PxRID);
    };

    obj.getPREHipjointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHipjointTraumaImg&id=' + PxRID);
    };

    obj.getPREHipjointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREHipjointTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTHipjointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHipjointTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTHipjointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHipjointTraumaImg&id=' + PxRID);
    };

    obj.getPOSTHipjointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTHipjointTraumaVideo&id=' + PxRID);
    };
    //END - Trauma Hip Joint


    //Trauma Femoral Shaft
    obj.getFemoralShaftApp = function(PxRID) {
        return $http.get(serviceBase + 'apiFemorShaftTrumaApp&id=' + PxRID);
    };

    obj.getFemoralShaftXray = function(PxRID) {
        return $http.get(serviceBase + 'apiFemoralShaftTrumaXray&id=' + PxRID);
    };

    obj.getPREFemoralshaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFemoralshaftTraumaXRays&id=' + PxRID);
    };

    obj.getPREFemoralshaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFemoralshaftTraumaImg&id=' + PxRID);
    };

    obj.getPREFemoralshaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFemoralshaftTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTFemoralshaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFemoralshaftTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTFemoralshaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFemoralshaftTraumaImg&id=' + PxRID);
    };

    obj.getPOSTFemoralshaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFemoralshaftTraumaVideo&id=' + PxRID);
    };
    //END - Trauma Femoral Shaft

    //Trauma Knee Joint
    obj.getKneeJointApp = function(PxRID) {
        return $http.get(serviceBase + 'apiKneeJointTrumaApp&id=' + PxRID);
    };

    obj.getKneeJointXray = function(PxRID) {
        return $http.get(serviceBase + 'apiKneeJointTrumaXray&id=' + PxRID);
    };

    obj.getPREKneejointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREKneejointTraumaXRays&id=' + PxRID);
    };

    obj.getPREKneejointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREKneejointTraumaImg&id=' + PxRID);
    };

    obj.getPREKneejointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREKneejointTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTKneejointTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTKneejointTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTKneejointTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTKneejointTraumaImg&id=' + PxRID);
    };

    obj.getPOSTKneejointTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTKneejointTraumaVideo&id=' + PxRID);
    };

    //END - Trauma Hip Joint

    //Trauma Tibia Shaft 
    obj.getTibiaShaftApp = function(PxRID) {
        return $http.get(serviceBase + 'apiaTibiaShaftTraumaApp&id=' + PxRID);
    };

    obj.getTibiaShaftXray = function(PxRID) {
        return $http.get(serviceBase + 'apiTibiaShaftTrumaXray&id=' + PxRID);
    };


    obj.getPRETibiaShaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPRETibiashaftTraumaXRays&id=' + PxRID);
    };

    obj.getPRETibiaShaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPRETibiashaftTraumaImg&id=' + PxRID);
    };

    obj.getPRETibiaShaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPRETibiashaftTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTTibiaShaftTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTTibiashaftTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTTibiaShaftTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTTibiashaftTraumaImg&id=' + PxRID);
    };

    obj.getPOSTTibiaShaftTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTTibiashaftTraumaVideo&id=' + PxRID);
    };

    //END - Trauma Tibia Shaft 

    //Trauma Foot and Ankle
    obj.getFootAnkleTrumaApp = function(PxRID) {
        return $http.get(serviceBase + 'apiaFootAnkleTraumaApp&id=' + PxRID);
    };

    obj.getFootAnkleTrumaXray = function(PxRID) {
        return $http.get(serviceBase + 'apiFootAnkleTrumaXray&id=' + PxRID);
    };

    obj.getPREFootAnkleTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFootAnkleTraumaXRays&id=' + PxRID);
    };

    obj.getPREFootAnkleTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFootAnkleTraumaImg&id=' + PxRID);
    };

    obj.getPREFootAnkleTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPREFootAnkleTraumaVideo&id=' + PxRID);
    };

    obj.getPOSTFootAnkleTraumaXRays = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFootAnkleTraumaXRays&id=' + PxRID);
    };

    obj.getPOSTFootAnkleTraumaImg = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFootAnkleTraumaImg&id=' + PxRID);
    };

    obj.getPOSTFootAnkleTraumaVideo = function(PxRID) {
        return $http.get(serviceBase + 'apiPOSTFootAnkleTraumaVideo&id=' + PxRID);
    };
    //END - Trauma Foot and Ankle 


    //Trauma Pre Op, Operative and Post Operative
    obj.getTraumaPreForm = function(ClinixRID) {
        return $http.get(serviceBase + 'apiTraumaPreForm&id=' + ClinixRID);
    };

    obj.getTraumaOpSchedForSurgery = function(PxRID) {
        return $http.get(serviceBase + 'apiGetDiagsSchedForSurgery&id=' + PxRID);
    };

    obj.getTraumaOpImplant = function(PxRID) {
        return $http.get(serviceBase + 'apiTraumaOpImplant&id=' + PxRID);
    };

    obj.getTraumaOpSurgicalTech = function(PxRID) {
        return $http.get(serviceBase + 'apiTraumaOpSurgicalTech&id=' + PxRID);
    };

    obj.getTraumaOpSurgical = function(PxRID) {
        return $http.get(serviceBase + 'apiTraumaOpSurgical&id=' + PxRID);
    };

    obj.getTraumaPostForm = function(ClinixRID) {
        return $http.get(serviceBase + 'apiTraumaPostForm&id=' + ClinixRID);
    };

    obj.insertTraumaPreForm = function(ClinixRID, PxRID, traumapreop) {

        var InsertDiagnosis = {
            "clinix": ClinixRID,
            "pxrid": PxRID,
            "Pre01": traumapreop.Pre01,
            "Pre02": traumapreop.Pre02,
            "Pre03": traumapreop.Pre03,
            "Pre04": traumapreop.Pre04,
            "Pre05": traumapreop.Pre05,
            "Pre06": traumapreop.Pre06,
            "Pre07": traumapreop.Pre07,
            "Pre08": traumapreop.Pre08,
            "Pre09CBC": traumapreop.Pre09CBC,
            "Pre09ECG": traumapreop.Pre09ECG,
            "Pre09BloodType": traumapreop.Pre09BloodType,
            "Pre09ChestXray": traumapreop.Pre09ChestXray,
            "Pre09Others": traumapreop.Pre09Others,
            "Pre10": traumapreop.Pre10,
            "Pre010Secure": traumapreop.Pre010Secure,
            "Pre11": traumapreop.Pre11,
            "Pre12": traumapreop.Pre12,
            "Pre13": traumapreop.Pre13,
            "PreOthers": traumapreop.PreOthers
        };
        return $http({
            method: 'POST',
            url: serviceBase + 'apiTraumaInsPreOp',
            responseType: 'json',
            data: InsertDiagnosis,
            cache: true
        });
    }


    obj.insertTraumaImplant = function(ClinixRID, PxRID, Implant) {

        newrecord = {
            "clinix": ClinixRID,
            "pxrid": PxRID,
            "ImplantNailType": Implant.ImplantNailType,
            "ImplantNailSize": Implant.ImplantNailSize,
            "ImplantPlateType": Implant.ImplantPlateType,
            "ImplantPlateSize": Implant.ImplantPlateSize,
            "ImplantScrewsType": Implant.ImplantScrewsType,
            "ImplantScrewsSize": Implant.ImplantScrewsSize,
            "ImplantPinType": Implant.ImplantPinType,
            "ImplantPinSize": Implant.ImplantPinSize,
            "Others": Implant.Others
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpOpImplant',
            responseType: 'json',
            data: newrecord,
            cache: true
        });
    }


    obj.insertTraumaUpdate = function(ClinixRID, PxRID, SchedSurgery) {

        newrecord = {
            "clinix": ClinixRID,
            "pxrid": PxRID,
            "SurgeryType": SchedSurgery.SurgeryType,
            "SurgeryDate": SchedSurgery.SurgeryDate,
            "SurgeryTime": SchedSurgery.SurgeryTime,
            "Surgeon": SchedSurgery.Surgeon,
            "Assistant": SchedSurgery.Assistant,
            "Cardio": SchedSurgery.Cardio,
            "Anesthesio": SchedSurgery.Anesthesio,
            "AnesthesiaType": SchedSurgery.AnesthesiaType,
            "AnestTypeLocal": SchedSurgery.AnestTypeLocal,
            "AnestTypeSpinal": SchedSurgery.AnestTypeSpinal,
            "AnestTypeEpi": SchedSurgery.AnestTypeEpi,
            "AnestTypeNerveBlock": SchedSurgery.AnestTypeNerveBlock,
            "AnestTypeGen": SchedSurgery.AnestTypeGen,
            "AnesthTypeOthers": SchedSurgery.AnesthTypeOthers,
            "Hospital": SchedSurgery.Hospital,
            "OrNurse": SchedSurgery.OrNurse,
            "Others": SchedSurgery.Others
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpOpSurgSched',
            responseType: 'json',
            data: newrecord,
            cache: true
        });
    }

    obj.insertTraumaSurgical = function(ClinixRID, PxRID, Surgical) {

        newrecord = {
            "clinix": ClinixRID,
            "pxrid": PxRID,
            "BloodLoss": Surgical.BloodLoss,
            "Closure": Surgical.Closure,
            "OperativeCourse": Surgical.OperativeCourse,
            "Findings": Surgical.Findings,
            "Diagnosis": Surgical.Diagnosis,
            "OpDuration": Surgical.OpDuration,
            "XRays": Surgical.XRays,
            "Others": Surgical.Others
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpOpSurgical',
            responseType: 'json',
            data: newrecord,
            cache: true
        });
    }

    obj.insertTraumaSurgicalTech = function(ClinixRID, PxRID, SurgicalTech) {

        newrecord = {
            "clinix": ClinixRID,
            "pxrid": PxRID,
            "Tourniquet": SurgicalTech.Tourniquet,
            "ReleaseTech": SurgicalTech.ReleaseTech,
            "SurgicalApproach": SurgicalTech.SurgicalApproach,
            "SurgicalAppOthers": SurgicalTech.SurgicalAppOthers,
            "BloodLoss": SurgicalTech.BloodLoss,
            "Closure": SurgicalTech.Closure,
            "OperativeCourse": SurgicalTech.OperativeCourse,
            "Findings": SurgicalTech.Findings,
            "Diagnosis": SurgicalTech.Diagnosis,
            "OpDuration": SurgicalTech.OpDuration,
            "XRays": SurgicalTech.XRays,
            "Others": SurgicalTech.Others
        }
        return $http({
            method: 'POST',
            url: serviceBase + 'apiUpOpSurgicalTech',
            responseType: 'json',
            data: newrecord,
            cache: true
        });
    }

    obj.insertTraumaPostForm = function(ClinixRID, PxRID, traumapostop) {


            var InsertPostOp = {
                "clinix": ClinixRID,
                "pxrid": PxRID,
                "Post01": traumapostop.Post01,
                "Post02SurgPro": traumapostop.Post02SurgPro,
                "Post03": traumapostop.Post03,
                "Post04": traumapostop.Post04,
                "Post05": traumapostop.Post05,
                "Post06": traumapostop.Post06,
                "Post07": traumapostop.Post07,
                "Post08": traumapostop.Post08,
                "Post09": traumapostop.Post09,
                "Post10": traumapostop.Post10,
                "PostOthers": traumapostop.PostOthers
            };
            return $http({
                method: 'POST',
                url: serviceBase + 'apiTraumaInsPostOp',
                responseType: 'json',
                data: InsertPostOp,
                cache: true
            });
        }
        //END - Trauma Pre Op, Operative and Post Operative 

    //**********END - LOWER EXTREMITIES***********

    // End - Trauma Module

    //===============
    // Structured Discharge
    //===============


    obj.getStrucDiscSumm = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_SchedSurgery = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_SchedSurgery&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_Hospitalize = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_Hospitalize&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_StructuredLABS = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_StructuredLABS&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_Disposition = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_Disposition&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_Management = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_Management&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_Medication = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_Medication&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getStrucDiscSumm_FollowUp = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetStrucDiscSumm_FollowUp&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    //===============
    // end Structured Discharge
    //===============


    // GET PREOP HIPS
    // GET PREOP HIPS
    // GET PREOP HIPS

    obj.getPREopHIP_prefrom = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopHIP_prefrom&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopHIP_contact = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopHIP_contact&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopHIP_antibio = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopHIP_antibio&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopHIP_repeatBilateral = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopHIP_repeatBilateral&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };



    // GET OPERATIVE HIPS

    obj.get_OP_HIP_1 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_1&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_HIP_2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_2&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_HIP_3 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_3&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_HIP_4 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_4&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_HIP_5 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_5&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_HIP_6 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_HIP_6&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };







    // GET POST_OP HIPS

    obj.getPOSTopHIP_prefrom = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTopHIP_prefrom&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    // KNEE Pre Opt

    obj.getPREopKNEE_prefrom = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopKNEE_prefrom&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopKNEE_contact = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopKNEE_contact&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopKNEE_antiBio = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopKNEE_antiBio&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getPREopKNEE_repestBil = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPREopKNEE_repeatBilateral&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };


    // KNEE OPEERATIVE
    // KNEE OPEERATIVE

    obj.get_OP_KNEE_1 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_KNEE_1&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_KNEE_2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_KNEE_2&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_KNEE_3 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_KNEE_3&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_KNEE_4 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_KNEE_4&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.get_OP_KNEE_5 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGet_OP_KNEE_5&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };



    // KNEE POST Opt

    obj.getPOSTopKNEE_prefrom = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetPOSTopKNEE_prefrom&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };





    //OR PRE Op

    obj.getORPreOperative = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORpreOp&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORPreOperativeMedHis = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreOpMedHis&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };
    obj.getORPreSocHabits = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreSocHabits&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORPreSocHabits2 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreSocHabits2&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORPreSocHabits3 = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreSocHabits3&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    // OR PreOp Nurse Dsig
    obj.getORPreOp_PINORnurse = function(clnx_rid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreOpPINORnurse?clinixrid=' + clnx_rid,
            responseType: 'json',
            cache: false
        });
    };
    // OR PreOp Surgeon Dsig
    obj.getORPreOp_PINSurgeon = function(clnx_rid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPreOpPINSurgeon?clinixrid=' + clnx_rid,
            responseType: 'json',
            cache: false
        });
    };


    //INTRA OP
    //INTRA OP
    //INTRA OP

    obj.getORIntraOp = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORIntraOp&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORSkinPrep = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORSkinPrep&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORBladder = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORBladder&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORPotenProb = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPotenProb&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    //INTRA OP - END




    //POST OP

    obj.getORPostPass = function(clnxrid) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetORPostPass&id=' + clnxrid,
            responseType: 'json',
            cache: false
        });
    };

    obj.getORpostOpRec = function(clnxrid) {
        return $http.get(serviceBase + 'apiGetPostORpostOpRec&id=' + clnxrid);
    };

    //POST OP END


    //============
    //Hx Medication
    //============
    obj.getHxMedications = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetHxMedications&PxRID=' + PxRID,
        });
    };

    obj.getAllHxMedications = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllHxMedications&PxRID=' + PxRID,
        });
    };

    obj.getAllHxMedicationsDetails = function(PrescRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllHxMedicationsDetails&PrescRID=' + PrescRID,
        });
    };

    //============
    //ENd Hx Medication
    //============

    //===========================
    // lab request
    //===========================

    obj.insertLabRequest = function(labRequest) {
        var LabRequestData = {
            "HospRID": labRequest.HospRID,
            "PxRID": labRequest.PxRID,
            "LabTypes": labRequest.LabTypes,
            "ClinixRID": labRequest.ClinixRID,
            "SignedPxRID": labRequest.SignedPxRID,
            "LabRID": labRequest.LabRID,
            "DateRequested": labRequest.DateRequested,
            "EnteredBy": labRequest.EnteredBy,
            "ReferredBy": labRequest.ReferredBy,

        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertLabRequest',
            responseType: 'json',
            data: LabRequestData,
            cache: true
        });
    }

    obj.getLabRequest = function(ClinixRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetLabRequest&ClinixRID=' + ClinixRID,
        });
    }

    obj.getLabRequestDetails = function(LabRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetLabRequestDetails&LabRID=' + LabRID,
        });
    }

    obj.getAllPxLabRequest = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllPxLabRequest&id=' + PxRID,
        });
    }


    obj.insertLabRequestDetails = function(labRequestDetails) {

        var LabRequestDetailsData = {
            "PxRID": labRequestDetails.PxRID,
            "LabRID": labRequestDetails.LabRID,
            "LRID": labRequestDetails.LRID,

        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertLabRequestDetails',
            responseType: 'json',
            data: LabRequestDetailsData,
            cache: true
        });
    }

    obj.signLabRequest = function(labRequest) {

            var LabRequestData = {
                "PxRID": labRequest.PxRID,
                "LabRID": labRequest.LabRID,
                "SignedPxRID": labRequest.SignedPxRID,

            };

            return $http({
                method: 'POST',
                url: serviceBase + 'apiSignLabRequest',
                responseType: 'json',
                data: LabRequestData,
                cache: true
            });
        }
        //===========================
        // end lab request
        //===========================

    //===========================
    // lab result
    //===========================

    obj.getAllPxLabResult = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllPxLabResult&PxRID=' + PxRID,
        });
    }

    obj.getLabResultDetails = function(LabRexRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apigetLabResultDetails&LabRexRID=' + LabRexRID,
        });
    }

    //===========================
    // end lab result
    //===========================

    //===========================
    // xray request
    //===========================

    obj.getAllPxXrayRequest = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllPxXrayRequest&PxRID=' + PxRID,
        });
    }


    obj.insertXrayRequest = function(HospRID, ClinixRID, PxRID, userPxRID, xrayRequest) {
        var xrayRequestData = {
            "HospRID": HospRID,
            "PxRID": PxRID,
            "ClinixRID": ClinixRID,
            "userPxRID": userPxRID,
            "LabTypes": xrayRequest.LabTypes,
            "LCatRID": xrayRequest.LCatRID,
            "StudyToBeDone": xrayRequest.StudyToBeDone,
            "ChiefComplaint": xrayRequest.ChiefComplaint,
            "History": xrayRequest.History,
            "LabRID": xrayRequest.LabRID,
            "DateRequested": xrayRequest.DateRequested,
            "EnteredBy": userPxRID,
            "ReferredBy": xrayRequest.ReferredBy,

        };

        return $http({
            method: 'POST',
            url: serviceBase + 'apiInsertXrayRequest',
            responseType: 'json',
            data: xrayRequestData,
            cache: true
        });
    }

    obj.signXrayRequest = function(LabRID, SignedPxRID) {

            var XrayRequestData = {
                "LabRID": LabRID,
                "SignedPxRID": SignedPxRID,

            };

            return $http({
                method: 'POST',
                url: serviceBase + 'apiSignXrayRequest',
                responseType: 'json',
                data: XrayRequestData,
                cache: true
            });
        }
        //===========================
        // end xray request
        //===========================


    //===========================
    // xray result
    //===========================

    obj.getAllPxXrayResult = function(PxRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetAllPxXrayResult&PxRID=' + PxRID,
        });
    }

    obj.getXrayResult = function(LabRID) {
        return $http({
            method: 'GET',
            url: serviceBase + 'apiGetXrayResult&LabRID=' + LabRID,
        });
    }


    //===========================
    // xray result
    //===========================


    //med abstract

    obj.getMedAbstract = function(PxRID) {
        return $http.get(serviceBase + 'apiGetMedAbstract&PxRID=' + PxRID);
    };


    //med abstract

    obj.getMedCertificate = function(PxRID) {
        return $http.get(serviceBase + 'apiGetMedCertificate&PxRID=' + PxRID);
    };



    // floor
    return obj;
}]);