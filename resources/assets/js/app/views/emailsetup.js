(function(){

    var app = angular.module('System.Views');

    app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){
        
        $stateProvider.state({

            name:             'email-setup',

            url:             '/email-setup',

            title:             'Email Setup',
            
            templateUrl:     ':api/layout/components/email-setup',
            
            controller:     'EmailsetupController',

            breadcrumbs:     [ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'envelope' , url: '/email-setup' , name: 'Email Setup' } ]
        
        })

    }]);


})();