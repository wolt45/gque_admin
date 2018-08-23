var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider) {
    
    $urlRouterProvider.otherwise('/calendar');
    
    $stateProvider

        .state('calendar', {
            url: '/calendar',
            templateUrl: 'calendarContent.php',
            controller: 'calendarCtrl'

        })
        
});




