var gmmrApp = angular.module('gmmrApp', ['ui.router', 'ngIdle', 'ui.bootstrap']);

gmmrApp.config(function($stateProvider, $urlRouterProvider, IdleProvider, KeepaliveProvider, $locationProvider) {
    $locationProvider.hashPrefix('');

    IdleProvider.idle(60000);
    IdleProvider.timeout(5);
    KeepaliveProvider.interval(70000);

    $urlRouterProvider.otherwise('/dashboard');

    $stateProvider

    .state('orDisinfectCheckList', {
        url: '/orDisinfectCheckList'
        , templateUrl: 'pages/orDisinfectCheckList.php'
        , controller: 'orDisinfectCheckListCtrl'
    })

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

    .state('inboxPage', {
        url: '/inboxPage'
        , templateUrl: 'pages/inboxPage.php'
        , controller: 'inboxPageCtrl'
    })

    .state('profile', {
        url: '/profile'
        , templateUrl: 'pages/profile.php'
        , controller: 'profileCtrl'
    })


});

//use in auto logout/idle
gmmrApp.run(['Idle', function(Idle) {
  Idle.watch();
}]);


gmmrApp.filter('beginning_data', function() {
    return function(input, begin) {
        if (input) {
            begin = +begin;
            return input.slice(begin);
        }
        return [];
    }
});
