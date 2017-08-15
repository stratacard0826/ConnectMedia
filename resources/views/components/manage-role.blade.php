    
        <!-- components.manage-role form -->
        <form name="roleForm" method="post" ng-submit="submit( roleForm )" novalidate>

            <!-- components.manage-role success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && roleForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-role error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( roleForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Role Name Error -->
                        <li ng-show="roleForm.error.name = ( roleForm.name.$dirty && roleForm.name.$invalid )" ng-messages="roleForm.name.$error">
                            <span ng-message="required">Name is required</span>
                            <span ng-message="uniqueRole">The Role Name already Exists</span>
                        </li>

                        <!-- Role Description Error -->
                        <li ng-show="roleForm.error.description = ( roleForm.description.$dirty && roleForm.description.$invalid )" ng-messages="roleForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-role success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Role Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : roleForm.name.$invalid && roleForm.name.$dirty , 'has-success' : !roleForm.name.$invalid && roleForm.name.$dirty }">
                        <label class="hide" for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Name" ng-model="role.name" ng-value="role.name" required unique-role="role.id" is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="roleForm.name.$invalid && roleForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!roleForm.name.$invalid && roleForm.name.$dirty"></span>
                    </div>

                    <!-- Role Description -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : roleForm.description.$invalid && roleForm.description.$dirty , 'has-success' : !roleForm.description.$invalid && roleForm.description.$dirty }">
                        <label class="hide" for="description">Description:</label>
                        <textarea type="text" id="description" name="description" placeholder="Description" ng-model="role.description" ng-value="role.description" required is-empty /></textarea>
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="roleForm.description.$invalid && roleForm.description.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!roleForm.description.$invalid && roleForm.description.$dirty"></span>
                    </div>

                    <!-- Role Permissions -->
                    <div class="form-group" ng-if="permissions && role.id != 1" ng-class="{ 'has-success' : !roleForm.permissions.$invalid && roleForm.permissions.$dirty }">
                        <label class="hide" for="permissions">permissions:</label>
                        <multiselect name="permissions" ng-model="role.permissions" ng-value="role.permissions" options="permissions.data" selected-model="role.permissions" extra-settings="permissions.settings" events="permissions.events" translation-texts="{ buttonDefaultText: 'Permissions' }" ng-dropdown-multiselect></multiselect>
                        <i class="fa fa-unlock color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!roleForm.permissions.$invalid && roleForm.permissions.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/admin/roles(/[0-9]+)?' , '/admin/roles')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                        
                            <!-- Form Error Notice -->
                            <small class="colorred submitnotice" ng-show="hasError( roleForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} role - Please wait.
                            </small>
    
                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( roleForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="roleForm.$invalid || sending">
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

