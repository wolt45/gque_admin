gmmrApp.controller('orDisinfectCheckListCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, $filter, dbServices){

  $scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 
  $scope.userTypeRID = localStorage.getItem("gmmrCentraluserTypeRID");

  $scope.loadScript = function (url){
    // console.log('Javascript Loading...');
    let node = document.createElement('script');
    node.src = url;
    node.type = 'text/javascript';
    document.getElementsByTagName('head')[0].appendChild(node);   
  }

  $scope.loadScript('build/js/customPanelToolBox.js');  
  

  $scope.getOperatingRoomDisinfectionDetail = function (operatingDisinfectCheckRID) {
    $scope.OperatingRoomDisinfectionListOBJ = [];
    dbServices.getOperatingRoomDisinfectionDetail(operatingDisinfectCheckRID)
    .then(function success(response) {
      console.log(response);
      for (var i = 0; i < response.data.length; i++) {
        var anesthesiaMachine = response.data[i].anesthesiaMachine;
        var electrocauteryMachine = response.data[i].electrocauteryMachine;
        var equipmentCabinet = response.data[i].equipmentCabinet;
        var floor = response.data[i].floor;

        var dateTimeEntered = response.data[i].dateTimeEntered;
        if (dateTimeEntered != "0000-00-00 00:00:00") {
          dateTimeEntered = moment(dateTimeEntered).format();
        }

        var initialName = response.data[i].initialName;
        var initialPxRID = response.data[i].initialPxRID;
        var initialSign = response.data[i].initialSign;
        var operatingDisinfectCheckDetailRID = response.data[i].operatingDisinfectCheckDetailRID;
        var operatingDisinfectCheckRID = response.data[i].operatingDisinfectCheckRID;
        var orBed = response.data[i].orBed;
        var orLight = response.data[i].orLight;
        var others = response.data[i].others;

        var remarks = response.data[i].remarks;
        var suctionMachine = response.data[i].suctionMachine;
        var suppliesCabinet = response.data[i].suppliesCabinet;
        var wall = response.data[i].wall;
        
        newrecord = {
          anesthesiaMachine : anesthesiaMachine
          , electrocauteryMachine : electrocauteryMachine
          , equipmentCabinet : equipmentCabinet
          , floor : floor

          , dateTimeEntered : dateTimeEntered

          , initialName : initialName
          , initialPxRID : initialPxRID
          , initialSign : initialSign
          , operatingDisinfectCheckDetailRID : operatingDisinfectCheckDetailRID
          , operatingDisinfectCheckRID : operatingDisinfectCheckRID
          , orBed : orBed
          , orLight : orLight
          , others : others

          , remarks : remarks
          , suctionMachine : suctionMachine
          , suppliesCabinet : suppliesCabinet
          , wall : wall
        }
        $scope.OperatingRoomDisinfectionListOBJ.push(newrecord);
        // console.log($scope.PhysicianOrderSheetList);
      }
    });
  };

  

  
  $scope.insertOperatingRoomDisinfectionDetail = function (OperatingRoomDisinfectionObjDetails) {
    // console.log($scope.OperatingRoomDisinfectionObj.operatingDisinfectCheckRID);
    OperatingRoomDisinfectionObjDetails.dateTimeEntered = $filter('date')(new Date(OperatingRoomDisinfectionObjDetails.dateTimeEntered), 'yyyy-MM-dd HH:mm:ss');

    dbServices.insertOperatingRoomDisinfectionDetail($scope.OperatingRoomDisinfectionObj.operatingDisinfectCheckRID, OperatingRoomDisinfectionObjDetails)
    .then(function success(response) {
      $scope.getOperatingRoomDisinfectionDetail($scope.OperatingRoomDisinfectionObj.operatingDisinfectCheckRID);
      $scope.OperatingRoomDisinfectionObjDetails = {};
      // console.log(response);
    });
  };

  $scope.removeOperatingRoomDisinfectionDetail = function (operatingDisinfectCheckRID) {
    if (confirm("Are you sure to delete this data?")) {
      dbServices.removeOperatingRoomDisinfectionDetail(operatingDisinfectCheckRID)
      .then(function success(response) {
        $scope.getOperatingRoomDisinfectionDetail();
      });
    }
  };

  $scope.editOperatingRoomDisinfection = function (OperatingRoomDisinfectionList) {
    $scope.OperatingRoomDisinfectionObjDetails = OperatingRoomDisinfectionList;
  };

  $scope.getOperatingRoomDisinfection = function () {
    dbServices.getOperatingRoomDisinfection()
    .then(function success(response) {
      $scope.OperatingRoomDisinfectionListObj = response.data;
    });
  };

  $scope.getOperatingRoomDisinfection();

  $scope.insertOperatingRoomDisinfection = function (OperatingRoomDisinfectionObj) {
    if (confirm("Are you sure to create new room?")) {
      dbServices.insertOperatingRoomDisinfection(OperatingRoomDisinfectionObj)
      .then(function success(response) {
        $scope.getOperatingRoomDisinfection();
        alert("Data successfully save!");
      });
    }
  };

  $scope.removeOperatingRoomDisinfection = function (operatingDisinfectCheckRID) {
    if (confirm("Are you sure to remove this data?")) {
      dbServices.removeOperatingRoomDisinfection(operatingDisinfectCheckRID)
      .then(function success(response) {
        alert("Data successfully remove!");
      });
    }
  };

  $scope.viewOperatingRoomDisinfection = function (OperatingRoomDisinfectionList) {
    $scope.OperatingRoomDisinfectionObj = OperatingRoomDisinfectionList;
    $scope.getOperatingRoomDisinfectionDetail($scope.OperatingRoomDisinfectionObj.operatingDisinfectCheckRID);
  };

  $scope.newOperatingRoomDisinfection = function () {
    dbServices.newOperatingRoomDisinfection()
    .then(function success(response) {
      // console.log(response);
      $scope.getOperatingRoomDisinfection();
      $scope.viewOperatingRoomDisinfection(response.data);
    });
  };



  $scope.signOperatingRoomDisinfection = function(PIN, operatingDisinfectCheckRID) {

    if (PIN) {
      dbServices.CheckPxDsig(PIN)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN");
        }else{
          dbServices.signOperatingRoomDisinfection(operatingDisinfectCheckRID, response.data.PxRID)
          .then(function success(response) {
            // console.log(response);
            alert("Successfully Sign!");
            $scope.getOperatingRoomDisinfectionDetail($scope.OperatingRoomDisinfectionObj.operatingDisinfectCheckRID);
          });
        }
      });
    }
  };


  
});




