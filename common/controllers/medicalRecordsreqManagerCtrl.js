gmmrApp.controller('medicalRecordsreqManagerCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, $timeout,ngToast, dbServices){

	var decrypteduserPxRID = localStorage.getItem("gmmrCentraluserPxRID");
  	if (decrypteduserPxRID) {
    	decrypteduserPxRID = CryptoJS.AES.decrypt(decrypteduserPxRID, "Passphrase").toString(CryptoJS.enc.Utf8);
  	}

  	var decrypteduserTypeRID = localStorage.getItem("gmmrCentraluserTypeRID"); 
  	if (decrypteduserPxRID) {
    	decrypteduserTypeRID = CryptoJS.AES.decrypt(decrypteduserTypeRID, "Passphrase").toString(CryptoJS.enc.Utf8);
  	}

  	$scope.userPxRID = decrypteduserPxRID; 
  	$scope.userTypeRID = decrypteduserTypeRID;
  	$scope.count = 0;

	$scope.loadScript = function (url){
      // console.log('Javascript Loading...');
      let node = document.createElement('script');
      node.src = url;
      node.type = 'text/javascript';
      document.getElementsByTagName('head')[0].appendChild(node);   
    }
    // $scope.loadScript('build/js/customCalendar.js');  


	$scope.getMedRequestList = function () {
		dbServices.getMedRequestList()
		.then(function success(response) {
		  	$scope.medRequestListObj = response.data;
		  	console.log($scope.medRequestListObj);

		  	$scope.medRequestListObjcurrent_grid = 1;
		  	$scope.medRequestListObjdata_limit = 50;
		  	$scope.medRequestListObjfilter_data = $scope.medRequestListObj.length;
		  	$scope.medRequestListObjentire_user = $scope.medRequestListObj.length;
		});
	};

	$scope.getMedRequestList();


	$scope.page_position = function(page_number) {
	  	$scope.DrugListObhcurrent_grid = page_number;
	};
	$scope.filter = function() {
	  	$timeout(function() {
	      	$scope.DrugListObhfilter_data = $scope.searched.length;
	  	}, 20);
	};
	$scope.sort_with = function(base) {
	  	$scope.base = base;
	  	$scope.reverse = !$scope.reverse;
	};

	$scope.base = '- medDate';
    $scope.reverse = true;

	// $scope.insertMedicine = function (requestmedRecordOBJ) {
	// 	dbServices.insertMedicine(requestmedRecordOBJ)
	// 	.then(function success(response) {
	// 		$scope.closeMedicine();
	// 		$scope.getMedRequestList();
	// 	  	// console.log(response);
	// 	  	alert("Data successfully save!");
	// 	});
	// };


	$scope.editMedicine = function (MedReqList) {
		console.log(MedReqList);
		$scope.requestmedRecordOBJ = MedReqList;
		$scope.showMedicine();
	};

	// $scope.newMedicine = function () {
	// 	$scope.requestmedRecordOBJ = {};
	// 	$scope.showMedicine();
	// };

	$scope.showMedicine = function() {
		$scope.requestmedRecordOBJ.releaseDate = new Date;
		$scope.showMedicineDialog(true);
	}

	$scope.closeMedicine = function() {
		$scope.showMedicineDialog(false);
	};

	$scope.showMedicineDialog = function(flag) {
		jQuery("#MedicineModal .modal").modal(flag ? 'show' : 'hide');
	};


	$scope.ReleaseSignRequest = function(requestmedRecordOBJ) {

    if (!requestmedRecordOBJ.releaseStatus) {
      $scope.showMedicine();
    }else{
      console.log(requestmedRecordOBJ.releaseStatus);
      if (requestmedRecordOBJ.SignedPin) {
        dbServices.CheckPxDsig(requestmedRecordOBJ.SignedPin)
        .then(function success(response) {
          if (response.data == "") {
            alert("Invalid PIN!");
          }else{

            dbServices.ReleaseSignRequest(requestmedRecordOBJ.releaseStatus, requestmedRecordOBJ.releaseDate, response.data.PxRID, requestmedRecordOBJ.requestmedRecordRID)
            .then(function success(response) {
              // console.log(response);
              $scope.closeMedicine();
              alert("Successfully Signed!");
              $scope.getMedRequestList();
            
            });
          }
        });
      }
    }
  };

	
    	
});




