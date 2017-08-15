(function(){

//    'use strict';

    var app = angular.module('System.Controllers');

    //The App Controller
    app.controller('EmailrequestController', [ '$window' , '$stateParams' , '$scope' , '$location', '$http' , 'User' , 'Store' , 'Role' , 'Page' , 'Loader'  , function( $window , $stateParams , $scope , $location , $http , User , Store , Role , Page , Loader ){
        User.ready(function(){
            Page.loading.start();
            if( Page.is( /^\/admin\/email-request/ ) && User.hasPermission([ 'emailrequest' ])  ){
                Store.all(function( stores ){

                    Role.all(function( roles ){
                        $scope.sending              = false;
                        $scope.timer                = null;
                        $scope.title                = 'Request an email';
                        $scope.button               = 'Send';
                        $scope.hasError             = Page.hasError;
                        $scope.errors               = [];

                        $scope.data                 = {
                            type:           "0",
                            message:        ""
                        };

                        $scope.submit     = function( form ){
                            if( form.$valid && !$scope.sending ){

                                $scope.sending = true;

                                $http.put( ':api/emailrequest' , $scope.data ).then(function( response ){
                                    if( !response.data.result ){

                                        $scope.sending     = false;
                                        $scope.success     = '';
                                        $scope.errors     = response.data.errors;

                                        $window.scrollTo(0,0);

                                    }else{

                                        form.$setPristine();

                                        $scope.sending     = false;
                                        $scope.errors     = [];
                                        $scope.data     = { 'type':"0", 'message':"" };
                                        $scope.success     = 'The email request has been Sent';            

                                        $window.scrollTo(0,0);

                                    }
                                });

                            }
                        };

                        Page.loading.end();                           
                    })
                })
                
 
            }
            
        });
    }]);


})();