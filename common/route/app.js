var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/dashboard');

    $stateProvider


    .state('dashboard', {
        url: '/dashboard'
        , templateUrl: 'pages/dashboard.php'
        , controller: 'dashboardCtrl'
    })

    .state('requestForModAlter', {
        url: '/requestForModAlter'
        , templateUrl: 'pages/requestForModAlter.php'
        , controller: 'requestForModAlterCtrl'
    })


});
