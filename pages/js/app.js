var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider) {

    $urlRouterProvider.otherwise('/home');

    $stateProvider

    .state('aboutUs', {
        url: '/aboutUs',
        templateUrl: 'aboutUs.php'
    })

    .state('home', {
        url: '/home',
        templateUrl: 'home.php',
        controller: 'PXDetailCtrl'
    })

});