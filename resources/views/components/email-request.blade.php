<div class="border panel panel-default">
    <header class="font2 panel-heading ng-binding">
        <i class="fa fa-envelope"></i>&nbsp; Email Request
    </header>
    <div class="body clearfix panel-body">
        <form ng-submit="submit( emailrequestForm )"  name="emailrequestForm" method="post" novalidate>

            <!-- components.email-request success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && emailrequestForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.email-request error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( emailrequestForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>
                        
                        <!-- Preset Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                    </ul>
                </div>
            </div>

            <p>Type of Request:</p>
            <!-- Request type dropdown box -->
            <div class="form-group clearfix">
                <div class="select">
                    <select type="text" id="request_type" name="createevent" ng-model="data.type">
                        <option value="0">New Email Request</option>
                        <option value="1">Password Change Request</option>
                    </select>
                </div>
                <i class="fa fa-calendar color888 fleft"></i>
            </div>

            <p>Email:</p>
            <div class="form-group clearfix" ng-class="{ 'has-success' : !emailrequestForm.email.$invalid && emailrequestForm.email.$dirty }">
                <input type="email" id="email" name="email" placeholder="Email" ng-model="data.email" required>
                <i class="fa fa-envelope color888 fleft"></i>
                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!emailrequestForm.email.$invalid && emailrequestForm.email.$dirty"></span>
            </div>

            <p>Password:</p>
            <div class="form-group clearfix" ng-class="{ 'has-success' : !emailrequestForm.password.$invalid && emailrequestForm.password.$dirty }">
                <input type="password" id="password" name="password" placeholder="Password" ng-model="data.password" required>
                <i class="fa fa-lock color888 fleft"></i>
                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!emailrequestForm.password.$invalid && emailrequestForm.password.$dirty"></span>
            </div>

            <p>Message:</p>
            <div class="form-group clearfix" ng-class="{ 'has-success' : !emailrequestForm.message.$invalid && emailrequestForm.message.$dirty }">
                <label class="hide" for="message">Message</label>
                <textarea id="message" name="message" placeholder="Message" ng-model="data.message" required></textarea>
                <i class="fa fa-info-circle color888 fleft"></i>
                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!emailrequestForm.message.$invalid && emailrequestForm.message.$dirty"></span>
            </div>
            <div class="form-group">
                    

                <div class="fright">
                
                    <!-- Form Error Notices -->
                    <small class="colorred submitnotice" ng-show="hasError( emailrequestForm )">
                        You must fix any form errors before proceeding * See Errors at Top *
                    </small>
                    
                    <!-- Submitting Wait Notice -->
                    <small class="colorinfo submitnotice" ng-show="sending">
                        <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                        @{{ running }} product - Please wait.
                    </small>

                    <div>
                        <!-- Submit Button -->
                        <button name="submit" class="btn fright" ng-class="hasError( emailrequestForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="emailrequestForm.$invalid || sending">
                            @{{ button }}
                        </button>

                    </div>

                <!-- /End .fright -->
                </div>

            <!-- /End .form-group -->
            </div>
            
        </form>

    </div>
</div>
