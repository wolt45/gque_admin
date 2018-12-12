gmmrApp.controller('dashboardCtrl', function ($scope, $stateParams, $rootScope, $location, $http, $window, $filter, $sce, dbServices){

	$scope.userPxRID = localStorage.getItem("gmmrCentraluserPxRID"); 

	$scope.loadScript = function (url){
      console.log('Javascript Loading...')
      let node = document.createElement('script');
      node.src = url;
      node.type = 'text/javascript';
      document.getElementsByTagName('head')[0].appendChild(node);   
    }
    $scope.loadScript('build/js/customCalendar.js');  
    
});




