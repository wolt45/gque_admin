gmmrApp.controller('drugsManagerCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, $timeout, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID");

	$scope.loadScript = function (url){
      // console.log('Javascript Loading...');
      let node = document.createElement('script');
      node.src = url;
      node.type = 'text/javascript';
      document.getElementsByTagName('head')[0].appendChild(node);   
    }
    // $scope.loadScript('build/js/customCalendar.js');  

    $scope.getDrugDepartment = function () {
		dbServices.getDrugDepartment()
		.then(function success(response) {
		  	$scope.DrugDepartmenListObj = response.data;
		});
	};

	$scope.getDrugDepartment();

	$scope.getDrugList = function () {
		dbServices.getDrugList()
		.then(function success(response) {
		  	$scope.DrugListObj = response.data;
		  	console.log($scope.DrugListObj);

		  	$scope.DrugListObjcurrent_grid = 1;
		  	$scope.DrugListObjdata_limit = 100;
		  	$scope.DrugListObjfilter_data = $scope.DrugListObj.length;
		  	$scope.DrugListObjentire_user = $scope.DrugListObj.length;
		});
	};

	$scope.getDrugList();


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

	$scope.insertMedicine = function (DrugObj) {
		dbServices.insertMedicine(DrugObj)
		.then(function success(response) {
			$scope.closeMedicine();
			$scope.getDrugList();
		  	// console.log(response);
		  	alert("Data successfully save!");
		});
	};


	$scope.editMedicine = function (DrugList) {
		$scope.DrugObj = DrugList;
		$scope.showMedicine();
	};

	$scope.newMedicine = function () {
		$scope.DrugObj = {};
		$scope.showMedicine();
	};

	$scope.showMedicine = function() {
		$scope.showMedicineDialog(true);
	}

	$scope.closeMedicine = function() {
		$scope.showMedicineDialog(false);
	};

	$scope.showMedicineDialog = function(flag) {
		jQuery("#MedicineModal .modal").modal(flag ? 'show' : 'hide');
	};

	
    	
});




