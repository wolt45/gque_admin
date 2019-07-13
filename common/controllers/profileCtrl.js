gmmrApp.controller('profileCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, $timeout, dbServices){

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

  $scope.newAccountShow = false;
  $scope.newPINShow = false;

  $scope.getUserProfile = function (userPxRID) {

    dbServices.getUserProfile(userPxRID)
    .then(function success(response) {
      $scope.userItem = response.data;
    });
  };

  $scope.getUserProfile($scope.userPxRID);

  $scope.checkAccount = function (oldAccountObj) {

    dbServices.checkAccount(oldAccountObj, $scope.userPxRID)
    .then(function success(response) {
      console.log(response);
      if (response.data == "") {
        alert("Wrong username or password!");
      }else{
        $scope.newAccountShow = true;
      }
    });
  };

  $scope.renewAccount = function (newAccountObj) {

    dbServices.renewAccount(newAccountObj, $scope.userPxRID)
    .then(function success(response) {
      console.log(response);
      alert("Account successfully change. You will be logged out after this.");
      localStorage.clear();
      $window.location.href = 'login.php';
    });
  };


  $scope.checkPxDsigAcct = function (oldPIN) {
    dbServices.CheckPxDsigAcct(oldPIN, $scope.userPxRID)
    .then(function success(response) {
      console.log(response);
      if (response.data == "") {
        alert("Invalid PIN!");
      }else{
        $scope.newPINShow = true;
      }
    });
  };

  $scope.renewCheckPxDsigAcct = function (newPIN) {

    dbServices.renewCheckDuplicatePxDsigAcct(newPIN)
    .then(function success(response) {
      console.log(response);
      if (response.data == "") {
        dbServices.renewCheckPxDsigAcct(newPIN, $scope.userPxRID)
        .then(function success(response) {
          console.log(response);
          alert("PIN successfully change.");
        });
      }else{
        alert("PIN already in use! Try again.");
      }
    });

    
  };
	
});




