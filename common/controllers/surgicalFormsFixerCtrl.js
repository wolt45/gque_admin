gmmrApp.controller('surgicalFormsFixerCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, ngToast, $filter, $timeout, dbServices){

  $scope.HospRID =  localStorage.getItem("gmmr3HospRID");
  $scope.userPxRID = localStorage.getItem("gmmr3userPxRID");

  $scope.surgerySchedulePanel = false;

  $scope.$on('LOAD',function(){$scope.loading=true});
  $scope.$on('UNLOAD',function(){$scope.loading=false});

  $scope.$emit('LOAD');

  var startDefaultdate = 'Wed Jan 01 2000 00:00:00 GMT+0800 (Philippine Standard Time)';
  var endDefaultdate = new Date;
  var addthisNum = 1;

  $scope.tempfromDate = $filter('date')(new Date(startDefaultdate), 'yyyy-MM-dd');
  $scope.temptoDate = $filter('date')(new Date(endDefaultdate), 'yyyy-MM-dd');

  $scope.Reload = function() {
    location.reload();
  };


  // All list
  $scope.getOperatingRoomScheduleReportAllList = function (fromDate, toDate) {
    $scope.tempfromDate = $filter('date')(new Date(fromDate), 'yyyy-MM-dd');
    $scope.temptoDate = $filter('date')(new Date(toDate), 'yyyy-MM-dd');
    $scope.OperatingRoomScheduleListAllObj = [];
    dbServices.getOperatingRoomScheduleReportAllList($scope.tempfromDate, $scope.temptoDate)
    .then(function success(response) {
      
      // console.log(response);

      for (var i = 0; i < response.data.length; i++) {
        var pxName = response.data[i].pxName;
        var diagnosis = response.data[i].diagnosis;
        var SurgeryType = response.data[i].SurgeryType;
        var Surgeon = response.data[i].Surgeon;
        var SurgeryDate = response.data[i].SurgeryDate;
        var wrid = response.data[i].wrid;
        var AnesthTypeOthers = response.data[i].AnesthTypeOthers;
        var AnesthesiaType = response.data[i].AnesthesiaType;
        var Anesthesio = response.data[i].Anesthesio;
        var Assistant = response.data[i].Assistant;
        var Cardio = response.data[i].Cardio;
        var ClinixRID = response.data[i].ClinixRID;
        var HospRID = response.data[i].HospRID;
        var PxRID = response.data[i].PxRID;
        var orCaseRID = response.data[i].orCaseRID;
        var OrNurse = response.data[i].OrNurse;
        var Hospital = response.data[i].Hospital;
        var OrNurse = response.data[i].OrNurse;
        var Others = response.data[i].Others;
        var scrubNurse = response.data[i].scrubNurse;
        var circulatingNurse = response.data[i].circulatingNurse;
        var signedPxRID = response.data[i].signedPxRID;
        var singedByName = response.data[i].singedByName;
        var singedBySign = response.data[i].singedBySign;
        var orNursePxRID = response.data[i].orNursePxRID;
        var orNurseName = response.data[i].orNurseName;
        var orNurseSign = response.data[i].orNurseSign;
        if (SurgeryDate != '0000-00-00') {
          SurgeryDate = moment(SurgeryDate).format();
        }
        var SurgeryTime = response.data[i].SurgeryTime;
        if (SurgeryDate != '0000-00-00' && SurgeryTime != '00:00:00') {
          var tempSurgeryDate = $filter('date')(new Date(SurgeryDate), 'yyyy-MM-dd');
          SurgeryTime = tempSurgeryDate + ' ' + SurgeryTime;
          SurgeryTime = moment(SurgeryTime).format();
        }

        var SurgeryTimeEnd = response.data[i].SurgeryTimeEnd;
        if (SurgeryDate != '0000-00-00' && SurgeryTimeEnd != '00:00:00') {
          var tempSurgeryDate = $filter('date')(new Date(SurgeryDate), 'yyyy-MM-dd');
          SurgeryTimeEnd = tempSurgeryDate + ' ' + SurgeryTimeEnd;
          SurgeryTimeEnd = moment(SurgeryTimeEnd).format();
        }
        var surgeryStatus = response.data[i].surgeryStatus;
        var surgeryStatusDesc = "";
        if (surgeryStatus == 1) {
          surgeryStatusDesc = "STAT";
        }else if (surgeryStatus == 3) {
          surgeryStatusDesc = "RESERVED";
        }else if (surgeryStatus == 2) {
          surgeryStatusDesc = "ELECTIVE";
        }else if (surgeryStatus == 5) {
          surgeryStatusDesc = "RESCHEDULE";
        }else if (surgeryStatus == 6) {
          surgeryStatusDesc = "CANCELED";
        }else if (surgeryStatus == 9) {
          surgeryStatusDesc = "COMPLETED";
        }
        var operatingRoom = response.data[i].operatingRoom;
        var foto = response.data[i].foto;

        newRecord={
          pxName : pxName
          , SurgeryType : SurgeryType
          , diagnosis : diagnosis
          , Surgeon : Surgeon
          , SurgeryDate : SurgeryDate
          , SurgeryTime : SurgeryTime
          , SurgeryTimeEnd : SurgeryTimeEnd
          , operatingRoom : operatingRoom
          , surgeryStatus : surgeryStatus
          , surgeryStatusDesc : surgeryStatusDesc
          , wrid : wrid
          , AnesthTypeOthers : AnesthTypeOthers
          , AnesthesiaType : AnesthesiaType
          , Anesthesio : Anesthesio
          , Assistant : Assistant
          , Cardio : Cardio
          , ClinixRID : ClinixRID
          , HospRID : HospRID
          , PxRID : PxRID
          , orCaseRID : orCaseRID
          , OrNurse : OrNurse
          , Hospital : Hospital
          , OrNurse : OrNurse
          , Others : Others
          , scrubNurse : scrubNurse
          , circulatingNurse : circulatingNurse
          , signedPxRID : signedPxRID
          , singedByName : singedByName
          , singedBySign : singedBySign
          , orNursePxRID : orNursePxRID
          , orNurseName : orNurseName
          , orNurseSign : orNurseSign
          , foto : foto
        }
        $scope.OperatingRoomScheduleListAllObj.push(newRecord);
        // console.log($scope.OperatingRoomScheduleListAllObj);
        if ($scope.OperatingRoomScheduleListAllObj == "") {
          ngToast.show("No Data!", 'top');
        }
        $scope.OperatingRoomScheduleListAllObjcurrent_grid = 1;
        $scope.OperatingRoomScheduleListAllObjdata_limit = $scope.OperatingRoomScheduleListAllObj.length;
        $scope.OperatingRoomScheduleListAllObjfilter_data = $scope.OperatingRoomScheduleListAllObj.length;
        $scope.OperatingRoomScheduleListAllObjentire_user = $scope.OperatingRoomScheduleListAllObj.length;
      }
    });
  };

  $scope.getOperatingRoomScheduleReportAllList($scope.tempfromDate, $scope.temptoDate);


  $scope.page_positionAll = function(page_number) {
      $scope.OperatingRoomScheduleListAllObjcurrent_grid = page_number;
  };
  $scope.filterAll = function() {
      $timeout(function() {
          $scope.OperatingRoomScheduleListAllObjfilter_data = $scope.searched.length;
      }, 20);
  };

  $scope.base = '- SurgeryDate';
  $scope.reverse = true;

  $scope.sort_withAll = function(base) {
      // $scope.base = base;
      // $scope.reverse = !$scope.reverse;
      $scope.reverse = ($scope.base === base) ? !$scope.reverse : false;
      $scope.base = base;
  };


  $scope.showAllWithOrcaseNum = function() {
    console.log('this was clicked');
    $scope.getFinalORcaseList($scope.temptoDate);
    $scope.allwithOrcaseNumDialog(true);
  };

  $scope.cancelAllOrCaseNum = function() {
    $scope.allwithOrcaseNumDialog(false);
  };

  $scope.allwithOrcaseNumDialog = function(flag) {
    jQuery("#allwithOrcaseNumModal .modal").modal(flag ? 'show' : 'hide');
  };


  // All list
  $scope.getFinalORcaseList = function () {
    $scope.allOrCaseListObj = [];
    dbServices.getFinalORcaseList($scope.temptoDate)
    .then(function success(response) {
      
      for (var i = 0; i < response.data.length; i++) {
        var pxName = response.data[i].pxName;
        var diagnosis = response.data[i].diagnosis;
        var SurgeryType = response.data[i].SurgeryType;
        var Surgeon = response.data[i].Surgeon;
        var SurgeryDate = response.data[i].SurgeryDate;
        var wrid = response.data[i].wrid;
        var AnesthTypeOthers = response.data[i].AnesthTypeOthers;
        var AnesthesiaType = response.data[i].AnesthesiaType;
        var Anesthesio = response.data[i].Anesthesio;
        var Assistant = response.data[i].Assistant;
        var Cardio = response.data[i].Cardio;
        var ClinixRID = response.data[i].ClinixRID;
        var HospRID = response.data[i].HospRID;
        var PxRID = response.data[i].PxRID;
        var orCaseRID = response.data[i].orCaseRID;
        var OrNurse = response.data[i].OrNurse;
        var Hospital = response.data[i].Hospital;
        var OrNurse = response.data[i].OrNurse;
        var Others = response.data[i].Others;
        var scrubNurse = response.data[i].scrubNurse;
        var circulatingNurse = response.data[i].circulatingNurse;
        var signedPxRID = response.data[i].signedPxRID;
        var singedByName = response.data[i].singedByName;
        var singedBySign = response.data[i].singedBySign;
        var orNursePxRID = response.data[i].orNursePxRID;
        var orNurseName = response.data[i].orNurseName;
        var orNurseSign = response.data[i].orNurseSign;
        if (SurgeryDate != '0000-00-00') {
          SurgeryDate = moment(SurgeryDate).format();
        }
        var SurgeryTime = response.data[i].SurgeryTime;
        if (SurgeryDate != '0000-00-00' && SurgeryTime != '00:00:00') {
          var tempSurgeryDate = $filter('date')(new Date(SurgeryDate), 'yyyy-MM-dd');
          SurgeryTime = tempSurgeryDate + ' ' + SurgeryTime;
          SurgeryTime = moment(SurgeryTime).format();
        }
        var SurgeryTimeEnd = response.data[i].SurgeryTimeEnd;
        if (SurgeryDate != '0000-00-00' && SurgeryTimeEnd != '00:00:00') {
          var tempSurgeryDate = $filter('date')(new Date(SurgeryDate), 'yyyy-MM-dd');
          SurgeryTimeEnd = tempSurgeryDate + ' ' + SurgeryTimeEnd;
          SurgeryTimeEnd = moment(SurgeryTimeEnd).format();
        }
        var surgeryStatus = response.data[i].surgeryStatus;
        var surgeryStatusDesc = "";
        if (surgeryStatus == 1) {
          surgeryStatusDesc = "STAT";
        }else if (surgeryStatus == 3) {
          surgeryStatusDesc = "RESERVED";
        }else if (surgeryStatus == 2) {
          surgeryStatusDesc = "ELECTIVE";
        }else if (surgeryStatus == 5) {
          surgeryStatusDesc = "RESCHEDULE";
        }else if (surgeryStatus == 6) {
          surgeryStatusDesc = "CANCELED";
        }else if (surgeryStatus == 9) {
          surgeryStatusDesc = "COMPLETED";
        }
        var operatingRoom = response.data[i].operatingRoom;
        var foto = response.data[i].foto;
        newRecord={
          pxName : pxName
          , SurgeryType : SurgeryType
          , diagnosis : diagnosis
          , Surgeon : Surgeon
          , SurgeryDate : SurgeryDate
          , SurgeryTime : SurgeryTime
          , SurgeryTimeEnd : SurgeryTimeEnd
          , operatingRoom : operatingRoom
          , surgeryStatus : surgeryStatus
          , surgeryStatusDesc : surgeryStatusDesc
          , wrid : wrid
          , AnesthTypeOthers : AnesthTypeOthers
          , AnesthesiaType : AnesthesiaType
          , Anesthesio : Anesthesio
          , Assistant : Assistant
          , Cardio : Cardio
          , ClinixRID : ClinixRID
          , HospRID : HospRID
          , PxRID : PxRID
          , orCaseRID : orCaseRID
          , OrNurse : OrNurse
          , Hospital : Hospital
          , OrNurse : OrNurse
          , Others : Others
          , scrubNurse : scrubNurse
          , circulatingNurse : circulatingNurse
          , signedPxRID : signedPxRID
          , singedByName : singedByName
          , singedBySign : singedBySign
          , orNursePxRID : orNursePxRID
          , orNurseName : orNurseName
          , orNurseSign : orNurseSign
          , foto : foto
        }
        $scope.allOrCaseListObj.push(newRecord);
        // console.log($scope.allOrCaseListObj);
      }
    });
  };



  $scope.showAddOrCaseNumber = function(OperatingRoomScheduleList) {
    console.log(OperatingRoomScheduleList);

    $scope.getPxPreopDiagnosis(OperatingRoomScheduleList.HospRID, OperatingRoomScheduleList.ClinixRID);
    $scope.getLastORCaseNumber();

    $scope.surgerySchedOBJ = OperatingRoomScheduleList;
    $scope.addthisORcaseNumDialog(true);
  };


  $scope.cancelOrCaseNumber = function() {
    $scope.surgerySchedOBJ = {};
    $scope.addthisORcaseNumDialog(false);
  };

  $scope.addthisORcaseNumDialog = function(flag) {
    jQuery("#thisORCaseNumberModal .modal").modal(flag ? 'show' : 'hide');
  };


  $scope.insertSurgerySchedule = function(surgerySchedlistOBJ, lastOrCaseNumberobj) {
    console.log(surgerySchedlistOBJ);
    surgerySchedlistOBJ.SurgeryTime = $filter('date')(new Date(surgerySchedlistOBJ.SurgeryTime), 'HH:mm:ss');
    surgerySchedlistOBJ.SurgeryTimeEnd = $filter('date')(new Date(surgerySchedlistOBJ.SurgeryTimeEnd), 'HH:mm:ss');
    surgerySchedlistOBJ.SurgeryDate = $filter('date')(new Date(surgerySchedlistOBJ.SurgeryDate), 'yyyy-MM-dd');
    console.log(surgerySchedlistOBJ.orCaseRID);
    if(surgerySchedlistOBJ.orCaseRID == 0) {
       surgerySchedlistOBJ.orCaseRID = lastOrCaseNumberobj;
    } else {

    }

    dbServices.insertSurgerySchedule(surgerySchedlistOBJ)
    .then(function success(response) {
      // console.log(response);
      ngToast.show("Surgery schedule has been successfully updated.", 'top');
      $scope.getOperatingRoomScheduleReportAllList($scope.tempfromDate, $scope.temptoDate);
      $scope.getFinalORcaseList($scope.temptoDate);
      $scope.cancelOrCaseNumber();
    });
  };



  $scope.getPxPreopDiagnosis = function(HospRID, ClinixRID) {
    dbServices.getPxPreopDiagnosis(HospRID, ClinixRID)
    .then(function success(response) {
        $scope.PxpreopDiagnosisobj = response.data;
        console.log($scope.PxpreopDiagnosisobj);
    });
  };

  $scope.getLastORCaseNumber = function() {
    dbServices.getLastORCaseNumber()
    .then(function success(response) {
        $scope.lastOrCaseNumberobj = response.data.orCaseRID;
        $scope.lastOrCaseNumberobj = parseInt($scope.lastOrCaseNumberobj)+1;
        console.log($scope.lastOrCaseNumberobj);
    });
  };

  $scope.signOrNurseSurgerySchedule = function(orNursePIN, surgerySchedOBJ) {

    if (orNursePIN) {
      dbServices.CheckPxDsig(orNursePIN)
      .then(function success(response) {
        if (response.data == "") {
          ngToast.show("Invalid PIN!", 'top');
        }else{
          dbServices.signOrNurseSurgerySchedule($scope.surgerySchedOBJ.wrid, response.data.PxRID)
          .then(function success(response) {
            ngToast.show("Successfully Signed!", 'top');
            $scope.showAddOrCaseNumber(surgerySchedOBJ);
          });
        }
      });
    }
  };


  $scope.signSurgerySchedule = function(PIN, surgerySchedOBJ) {

    if (!PIN) {
    }else{
      dbServices.CheckPxDsig(PIN)
      .then(function success(response) {
        if (response.data == "") {
          ngToast.show("Invalid PIN!", 'top');
        }else{
          dbServices.signSurgerySchedule($scope.surgerySchedOBJ.wrid, response.data.PxRID)
          .then(function success(response) {
            ngToast.show("Successfully Signed!", 'top');
            $scope.showAddOrCaseNumber(surgerySchedOBJ);
          });
        }
      });
    }
  };


  $scope.showupdateOtherSurgicalForms = function(OperatingRoomScheduleList) {
    console.log(OperatingRoomScheduleList);
    $scope.relateSurgerySchedOBJ = OperatingRoomScheduleList;
    $scope.updateOtherSurgicalFormsDialog(true);
  };

  $scope.cancelupdateOtherSurgicalForms= function() {
    $scope.relateSurgerySchedOBJ = {};
    $scope.updateOtherSurgicalFormsDialog(false);
  };

  $scope.updateOtherSurgicalFormsDialog = function(flag) {
    jQuery("#updateOtherSurgicalFormsModal .modal").modal(flag ? 'show' : 'hide');
  };


  $scope.updateOtherSurgicalFormsAction = function(relateSurgerySchedOBJ) {
    console.log(relateSurgerySchedOBJ);
    dbServices.updateOtherSurgicalFormsAction(relateSurgerySchedOBJ)
    .then(function success(response) {
      // console.log(response);
      ngToast.show("Data successfully saved!", 'top');
      $scope.getOperatingRoomScheduleReportAllList($scope.tempfromDate, $scope.temptoDate);
      $scope.cancelupdateOtherSurgicalForms();
    });
  };


  
  
});