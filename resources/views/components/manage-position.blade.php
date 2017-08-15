    
        <!-- components.manage-position form -->
        <form name="positionForm" method="post" ng-submit="submit( positionForm )" novalidate>

            <!-- components.manage-position success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && positionForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-position error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( positionForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Position Name Error -->
                        <li ng-show="positionForm.error.name = ( positionForm.name.$dirty && positionForm.name.$invalid )" ng-messages="positionForm.name.$error">
                            <span ng-message="required">Name is required</span>
                            <span ng-message="uniquePosition">The Position Name already Exists</span>
                        </li>

                        <!-- Position Description Error -->
                        <li ng-show="positionForm.error.description = ( positionForm.description.$dirty && positionForm.description.$invalid )" ng-messages="positionForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-position success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Position Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : positionForm.name.$invalid && positionForm.name.$dirty , 'has-success' : !positionForm.name.$invalid && positionForm.name.$dirty }">
                        <label class="hide" for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Name" ng-model="position.name" ng-value="position.name" required unique-position="position.id" is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="positionForm.name.$invalid && positionForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!positionForm.name.$invalid && positionForm.name.$dirty"></span>
                    </div>

                    <!-- Position Description -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : positionForm.description.$invalid && positionForm.description.$dirty , 'has-success' : !positionForm.description.$invalid && positionForm.description.$dirty }">
                        <label class="hide" for="description">Description:</label>
                        <textarea type="text" id="description" name="description" placeholder="Description" ng-model="position.description" ng-value="position.description" is-empty /></textarea>
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="positionForm.description.$invalid && positionForm.description.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!positionForm.description.$invalid && positionForm.description.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/admin/positions(/[0-9]+)?' , '/admin/positions')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                        
                            <!-- Form Error Notice -->
                            <small class="colorred submitnotice" ng-show="hasError( positionForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} position - Please wait.
                            </small>
    
                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( positionForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="positionForm.$invalid || sending">
                                <i class="fa fa-@{{ icon }}"></i>
                                @{{ button }}
                            </button>

                        <!-- /End .fright -->
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>
                
            </main>

        </form>

