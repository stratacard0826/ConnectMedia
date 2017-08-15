
        <!-- components.manage-doctor form -->
        <form name="doctorForm" method="post" class="panel-body" ng-submit="submit( doctorForm )" novalidate>

            <!-- components.manage-doctor success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && doctorForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-doctor error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( doctorForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>
                        
                        <!-- Preset Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>
                        
                        <!-- Firstname -->
                        <li ng-show="doctorForm.error.firstname = ( doctorForm.firstname.$dirty && doctorForm.firstname.$invalid )" ng-messages="doctorForm.firstname.$error">
                            <span ng-message="required">Firstname is required</span>
                        </li>
                        
                        <!-- Lastname -->
                        <li ng-show="doctorForm.error.lastname = ( doctorForm.lastname.$dirty && doctorForm.lastname.$invalid )" ng-messages="doctorForm.lastname.$error">
                            <span ng-message="required">Lastname is required</span>
                        </li>
                        
                        <!-- Address -->
                        <li ng-show="doctorForm.error.address = ( doctorForm.address.$dirty && doctorForm.address.$invalid )" ng-messages="doctorForm.address.$error">
                            <span ng-message="required">Address is required</span>
                        </li>
                        
                        <!-- City -->
                        <li ng-show="doctorForm.error.city = ( doctorForm.city.$dirty && doctorForm.city.$invalid )" ng-messages="doctorForm.city.$error">
                            <span ng-message="required">City is required</span>
                        </li>
                        
                        <!-- Province -->
                        <li ng-show="doctorForm.error.province = ( doctorForm.province.$dirty && doctorForm.province.$invalid )" ng-messages="doctorForm.province.$error">
                            <span ng-message="required">Province is required</span>
                        </li>
                        
                        <!-- Postal Code -->
                        <li ng-show="doctorForm.error.postalcode = ( doctorForm.postalcode.$dirty && doctorForm.postalcode.$invalid )" ng-messages="doctorForm.postalcode.$error">
                            <span ng-message="uniquePostal Code">That Postal Code already exists</span>
                            <span ng-message="pattern">You have entered an invalid Postal Code</span>
                        </li>

                        <!-- Phone Number -->
                        <li ng-show="doctorForm.error.phone = ( doctorForm.phone.$dirty && doctorForm.phone.$invalid )" ng-messages="doctorForm.phone.$error">
                            <span ng-message="phonenumber">You've entered an invalid phone number</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- components.manage-doctor success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Firstame -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : doctorForm.firstname.$invalid && doctorForm.firstname.$dirty , 'has-success' : !doctorForm.firstname.$invalid && doctorForm.firstname.$dirty }">
                        <label class="hide" for="firstname">Firstname:</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Firstname" ng-model="doctor.firstname" ng-value="doctor.firstname" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="doctorForm.firstname.$invalid && doctorForm.firstname.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.firstname.$invalid && doctorForm.firstname.$dirty"></span>
                    </div>

                    <!-- Lastname -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : doctorForm.lastname.$invalid && doctorForm.lastname.$dirty , 'has-success' : !doctorForm.lastname.$invalid && doctorForm.lastname.$dirty }">
                        <label class="hide" for="lastname">Lastname:</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Lastname" ng-model="doctor.lastname" ng-value="doctor.lastname" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="doctorForm.lastname.$invalid && doctorForm.lastname.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.lastname.$invalid && doctorForm.lastname.$dirty"></span>
                    </div>

                    <!-- Email -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : doctorForm.email.$invalid && doctorForm.email.$dirty , 'has-success' : !doctorForm.email.$invalid && doctorForm.email.$dirty }">
                        <label class="hide" for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" ng-value="doctor.email" ng-model="doctor.email" unique-doctor-email="doctor.id" required />
                        <i class="fa fa-envelope color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="doctorForm.email.$invalid && doctorForm.email.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.email.$invalid && doctorForm.email.$dirty"></span>
                    </div>

                    <!-- Address -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : doctorForm.address.$invalid && doctorForm.address.$dirty , 'has-success' : !doctorForm.address.$invalid && doctorForm.address.$dirty }">
                        <label class="hide" for="address">Address:</label>
                        <input type="text" id="address" name="address" placeholder="Address" ng-model="doctor.address" ng-value="doctor.address" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="doctorForm.address.$invalid && doctorForm.address.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.address.$invalid && doctorForm.address.$dirty"></span>
                    </div>

                    <!-- City -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : doctorForm.city.$dirty && !doctorForm.city.$empty  }">
                        <label class="hide" for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="City" ng-value="doctor.city" ng-model="doctor.city" required is-empty />
                        <i class="fa fa-building color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="doctorForm.city.$dirty && !doctorForm.city.$empty"></span>
                    </div>

                    <!-- Province -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : doctorForm.province.$dirty && !doctorForm.province.$empty }">
                        <label class="hide" for="province">Province:</label>
                        <input type="text" id="province" name="province" placeholder="Province" ng-value="doctor.province" ng-model="doctor.province" required is-empty />
                        <i class="fa fa-map color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="doctorForm.province.$dirty && !doctorForm.province.$empty"></span>
                    </div>

                    <!-- Postal Code -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : doctorForm.postalcode.$invalid && doctorForm.postalcode.$dirty , 'has-success' : !doctorForm.postalcode.$invalid && doctorForm.postalcode.$dirty }">
                        <label class="hide" for="postalcode">Postal Code:</label>
                        <input type="text" id="postalcode" name="postalcode" placeholder="Postal Code" ng-value="doctor.postalcode" ng-model="doctor.postalcode" ng-pattern="/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/i" required />
                        <i class="fa fa-user color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="doctorForm.postalcode.$invalid && doctorForm.postalcode.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.postalcode.$invalid && doctorForm.postalcode.$dirty"></span>
                    </div>

                    <!-- Phone -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : !doctorForm.phone.$empty && doctorForm.phone.$invalid && doctorForm.phone.$dirty , 'has-success' : !doctorForm.phone.$empty && !doctorForm.phone.$invalid && doctorForm.phone.$dirty }">
                        <label class="hide" for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone" ng-value="doctor.phone" ng-model="doctor.phone" maxlength="14" phonenumber required is-empty />
                        <i class="fa fa-phone color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="!doctorForm.phone.$empty && doctorForm.phone.$invalid && doctorForm.phone.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!doctorForm.phone.$empty && !doctorForm.phone.$invalid && doctorForm.phone.$dirty"></span>
                    </div>


                    <div class="form-group">
                    
                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/medical/doctors(/[0-9]+)?' , '/medical/doctors')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>

                        <div class="fright">
                        
                            <!-- Form Error Notices -->
                            <small class="colorred submitnotice" ng-show="hasError( doctorForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} doctor - Please wait.
                            </small>

                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( doctorForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="doctorForm.$invalid || sending">
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

