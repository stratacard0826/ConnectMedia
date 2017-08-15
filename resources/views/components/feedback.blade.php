
    <!-- Search -->
    <form id="feedback" name="feedbackForm" ng-submit="sendFeedback( feedbackForm )" ng-init="feedback.type='Problem'">

        <nav class="col-md-3">
            <ul>
                <li ng-repeat="(index , type) in [['times', 'Problem'], ['exclamation', 'Suggestion'], ['check', 'Compliment'], ['question', 'Other']]" ng-class="{ 'active' : feedback.type == type[1] }" ng-click="feedback.type=type[1]">
                    <a ng-class="{ 'colorfff hovercolorfff' : feedback.type == type[1] , 'color555 hovercolor000' : feedback.type != type[1] }"> 
                        <span class="icon colorccc">
                            <i class="fa fa-@{{ type[0] }}-circle"></i>
                        </span>
                        <span class="text">
                            @{{ type[1] }}
                        </span>
                        <span class="arrow"></span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="col-md-9">    	

            <div class="form-group">                   

                <!-- Add Feedback -->
                <div class="form-group clearfix" ng-class="{ 'has-error' : feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty , 'has-success' : !feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty }">
                    <label class="hide" for="feedback_textarea">Enter the Feedback:</label>
                    <textarea id="feedback_textarea" name="feedback" placeholder="Enter the Feedback" ng-model="feedback.message" required></textarea>
                    <i class="fa fa-comment color888 fleft"></i>
                    <span class="glyphicon glyphicon-remove form-control-feedback push-right" ng-show="feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty"></span>
                    <span class="glyphicon glyphicon-ok form-control-feedback push-right" ng-show="!feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty"></span>
                </div>

            </div>


            <div class="form-group">                            

                <div class="fright">
                
                    <!-- Submitting Wait Notice -->
                    <small class="colorinfo text-right submitnotice" ng-show="sending">
                        <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                        Sending Feedback - Please wait.
                    </small>
                
                    <!-- Form Error Notices -->
                    <small class="colorred submitnotice" ng-show="hasError( feedbackForm )">
                        You must fix any form errors before proceeding
                    </small>

                    <!-- Submit Button -->
                    <button name="submit" class="btn fright" ng-class="hasError( feedbackForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="feedbackForm.$pristine || feedbackForm.$invalid || sending">
                        <i class="fa fa-paper-plane"></i>
                        Send Feedback
                    </button>

                <!-- /End .fright -->
                </div>

            <!-- /End .form-group -->
            </div>

        </div>

    </form>