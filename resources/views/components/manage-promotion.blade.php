    
        <!-- components.manage-promotion form -->
        <form name="promotionForm" method="post" ng-submit="submit( promotionForm )" novalidate>

            <!-- components.manage-promotion success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && promotionForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-promotion error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( promotionForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Promotion Name Error -->
                        <li ng-show="promotionForm.error.name = ( promotionForm.name.$dirty && promotionForm.name.$invalid )" ng-messages="promotionForm.name.$error">
                            <span ng-message="required">Name is required</span>
                            <span ng-message="uniquePromotion">The Promotion Name already Exists</span>
                        </li>

                        <!-- Promotion Description Error -->
                        <li ng-show="promotionForm.error.description = ( promotionForm.description.$dirty && promotionForm.description.$invalid )" ng-messages="promotionForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-promotion success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Promotion Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : promotionForm.name.$invalid && promotionForm.name.$dirty , 'has-success' : !promotionForm.name.$invalid && promotionForm.name.$dirty }">
                        <label class="hide" for="name">Name:</label>
                        <input type="text" id="name" name="name" placeholder="Name" ng-model="promotion.name" ng-value="promotion.name" required unique-promotion="promotion.id" is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promotionForm.name.$invalid && promotionForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promotionForm.name.$invalid && promotionForm.name.$dirty"></span>
                    </div>

                    <!-- Promotion Description -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : promotionForm.description.$invalid && promotionForm.description.$dirty , 'has-success' : !promotionForm.description.$invalid && promotionForm.description.$dirty }">
                        <label class="hide" for="description">Description:</label>
                        <textarea type="text" id="description" name="description" placeholder="Description" ng-model="promotion.description" ng-value="promotion.description" required is-empty /></textarea>
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promotionForm.description.$invalid && promotionForm.description.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promotionForm.description.$invalid && promotionForm.description.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/admin/promotions(/[0-9]+)?' , '/admin/promotions')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                        
                            <!-- Form Error Notice -->
                            <small class="colorred submitnotice" ng-show="hasError( promotionForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} promotion - Please wait.
                            </small>
    
                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( promotionForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="promotionForm.$invalid || sending">
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

