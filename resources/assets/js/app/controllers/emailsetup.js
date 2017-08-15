(function(){

//    'use strict';

    var app = angular.module('System.Controllers');

    //The App Controller
    app.controller('EmailsetupController', [ '$window' , '$stateParams' , '$scope' , '$location' , 'User' , 'Page' , 'Loader'  , function( $window , $stateParams , $scope , $location , User , Page , Loader ){
        User.ready(function(){
            if( Page.is( /^\/email-setup$/ ) ){
                /**
                *
                *    ROUTE: /profile
                *        - Edit the User Profile    
                *     
                *     Params (URL):
                *         - userid:         (INT) The User ID to Edit
                *
                **/

                var currentStep = 1, currentPlatform, currentDevice;
                
                /**
                * move to stepth page
                * 
                * @param step
                */
                function goToStep(step){
                    if(step == 1){
                        step1();
                    }else if(step == 2){
                        step2();
                    }else if(step == 3){
                        step3();
                    }
                }

                function step1(){
                    // hide all continers
                    jQuery(".custom-container").fadeOut("slow");
                    
                    // show platform container
                    jQuery("#platform-container").fadeIn("slow");
                    
                    /**
                    * active the first progress, inactive second and third progress
                    */
                    jQuery(".progress-1").addClass('active')
                    jQuery(".progress-2").removeClass('active')
                    jQuery(".progress-3").removeClass('active')
                    
                    /**
                    * hide previous button, show next button
                    */
                    jQuery("#button-container .previous").hide();
                    jQuery("#button-container .next").show();
                }

                function step2(){
                    // hide all containers
                    jQuery(".custom-container").fadeOut("slow");
                    if(currentPlatform == "desktop"){
                        
                        // if current selected platform is desktop, shows mail app container
                        jQuery("#mailapp-container").fadeIn("slow");

                        // change name of second progress
                        jQuery(".progress-2 .title").html("Select a Mail App");
                    }else if(currentPlatform == "mobile"){
                        
                        // if current selected platform is mobile, shows mobile container
                        jQuery("#mobile-container").fadeIn("slow");
                        
                        // change name of second progress
                        jQuery(".progress-2 .title").html("Select a Device");
                    }else{
                        return;
                    }

                    /**
                    * active the first and second progress, inactive third progress
                    */
                    jQuery(".progress-1").addClass('active')
                    jQuery(".progress-2").addClass('active')
                    jQuery(".progress-3").removeClass('active')
                    
                    /**
                    * show previous button, show next button
                    */
                    jQuery("#button-container .previous").show();
                    jQuery("#button-container .next").show();
                }

                function step3(){
                    
                    // hide all containers
                    jQuery(".custom-container").fadeOut("slow");
                    
                    // Can't move to third step without selecting the second step 
                    if(!currentDevice){
                        return false;
                    }
                    
                    var contentHtml = jQuery("#instruction-template .content").html();
                    
                    /**
                    * Shows content as current device or mail app
                    */
                    switch(currentDevice){
                        case "mac-mail":
                            jQuery("#mac-mail-content").fadeIn("slow");
                            jQuery(".progress-3 #device-name").html("Mac Mail");
                        break;
                        case "windows-mail":
                            jQuery("#windows-mail-content").fadeIn("slow");
                            jQuery(".progress-3 #device-name").html("Windows Mail");
                        break;
                        case "iphone":
                            jQuery("#iphone-content").fadeIn("slow");
                            jQuery(".progress-3 #device-name").html("iPhone / iPad");
                        break;
                        case "android":
                            jQuery("#android-content").fadeIn("slow");
                            jQuery(".progress-3 #device-name").html("Android");
                        break;
                        case "blackberry":
                            jQuery("#blackberry-content").fadeIn("slow");
                            jQuery(".progress-3 #device-name").html("Blackberry");
                        break;
                        default:
                            return;
                        break;
                    }
                    
                    /**
                    * Set active all steps
                    */
                    jQuery(".progress-1").addClass('active')
                    jQuery(".progress-2").addClass('active')
                    jQuery(".progress-3").addClass('active')
                    jQuery("#button-container .previous").hide();
                    jQuery("#button-container .next").hide();
                }

                jQuery(document).ready(function(){
                    
                    /**
                    * Event when click next button
                    */
                    jQuery('body').on("click", "#button-container .next", function(){
                        if(currentStep == 1){
                            currentStep++;
                            goToStep(currentStep);
                        }else if(currentStep == 2){
                            if(!currentDevice){
                                return false;
                            }
                            currentStep++;
                            goToStep(currentStep);
                        }
                    })

                    /**
                    * Event when click previous button
                    */
                    jQuery('body').on("click", "#button-container .previous", function(){
                        currentStep--;
                        goToStep(currentStep)
                    })
                    
                    /**
                    * Event when select a platform for all pages
                    */
                    jQuery('body').on('click', '.platform-holder', function(){
                        var parents = jQuery(this).parents('.row');
                        jQuery('.platform-holder', parents).removeClass('active');
                        jQuery(this).addClass('active');
                    })
                    
                    /**
                    * Event when select a platform in first page
                    */
                    jQuery('body').on('click', '#platform-container .platform-holder', function(){
                        currentPlatform = jQuery(this).data('value');
                        currentStep++;
                        goToStep(currentStep)
                    })
                    
                    /**
                    * Event when select a platform in second page
                    */
                    jQuery('body').on('click', '#mailapp-container .platform-holder, #mobile-container .platform-holder', function(){
                        currentDevice = jQuery(this).data('value');
                        currentStep++;
                        goToStep(currentStep)
                    })
                    
                    /**
                    * Event when select first progress button
                    */
                    jQuery('body').on('click', '.progress-1.active', function(){
                        currentStep = 1;
                        goToStep(currentStep)
                    })
                    
                    /**
                    * Event when select second progress button
                    */
                    jQuery('body').on('click', '.progress-2.active', function(){
                        currentStep = 2;
                        goToStep(currentStep)
                    })
                    
                    /**
                    * Event when select third progress button
                    */
                    jQuery('body').on('click', '.progress-3.active', function(){
                        currentStep = 3;
                        goToStep(currentStep)
                    })
                    
                });
                
                Page.loading.end();


            }


        });
    }]);


})();