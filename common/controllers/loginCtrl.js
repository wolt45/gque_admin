gmmrApp.controller('loginCtrl', function ($scope, $stateParams, $rootScope, $location, $window, $http, $filter, $sce, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 

	$scope.checkAuth = function()
	{
		if ($scope.userPxRID == '' || $scope.userPxRID == null) {
		  $location.path('/login');
		}else{
		  $window.location.href = 'index.php';
		}
	};

	$scope.checkAuth();


    $scope.login = function (Username, Password) {
    	$scope.errorMessage = "";
    	$scope.errorMessage = "";
        if (Username != undefined && Password !=undefined) {
		    dbServices.login(Username, Password)
		    .then(function success(response) {
		    	// console.log(response.data);
		    	$scope.checkSysDoorKeys(response.data.PxRID, response.data.userTypeRID);
		    },
		    function error (response) {
		        $scope.message = '';
		        if (response.status === 404){
		        	// alert("Username or Password Invalid!");
		            $scope.errorMessage = 'Username or Password Invalid!';
		        }
		        else {
		        	// alert("Error getting user!");
		            $scope.errorMessage = "Account cannot found!";
		        }
		    });
		}else{
			$scope.errorMessage = "Please fill-up the form!";
		}
	};


	$scope.checkSysDoorKeys = function (PxRID, userTypeRID) {
    
        dbServices.checkSysDoorKeys(PxRID, "6601")
        .then(function success(response) {

            if (response.data.DoorKnob == "6601") {

		    	localStorage.setItem("gmmrCentraluserPxRID", PxRID);
		    	localStorage.setItem("gmmrCentraluserTypeRID", userTypeRID);

		    	localStorage.setItem("rbgreguserPxRID", PxRID);
		    	localStorage.setItem("rbgreguserTypeRID", userTypeRID);

		    	localStorage.setItem("gmmr2userPxRID", PxRID);
		    	localStorage.setItem("gmmr2userTypeRID", userTypeRID);

		    	localStorage.setItem("gmmr3userPxRID", PxRID);
		    	localStorage.setItem("gmmr3userSpecialty", userTypeRID);

		    	$window.location.href = 'index.php';
		    	// $location.path('/dashboard');
		    	$scope.successMessage = 'Account Successfully Login!';
            }else{
               $scope.errorMessage = 'Access denied!';
            }
            

        });

    };




    
});




