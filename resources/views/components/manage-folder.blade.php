    
        <!-- components.manage-folder form -->
        <form name="folderForm" method="post" ng-submit="submit( folderForm )" novalidate>

            <!-- components.manage-folder success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && folderForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-folder error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( folderForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Folder Name Error -->
                        <li ng-show="folderForm.error.name = ( folderForm.name.$dirty && folderForm.name.$invalid )" ng-messages="folderForm.name.$error">
                            <span ng-message="required">Name is required</span>
                            <span ng-message="uniqueFolder">The Folder Name already Exists</span>
                        </li>

                        <!-- Folder Description Error -->
                        <li ng-show="folderForm.error.description = ( folderForm.description.$dirty && folderForm.description.$invalid )" ng-messages="folderForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-folder success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your Folder</p>

                    <!-- Parent Folder -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : folderForm.parent.$invalid && folderForm.parent.$dirty , 'has-success' : !folderForm.parent.$invalid && folderForm.parent.$dirty }">
                        <label class="hide" for="parent">Parent Folder:</label>
                        <div class="select">
                            <select id="parent" name="parent_id" ng-model="folder.parent_id" ng-options="item.id as item.tree for item in folders">
                                <option value="">No Parent Folder</option>
                            </select>
                        </div>
                        <i class="fa fa-folder color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="folderForm.parent.$invalid && folderForm.parent.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!folderForm.parent.$invalid && folderForm.parent.$dirty"></span>
                    </div>

                    <!-- Folder Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : folderForm.name.$invalid && folderForm.name.$dirty , 'has-success' : !folderForm.name.$invalid && folderForm.name.$dirty }">
                        <label class="hide" for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Name" ng-model="folder.name" ng-value="folder.name" required unique-folder="folder.id" is-empty />
                        <i class="fa fa-folder color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="folderForm.name.$invalid && folderForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!folderForm.name.$invalid && folderForm.name.$dirty"></span>
                    </div>

                    <!-- Folder Description -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : folderForm.description.$invalid && folderForm.description.$dirty , 'has-success' : !folderForm.description.$invalid && folderForm.description.$dirty }">
                        <label class="hide" for="description">Description:</label>
                        <textarea type="text" id="description" name="description" placeholder="Description" ng-model="folder.description" ng-value="folder.description" is-empty /></textarea>
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="folderForm.description.$invalid && folderForm.description.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!folderForm.description.$invalid && folderForm.description.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/documents/folders(/[0-9]+)?' , '/documents/folders')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                        
                            <!-- Form Error Notice -->
                            <small class="colorred submitnotice" ng-show="hasError( folderForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} folder - Please wait.
                            </small>
    
                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( folderForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="folderForm.$invalid || sending">
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

