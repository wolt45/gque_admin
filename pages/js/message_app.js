var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider) {
    
    $urlRouterProvider.otherwise('/message');
    
    $stateProvider

        .state('message', {
            url: '/message',
            templateUrl: 'calendarContent.php',
            controller: 'messageCtrl'

        })
        
});




