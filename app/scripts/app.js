'use strict';

angular.module('bookMyClassApp', ['ui.router','ngResource','ngAnimate','ui.bootstrap'])
.config(function($stateProvider, $urlRouterProvider) {
        $stateProvider
        
            // route for the home page
            .state('app', {
                url:'/',
                views: {
                    'header': {
                        templateUrl : 'views/header.html',
                        controller : 'HeaderController'
                    },
                    'content': {
                        templateUrl : 'views/myBookings.html',
                        controller  : 'MyBookController'
                    },
                    'footer': {
                        templateUrl : 'views/footer.html',
                    }
                }
            })
            // route for the aboutus page
            .state('app.aboutus', {
                url:'aboutus',
                views: {
                    'content@': {
                        templateUrl : 'views/aboutUs.html',
                        controller  : 'AboutController'                  
                    }
                }
            })
            .state('app.week', {
                url:'week',
                views: {
                    'content@': {
                        templateUrl : 'views/week.html',
                        controller  : 'WeekController'                  
                    }
                }
            })
            .state('app.availability', {
                url:'availability/:id',
                views: {
                    'content@': {
                        templateUrl : 'views/availability.html',
                        controller  : 'AvailabilityController'                  
                    }
                }
            });
        $urlRouterProvider.otherwise('/');
    })
;