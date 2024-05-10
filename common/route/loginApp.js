var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    $locationProvider.hashPrefix('');

    $urlRouterProvider.otherwise('/login');

    $stateProvider

    .state('login', {
        url: '/login'
        , templateUrl: 'pages/login.php'
        , controller: 'loginCtrl'
    })

    .state('register', {
        url: '/register'
        , templateUrl: 'pages/register.php'
        , controller: 'registerCtrl'
    })


});
