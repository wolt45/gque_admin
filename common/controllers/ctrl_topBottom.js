gmmrApp.controller('topBottomCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $sce, $window, $timeout, ngToast, $anchorScroll, dbServices){

  $scope.gotoTop = function() {
    // set the location.hash to the id of
    // the element you wish to scroll to.
    $location.hash('top');

    // call $anchorScroll()
    $anchorScroll();
  };


  $scope.gotoBottom = function() {
    // set the location.hash to the id of
    // the element you wish to scroll to.
    $location.hash('bottom');

    // call $anchorScroll()
    $anchorScroll();
  };

});




