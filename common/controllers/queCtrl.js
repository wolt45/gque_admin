gmmrApp.controller('queCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $timeout, $filter, $sce, Idle, Keepalive, $uibModal, ngToast, $interval, dbServices, $state){

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


  $scope.$on('LOAD',function(){$scope.loading=true});
  $scope.$on('UNLOAD',function(){$scope.loading=false});

  $scope.$emit('LOAD');

  

  $interval(function(){
    $scope.getQues();
  },5000);



  $scope.getQues = function () {
    dbServices.getQues()
    .then(function success(response) {
      $scope.queOBJ = response.data;
      $scope.$emit('UNLOAD');
    });
  };
  $scope.getQues();


  // $scope.getQueStatus = function (stts) {
  //   if (stts = 0) $scope.QueStatus = "waiting";
  //   if (stts = 8) $scope.QueStatus = "<span style='color:red'>cancelled</span>";
  //   if (stts = 9) $scope.QueStatus = "<span style='color:blue'>done</span>";
  // };



  $scope.queAction = function (rid, stts) {
    if (confirm("Proceed? ")) {
      $scope.$emit('LOAD');
      dbServices.queAction(rid, stts)
      .then(function success(response) {
        $scope.$emit('UNLOAD');
        $scope.getQues();
      });
    }
  };


  $scope.queRESET = function (){
    if (confirm(" This will erase all your ques data and reset number back to 1, Proceed? ")) {
      $scope.$emit('LOAD');
      dbServices.queRESET()
      .then(function success(response) {
        $scope.$emit('UNLOAD');
        $scope.getQues();
      });
    }
  };

});