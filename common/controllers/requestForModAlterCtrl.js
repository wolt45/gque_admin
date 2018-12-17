gmmrApp.controller('requestForModAlterCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, $timeout, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 


	$scope.showOnlyToAccountWPriviledged = true; 

	$scope.checkSysDoorKeys = function (PxRID) {
    
        dbServices.checkSysDoorKeys(PxRID, "6002")
        .then(function success(response) {

            if (response.data.DoorKnob == "6002") {
            	$scope.getRequestForModifAlter("0");
            	$scope.showOnlyToAccountWPriviledged = false; 
            	// console.log("authorize mag approved");
            }else{
               $scope.getRequestForModifAlter($scope.userPxRID);
               // console.log("Indi authorize mag approved");
            }
            

        });

    };

    $scope.checkSysDoorKeys($scope.userPxRID);
    

    $scope.getRequestForModifAlter = function (PxRID) {
      $scope.RequestForModifAlterListObj = [];
    	dbServices.getRequestForModifAlter(PxRID)
	    .then(function success(response) {
	    	// console.log(response);
	    	// $scope.RequestForModifAlterListObj = response.data;

        for (var i = 0; i < response.data.length; i++) {
          var requestAlterModRID = response.data[i].requestAlterModRID;
          var requestType = response.data[i].requestType;
          var requestDescription = response.data[i].requestDescription;
          var requestedBy = response.data[i].requestedBy;
          var RequestedByPxName = response.data[i].RequestedByPxName;
          var tempdateRequested =response.data[i].dateRequested;
          if (tempdateRequested != "0000-00-00 00:00:00") {
            var dateRequested = moment(tempdateRequested).format();
          }
          var requestStatus = response.data[i].requestStatus;
          var requestStatusDesc = response.data[i].requestStatusDesc;
          var approvedBy = response.data[i].approvedBy;
          var ApprovedByPxName = response.data[i].ApprovedByPxName;
          var tempdateApproved = response.data[i].dateApproved;
          if (tempdateApproved != "0000-00-00 00:00:00") {
            var dateApproved = moment(tempdateApproved).format();
          }
          var disApprovedBy = response.data[i].disApprovedBy;
          var DisapprovedByPxName = response.data[i].DisapprovedByPxName;
          var tempdateDisApproved = response.data[i].dateDisApproved;
          if (tempdateDisApproved != "0000-00-00 00:00:00") {
            var dateDisApproved = moment(tempdateDisApproved).format();
          }
          var disApprovedDescription = response.data[i].disApprovedDescription;
          var EnteredBy = response.data[i].EnteredBy;
          var Deleted = response.data[i].Deleted;

          newrecord = {
            requestAlterModRID : requestAlterModRID
            , requestType : requestType
            , requestDescription : requestDescription
            , requestedBy : requestedBy
            , RequestedByPxName : RequestedByPxName
            , dateRequested : dateRequested
            , requestStatus : requestStatus
            , requestStatusDesc : requestStatusDesc
            , approvedBy : approvedBy
            , ApprovedByPxName : ApprovedByPxName
            , dateApproved : dateApproved
            , disApprovedBy : disApprovedBy
            , DisapprovedByPxName : DisapprovedByPxName
            , dateDisApproved : dateDisApproved
            , disApprovedDescription : disApprovedDescription
            , EnteredBy : EnteredBy
            , Deleted : Deleted
            
          }
          $scope.RequestForModifAlterListObj.push(newrecord);
          
        }

        $scope.RequestForModifAlterListObjcurrent_grid = 1;
        $scope.RequestForModifAlterListObjdata_limit = 10;
        $scope.RequestForModifAlterListObjfilter_data = $scope.RequestForModifAlterListObj.length;
        $scope.RequestForModifAlterListObjentire_user = $scope.RequestForModifAlterListObj.length;


	    });

	};

    $scope.page_position = function(page_number) {
        $scope.RequestForModifAlterListObjcurrent_grid = page_number;
    };
    $scope.filter = function() {
        $timeout(function() {
            $scope.RequestForModifAlterListObjfilter_data = $scope.searched.length;
        }, 20);
    };
    $scope.sort_with = function(base) {
        $scope.base = base;
        $scope.reverse = !$scope.reverse;
    };




	$scope.insertRequestForModifAlter = function (RequestForModifAlterObj) {

    	dbServices.insertRequestForModifAlter(RequestForModifAlterObj, $scope.userPxRID)
	    .then(function success(response) {
	    	// console.log(response);
	    	$scope.checkSysDoorKeys($scope.userPxRID);
	    	$scope.RequestForModifAlterObj = {};
	    	alert("Data successfully save!");
	    });

	};

	$scope.editRequestForModifAlter = function (RequestForModifAlterList) {
    	$scope.RequestForModifAlterObj = RequestForModifAlterList;
    	// console.log(RequestForModifAlterList);
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
      dbServices.CheckPxDsigAcct(RequestedByPIN, $scope.userPxRID)
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
    var requestStatusDesc = "Approved";
    if (!ApprovedByPIN) {
    }else{
     dbServices.CheckPxDsigAcct(RequestedByPIN, $scope.userPxRID)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN!");
        }else{
          dbServices.signApprovedByRequestForModifAlter($scope.RequestForModifAlterObj.requestAlterModRID, requestStatus, requestStatusDesc, response.data.PxRID)
          .then(function success(response) {
            // console.log(response);
            alert("Successfully Sign!");
            $scope.checkSysDoorKeys($scope.userPxRID);
          });
        }
      });
    }
  };

  $scope.signDisapprovedByRequestForModifAlter = function(DisapprovedByPIN, requestStatus, disApprovedDescription) {
    var requestStatusDesc = "Diapproved";
    if (!DisapprovedByPIN) {
    }else{
      dbServices.CheckPxDsigAcct(RequestedByPIN, $scope.userPxRID)
      .then(function success(response) {
        if (response.data == "") {
          alert("Invalid PIN!");
        }else{
          dbServices.signDisapprovedByRequestForModifAlter($scope.RequestForModifAlterObj.requestAlterModRID, requestStatus, disApprovedDescription, requestStatusDesc, response.data.PxRID)
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




