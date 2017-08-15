
        <!-- components.manage-store form -->
        <form name="storeForm" method="post" class="panel-body" ng-submit="submit( storeForm )" novalidate>

            <!-- components.manage-store success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && storeForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-store error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( storeForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>
                        
                        <!-- Preset Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>
                        
                        <!-- Store Name -->
                        <li ng-show="storeForm.error.name = ( storeForm.name.$dirty && storeForm.name.$invalid )" ng-messages="storeForm.name.$error">
                            <span ng-message="required">Store Name is required</span>
                        </li>
                        
                        <!-- Address -->
                        <li ng-show="storeForm.error.address = ( storeForm.address.$dirty && storeForm.address.$invalid )" ng-messages="storeForm.address.$error">
                            <span ng-message="required">Address is required</span>
                        </li>
                        
                        <!-- City -->
                        <li ng-show="storeForm.error.city = ( storeForm.city.$dirty && storeForm.city.$invalid )" ng-messages="storeForm.city.$error">
                            <span ng-message="required">City is required</span>
                        </li>
                        
                        <!-- Province -->
                        <li ng-show="storeForm.error.province = ( storeForm.province.$dirty && storeForm.province.$invalid )" ng-messages="storeForm.province.$error">
                            <span ng-message="required">Province is required</span>
                        </li>
                        
                        <!-- Postal Code -->
                        <li ng-show="storeForm.error.postalcode = ( storeForm.postalcode.$dirty && storeForm.postalcode.$invalid )" ng-messages="storeForm.postalcode.$error">
                            <span ng-message="uniquePostal Code">That Postal Code already exists</span>
                            <span ng-message="pattern">You have entered an invalid Postal Code</span>
                        </li>

                        <!-- Phone Number -->
                        <li ng-show="storeForm.error.phone = ( storeForm.phone.$dirty && storeForm.phone.$invalid )" ng-messages="storeForm.phone.$error">
                            <span ng-message="phonenumber">You've entered an invalid phone number</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- components.manage-store success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Store Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : storeForm.name.$invalid && storeForm.name.$dirty , 'has-success' : !storeForm.name.$invalid && storeForm.name.$dirty }">
                        <label class="hide" for="name">Store Name:</label>
                        <input type="text" id="name" name="name" placeholder="Store Name" ng-model="store.name" ng-value="store.name" required unique-store-name is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="storeForm.name.$invalid && storeForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!storeForm.name.$invalid && storeForm.name.$dirty"></span>
                    </div>

                    <!-- Store Address -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : storeForm.address.$invalid && storeForm.address.$dirty , 'has-success' : !storeForm.address.$invalid && storeForm.address.$dirty }">
                        <label class="hide" for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="Address" ng-model="store.address" ng-value="store.address" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="storeForm.address.$invalid && storeForm.address.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!storeForm.address.$invalid && storeForm.address.$dirty"></span>
                    </div>

                    <!-- Store City -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : storeForm.city.$dirty && !storeForm.city.$empty  }">
                        <label class="hide" for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="City" ng-value="store.city" ng-model="store.city" required is-empty />
                        <i class="fa fa-building color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="storeForm.city.$dirty && !storeForm.city.$empty"></span>
                    </div>

                    <!-- Store Province -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : storeForm.province.$dirty && !storeForm.province.$empty }">
                        <label class="hide" for="province">Province:</label>
                        <input type="text" id="province" name="province" placeholder="Province" ng-value="store.province" ng-model="store.province" required is-empty />
                        <i class="fa fa-map color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="storeForm.province.$dirty && !storeForm.province.$empty"></span>
                    </div>

                    <!-- Store Postal Code -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : storeForm.postalcode.$invalid && storeForm.postalcode.$dirty , 'has-success' : !storeForm.postalcode.$invalid && storeForm.postalcode.$dirty }">
                        <label class="hide" for="postalcode">Postal Code:</label>
                        <input type="text" id="postalcode" name="postalcode" placeholder="Postal Code" ng-value="store.postalcode" ng-model="store.postalcode" ng-pattern="/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/i" required />
                        <i class="fa fa-user color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="storeForm.postalcode.$invalid && storeForm.postalcode.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!storeForm.postalcode.$invalid && storeForm.postalcode.$dirty"></span>
                    </div>

                    <!-- Store Phone -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : !storeForm.phone.$empty && storeForm.phone.$invalid && storeForm.phone.$dirty , 'has-success' : !storeForm.phone.$empty && !storeForm.phone.$invalid && storeForm.phone.$dirty }">
                        <label class="hide" for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone" ng-value="store.phone" ng-model="store.phone" maxlength="14" phonenumber required is-empty />
                        <i class="fa fa-phone color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="!storeForm.phone.$empty && storeForm.phone.$invalid && storeForm.phone.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!storeForm.phone.$empty && !storeForm.phone.$invalid && storeForm.phone.$dirty"></span>
                    </div>


                    <div class="form-group">
                    
                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/admin/stores(/[0-9]+)?' , '/admin/stores')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>

                        <div class="fright">
                        
                            <!-- Form Error Notices -->
                            <small class="colorred submitnotice" ng-show="hasError( storeForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} store - Please wait.
                            </small>

                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( storeForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="storeForm.$invalid || sending">
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

