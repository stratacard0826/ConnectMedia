(function(){

    var app = angular.module('System.Views');

    app.config([ '$stateProvider' , '$urlRouterProvider' , function( $stateProvider , $urlRouterProvider ){
        
        $stateProvider.state({

            name:             'email-request',

            url:             '/admin/email-request',

            title:             'Email Request',
            
            templateUrl:     ':api/layout/components/email-request',
            
            controller:     'EmailrequestController',

            breadcrumbs:     [ { icon:'home' , url:'/', name: 'Dashboard' } , { icon:'envelope' , url: '/admin/email-request' , name: 'Email Request' } ]
        
        })

    }]);


})();