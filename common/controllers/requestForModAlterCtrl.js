gmmrApp.controller('requestForModAlterCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 


	$scope.showOnlyToAccountWPriviledged = true; 

	$scope.checkSysDoorKeys = function (PxRID) {
    
        dbServices.checkSysDoorKeys(PxRID, "6002")
        .then(function success(response) {

            if (response.data.DoorKnob == "6002") {
            	$scope.getRequestForModifAlter("0");
            	$scope.showOnlyToAccountWPriviledged = false; 
            	console.log("authorize mag approved");
            }else{
               $scope.getRequestForModifAlter($scope.userPxRID);
               console.log("Indi authorize mag approved");
            }
            

        });

    };

    $scope.checkSysDoorKeys($scope.userPxRID);
    

    $scope.getRequestForModifAlter = function (PxRID) {

    	dbServices.getRequestForModifAlter(PxRID)
	    .then(function success(response) {
	    	console.log(response);
	    	$scope.RequestForModifAlterListObj = response.data;
	    });

	};



	$scope.insertRequestForModifAlter = function (RequestForModifAlterObj) {

    	dbServices.insertRequestForModifAlter(RequestForModifAlterObj, $scope.userPxRID)
	    .then(function success(response) {
	    	console.log(response);
	    	$scope.checkSysDoorKeys($scope.userPxRID);
	    });

	};

	$scope.editRequestForModifAlter = function (RequestForModifAlterList) {
    	$scope.RequestForModifAlterObj = RequestForModifAlterList;
    	console.log(RequestForModifAlterList);
	};

	$scope.cancelRequestForModifAlter = function () {
    	$scope.RequestForModifAlterObj = {};
	};

	$scope.newRequestForModifAlter = function () {
    	$scope.RequestForModifAlterObj = {};
	};

	$scope.signRequestedByRequestForModifAlter = function(RequestedByPIN) {

    if (!RequestedByPIN) {
    }else{
      dbServices.CheckPxDsig(RequestedByPIN)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN!");
        }else{
          dbServices.signRequestedByRequestForModifAlter($scope.RequestForModifAlterObj.requestAlterModRID, response.data.PxRID)
          .then(function success(response) {
            // console.log(response);
            alert("Successfully Sign!");
            $scope.checkSysDoorKeys($scope.userPxRID);
          });
        }
      });
    }
  };

  $scope.signApprovedByRequestForModifAlter = function(ApprovedByPIN, requestStatus) {

    if (!ApprovedByPIN) {
    }else{
      dbServices.CheckPxDsig(ApprovedByPIN)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN!");
        }else{
          dbServices.signApprovedByRequestForModifAlter($scope.RequestForModifAlterObj.requestAlterModRID, requestStatus, response.data.PxRID)
          .then(function success(response) {
            // console.log(response);
            alert("Successfully Sign!");
            $scope.checkSysDoorKeys($scope.userPxRID);
          });
        }
      });
    }
  };

  $scope.signDisapprovedByRequestForModifAlter = function(DisapprovedByPIN, requestStatus) {

    if (!DisapprovedByPIN) {
    }else{
      dbServices.CheckPxDsig(DisapprovedByPIN)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN!");
        }else{
          dbServices.signDisapprovedByRequestForModifAlter($scope.RequestForModifAlterObj.requestAlterModRID, requestStatus, response.data.PxRID)
          .then(function success(response) {
            // console.log(response);
            alert("Successfully Sign!");
            $scope.checkSysDoorKeys($scope.userPxRID);
          });
        }
      });
    }
  };

	
});




