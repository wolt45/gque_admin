gmmrApp.controller('PXDetailCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, $filter, $window, dbServices){

  $scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID");
  $scope.userTypeRID = localStorage.getItem("gmmrCentraluserTypeRID");

 
  $scope.checkAuth = function()
  {
    if ($scope.userPxRID == '' || $scope.userPxRID == null) {
      $window.location.href = '../index.php';
    }else{
      // console.log("Stay here!");
    }
  };

  $scope.checkAuth();


  $scope.Reload = function()
  {
    location.reload();
  };



  $scope.LoadUserProfile = function () {

    dbServices.getUserProfile($scope.userPxRID)
    .then(function success(response) {
      $scope.userItem = response.data;
    });
  };

  $scope.LoadUserProfile();




  $scope.logout = function(){
    localStorage.clear();
  };

  $scope.toRBGReg = function(){
    $window.location.href = '../../rbgregv3/pages';
  };

  $scope.toRBGGenMed = function(){
    $window.location.href = '../../rbg_genmed';
  };

  $scope.toGMMR2 = function(){
    $window.location.href = '../../gmmr2';
  };

  $scope.toGMMR3 = function(){
    $window.location.href = '../../gmmr3/pages';
  };

  $scope.toDiagnostix = function(){
    $window.location.href = '../../diagnostix/www';
  };

  $scope.toMedManager = function(){
    $window.location.href = '../../SoftMedlib/MedLibMain.php';
  };

  $scope.toBulkUploader = function(){
    $window.location.href = '../../SoftMedlib';
  };

  $scope.toICD10 = function(){
    $window.location.href = '../../icd10/';
  };

  $scope.toPhilRVS = function(){
    $window.location.href = '../../PhilRVS/';
  };

  $scope.toBillMgr = function(){
    $window.location.href = '../../billingmgr/';
  };

  $scope.toBackUp = function(){
    // $window.location.href = '../../billingmgr/';
  };


});




