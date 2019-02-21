gmmrApp.controller('sideTopCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $interval, $filter, $sce, Idle, Keepalive, $uibModal, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 
	$scope.userTypeRID = localStorage.getItem("gmmrCentraluserTypeRID");

	$scope.notifItemSum= 0;
  $scope.messageItemSum= 0;
  $scope.DateNow = new Date;

  $scope.myAccessLocation= "";

	$scope.checkAuth = function()
  {
    if ($scope.userPxRID == '' || $scope.userPxRID == null) {
      // $location.path('/login');
      $window.location.href = $scope.myAccessLocation + '/rbgmain/login.php';
    }else{
      // console.log("Stay here!");
    }
  };

  
  $scope.getMyAccess = function()
  {
    dbServices.getMyAccess()
    .then(function success(response) {
      $scope.myAccessLocation = 'https://'+response.data;
      console.log($scope.myAccessLocation);
      $scope.checkAuth();
    });
  };

  $scope.getMyAccess();

  $scope.loadScript = function (url){
    // console.log('Javascript Loading...');
    let node = document.createElement('script');
    node.src = url;
    node.type = 'text/javascript';
    document.getElementsByTagName('head')[0].appendChild(node);   
  }

  $scope.loadScript('build/js/customSidemenu.js');  
  $scope.loadScript('build/js/customPanelToolBox.js');  

    $scope.LoadUserProfile = function (userPxRID) {

	    dbServices.getUserProfile(userPxRID)
	    .then(function success(response) {
	      $scope.userItem = response.data;
	    });
	  };

  	$scope.LoadUserProfile($scope.userPxRID);


  $scope.getNotifications = function () {

    dbServices.getNotifications($scope.userPxRID)
    .then(function success(response) {
      $scope.notifItem = response.data;
      // console.log($scope.notifItem);
      var tempSum = $scope.notifItem.length;
      $scope.notifItemSum = parseFloat($scope.notifItemSum) + parseFloat(tempSum);
    });
  };

  $scope.getNotifications();

  $scope.getNotificationsBirthdays = function () {

    dbServices.getNotificationsBirthdays()
    .then(function success(response) {
      $scope.notifItemBirthdays = response.data;
      $scope.notifItemBirthdaysSum = $scope.notifItemBirthdays.length;
      $scope.notifItemSum = parseFloat($scope.notifItemSum) + parseFloat($scope.notifItemBirthdaysSum);
    });
  };

  $scope.getNotificationsBirthdays();

  $scope.getNotificationsRequestForModifAlter = function (userPxRID) {

    dbServices.getNotificationsRequestForModifAlter(userPxRID)
    .then(function success(response) {
      $scope.NotificationsRequestForModifAlterListObj = response.data;
      $scope.NotificationsRequestForModifAlterSum = $scope.NotificationsRequestForModifAlterListObj.length;
      $scope.notifItemSum = parseFloat($scope.notifItemSum) + parseFloat($scope.NotificationsRequestForModifAlterSum);
    });
  };


  $scope.checkSysDoorKeys = function (PxRID) {
  
      dbServices.checkSysDoorKeys(PxRID, "6002")
      .then(function success(response) {

          if (response.data.DoorKnob == "6002") {
            $scope.getNotificationsRequestForModifAlter("0");
            $scope.showOnlyToAccountWPriviledged = false; 
          }else{
             $scope.getNotificationsRequestForModifAlter($scope.userPxRID);
          }
      });

  };

  $scope.checkSysDoorKeys($scope.userPxRID);

  $interval(function(){
    $scope.notifItemSum= 0;
    $scope.messageItemSum= 0; 
    $scope.checkSysDoorKeys($scope.userPxRID);
    $scope.getNotifications();
  },10000);


  	$scope.registrationSidemenu = false;
  	$scope.opdOrthopedicsSidemenu = false;
  	$scope.opdSidemenu = false;
  	$scope.inPatientSidemenu = false;
  	$scope.diagnostixSidemenu = false;

  	$scope.mediaManagerSidemenu = false;
  	$scope.bulkUploaderSidemenu = false;
  	$scope.icd10CodeSidemenu = false;
  	$scope.billingCodeSidemenu = false;
  	$scope.rvsCodeSidemenu = false;
    $scope.adminPanelSidemenu = false;


  	$scope.checkAcctSysDoorKeys = function (PxRID) {
    dbServices.checkAcctSysDoorKeys(PxRID)
    .then(function success(response) {

      for (var i = 0; i < response.data.length; i++) {
        if (response.data[i].DoorKnob == "2001") {
          $scope.registrationSidemenu = true;
        }

        if (response.data[i].DoorKnob == "4001") {
          $scope.opdOrthopedicsSidemenu = true;
        }

        if (response.data[i].DoorKnob == "7001") {
          $scope.opdSidemenu = true;
        }

        if (response.data[i].DoorKnob == "3001") {
          $scope.inPatientSidemenu = true;
        }

        if (response.data[i].DoorKnob == "5001") {
          $scope.diagnostixSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6602") {
          $scope.mediaManagerSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6603") {
          $scope.bulkUploaderSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6604") {
          $scope.icd10CodeSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6605") {
          $scope.rvsCodeSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6606") {
          $scope.billingCodeSidemenu = true;
        }

        if (response.data[i].DoorKnob == "6001") {
          $scope.adminPanelSidemenu = true;
        }


      }

    });

  };

  $scope.checkAcctSysDoorKeys($scope.userPxRID);

    $scope.logout = function () {
    	if (confirm("Are you sure to logout?")) {
        localStorage.clear();

          $window.location.href = 'login.php';
	    	// dbServices.logout($scope.userPxRID)
		    // .then(function success(response) {
		    // 	// console.log(response.data);
		    // 	// localStorage.setItem("gmmrCentraluserPxRID", "");
		    // 	localStorage.clear();

		    // 	$window.location.href = 'login.php';

		    // });
		}
	};


  // for idle

  $scope.started = false;

  function closeModals() {
    if ($scope.warning) {
      $scope.warning.close();
      $scope.warning = null;
    }

    if ($scope.timedout) {
      $scope.timedout.close();
      $scope.timedout = null;
    }
  }

  $scope.$on('IdleStart', function() {
    closeModals();

    $scope.warning = $uibModal.open({
      templateUrl: 'warning-dialog.html',
      windowClass: 'modal-danger'
    });
  });

  $scope.$on('IdleEnd', function() {
    closeModals();
  });

  $scope.$on('IdleTimeout', function() {
    closeModals();
    $scope.timedout = $uibModal.open({
      templateUrl: 'timedout-dialog.html',
      windowClass: 'modal-danger'
    });
    localStorage.clear();
    $window.location.href = 'login.php';
  });

  $scope.start = function() {
    closeModals();
    Idle.watch();
    $scope.started = true;
    // console.log("Nag start na.");
  };

  $scope.start();

  $scope.stop = function() {
    closeModals();
    Idle.unwatch();
    $scope.started = false;

  };
    
});




