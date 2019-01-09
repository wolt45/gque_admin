var gmmrApp = angular.module('gmmrApp', ['ui.router', 'ui.bootstrap']);

gmmrApp.config(function($stateProvider, $urlRouterProvider, $locationProvider) {
    $locationProvider.hashPrefix('');

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

gmmrApp.filter('beginning_data', function() {
    return function(input, begin) {
        if (input) {
            begin = +begin;
            return input.slice(begin);
        }
        return [];
    }
});
