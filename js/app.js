var gmmrApp = angular.module('gmmrApp', ['ui.router']);


gmmrApp.config(function($stateProvider, $urlRouterProvider) {
    
    $urlRouterProvider.otherwise('/login');
    
    $stateProvider
        
        
        .state('login', {
            url: '/login',
            templateUrl: 'login.php', 
            controller: 'LoginCtrl'
        })
        
});

