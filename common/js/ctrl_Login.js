gmmrApp.controller('LoginCtrl', function ($scope, $stateParams, $rootScope, $location, $window, $http, $sce, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID")
	// console.log("$scope.userPxRID");
	// console.log($scope.userPxRID);

	if ($scope.userPxRID == '' || $scope.userPxRID == null) {
		// console.log("Stay here!");
	}else{
		$window.location.href = 'pages/index.php';
	}

    $scope.LoginAccount = function (Username, Password) {
          
	    dbServices.LoginAccount(Username, Password)
	    .then(function success(response) {
	    	console.log(response.data);

	    	localStorage.setItem("gmmrCentraluserPxRID", response.data.PxRID);
	    	localStorage.setItem("gmmrCentraluserTypeRID", response.data.userTypeRID);

	    	localStorage.setItem("rbgreguserPxRID", response.data.PxRID);
	    	localStorage.setItem("rbgreguserTypeRID", response.data.userTypeRID);

	    	localStorage.setItem("gmmr2userPxRID", response.data.PxRID);
	    	localStorage.setItem("gmmr2userTypeRID", response.data.userTypeRID);

	    	localStorage.setItem("gmmr3userPxRID", response.data.PxRID);
	    	localStorage.setItem("gmmr3userSpecialty", response.data.UserType);

	    	$window.location.href = 'pages/index.php';
	    	// $location.path('pages');
	    },
	    function error (response) {
	        $scope.message = '';
	        if (response.status === 404){
	        	alert("User not found!");
	            $scope.errorMessage = 'User not found!';
	        }
	        else {
	        	alert("Error getting user!");
	            $scope.errorMessage = "Error getting user!";
	        }
	    });
	};


});