gmmrApp.controller('loginCtrl', function ($scope, $stateParams, $rootScope, $location, $window, $http, $filter, $sce, dbServices){

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

	$scope.checkAuth = function()
	{
		if ($scope.userPxRID == '' || $scope.userPxRID == null) {
		  $location.path('/login');
		}else{
		  $window.location.href = '/rbgmain';
		}
	};

	$scope.checkAuth();


    $scope.login = function (Username, Password) {
    	$scope.errorMessage = "";
    	$scope.errorMessage = "";
        if (Username != undefined && Password !=undefined) {
		    dbServices.login(Username, Password)
		    .then(function success(response) {
		    	// console.log(response);
		    	$scope.checkSysDoorKeys(response.data.PxRID, response.data.userTypeRID);
		    },
		    function error (response) {
		        $scope.message = '';
		        if (response.status === 404){
		        	// alert("Username or Password Invalid!");
		            $scope.errorMessage = 'Username or Password Invalid!';
    				// window.speechSynthesis.speak(new SpeechSynthesisUtterance($scope.errorMessage));
		        }
		        else {
		        	// alert("Error getting user!");
		            $scope.errorMessage = "Account cannot found!";
		            // window.speechSynthesis.speak(new SpeechSynthesisUtterance($scope.errorMessage));
		        }
		    });
		}else{
			$scope.errorMessage = "Please fill-up the form!";
			// window.speechSynthesis.speak(new SpeechSynthesisUtterance($scope.errorMessage));
		}
	};


	$scope.checkSysDoorKeys = function (PxRID, userTypeRID) {
    
        dbServices.checkSysDoorKeys(PxRID, "6601")
        .then(function success(response) {

            if (response.data.DoorKnob == "6601") {

            	var encryptedPxRID = CryptoJS.AES.encrypt(PxRID, "Passphrase"); 
            	var encrypteduserTypeRID = CryptoJS.AES.encrypt(userTypeRID, "Passphrase"); 

		    	localStorage.setItem("gmmrCentraluserPxRID", encryptedPxRID);
		    	localStorage.setItem("gmmrCentraluserTypeRID", encrypteduserTypeRID);

		    	localStorage.setItem("rbgreguserPxRID", PxRID);
		    	localStorage.setItem("rbgreguserTypeRID", userTypeRID);

		    	localStorage.setItem("gmmr2userPxRID", PxRID);
		    	localStorage.setItem("gmmr2userTypeRID", userTypeRID);

		    	localStorage.setItem("gmmr3userPxRID", PxRID);
		    	localStorage.setItem("gmmr3userTypeRID", userTypeRID);

		    	$window.location.href = '/gque_admin';
		    	// $location.path('/dashboard');
		    	$scope.successMessage = 'Account Successfully Login!';
		    	// window.speechSynthesis.speak(new SpeechSynthesisUtterance($scope.successMessage));
            }else{
               $scope.errorMessage = 'Access denied!';
               // window.speechSynthesis.speak(new SpeechSynthesisUtterance($scope.errorMessage));
            }
        });
    };
});




