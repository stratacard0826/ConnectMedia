
        <!-- components.manage-profile -->
        <form name="userForm" method="post" class="panel-body" ng-submit="submit( userForm )" novalidate>


            <!-- components.manage-profile success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && userForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-profile error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( userForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>
                    
                        <!-- Firstname Error -->
                        <li ng-show="userForm.error.firstname = ( userForm.firstname.$dirty && userForm.firstname.$invalid )" ng-messages="userForm.firstname.$error">
                            <span ng-message="required">Firstname is required</span>
                        </li>

                        <!-- Lastname Error -->
                        <li ng-show="userForm.error.lastname = ( userForm.lastname.$dirty && userForm.lastname.$invalid )" ng-messages="userForm.lastname.$error">
                            <span ng-message="required">Lastname is required</span>
                        </li>

                        <!-- Username Error -->
                        <li ng-show="userForm.error.username = ( userForm.username.$dirty && userForm.username.$invalid )" ng-messages="userForm.username.$error">
                            <span ng-message="uniqueUsername">That Username already exists</span>
                            <span ng-message="pattern">Usernames can only contain numbers, letters and underscores <small>( _ )</small></span>
                            <span ng-message="minlength, maxlength">Username must be between 3 and 50 characters</span>
                        </li>

                        <!-- Email Error -->
                        <li ng-show="userForm.error.email = ( userForm.email.$dirty && userForm.email.$invalid )" ng-messages="userForm.email.$error">
                            <span ng-message="required">Email is required</span>
                            <span ng-message="email">You've entered an invalid email address</span>
                            <span ng-message="uniqueEmail">That Email already exists</span>
                        </li>

                        <!-- Password Error -->
                        <li ng-show="userForm.error.password = ( userForm.password.$dirty && userForm.password.$invalid )" ng-messages="userForm.password.$error">
                            <span ng-message="required">Password is required</span>
                            <div ng-message="pattern">
                                Your Password must contain at least one of the three: 
                                <ul>
                                    <li>Uppercase Letter <small>(A-Z)</small></li>
                                    <li>Lowercase Letter <small>(a-z)</small></li>
                                    <li>Number <small>(0-9)</small></li>
                                    <li>Special Character <small>(!, $, #, %, etc.)</small></li>
                                </ul>
                            </div>
                            <span ng-message="minlength">You password must be at least 6 characters</span>
                        </li>

                        <!-- Password Confirm Error -->
                        <li ng-show="userForm.error.confirm = ( userForm.password_confirmation.$dirty && userForm.password_confirmation.$invalid )" ng-messages="userForm.password_confirmation.$error">
                            <span ng-message="compareTo">Password Confirmation does not match the Password</span>
                        </li>

                        <!-- Phone Error -->
                        <li ng-show="userForm.error.phone = ( userForm.phone.$dirty && userForm.phone.$invalid )" ng-messages="userForm.phone.$error">
                            <span ng-message="phonenumber">You've entered an invalid phone number</span>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-profile -->
            <main id="profile-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your User</p>

                    <!-- Firstname -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.firstname.$invalid && userForm.firstname.$dirty , 'has-success' : !userForm.firstname.$invalid && userForm.firstname.$dirty }">
                        <label class="hide" for="firstname">Firstname:</label>
                        <input type="text" id="firstname" name="firstname" placeholder="Firstname" ng-model="user.firstname" ng-value="user.firstname" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.firstname.$invalid && userForm.firstname.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.firstname.$invalid && userForm.firstname.$dirty"></span>
                    </div>

                    <!-- Lastname -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.lastname.$invalid && userForm.lastname.$dirty , 'has-success' : !userForm.lastname.$invalid && userForm.lastname.$dirty }">
                        <label class="hide" for="lastname">Lastname:</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Lastname" ng-model="user.lastname" ng-value="user.lastname" required is-empty />
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.lastname.$invalid && userForm.lastname.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.lastname.$invalid && userForm.lastname.$dirty"></span>
                    </div>

                    <!-- Username -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.username.$invalid && userForm.username.$dirty , 'has-success' : !userForm.username.$invalid && userForm.username.$dirty }">
                        <label class="hide" for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Username" ng-value="user.username" ng-model="user.username" unique-username="user.id" ng-pattern="/^[a-z0-9_]*$/i" ng-minlength="3" ng-maxlength="50" maxlength="50" />
                        <i class="fa fa-user color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.username.$invalid && userForm.username.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.username.$invalid && userForm.username.$dirty"></span>
                    </div>

                    <!-- Email -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.email.$invalid && userForm.email.$dirty , 'has-success' : !userForm.email.$invalid && userForm.email.$dirty }">
                        <label class="hide" for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Email" ng-value="user.email" ng-model="user.email" unique-email="user.id" required />
                        <i class="fa fa-envelope color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.email.$invalid && userForm.email.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.email.$invalid && userForm.email.$dirty"></span>
                    </div>

                    <!-- Password -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.password.$invalid && userForm.password.$dirty , 'has-success' : !userForm.password.$invalid && userForm.password.$dirty }">
                        <label class="hide" for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Password" ng-value="user.password" ng-model="user.password" ng-minlength="6" ng-pattern="/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/" ng-required="action == 'insert'" />
                        <i class="fa fa-lock color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.password.$invalid && userForm.password.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.password.$invalid && userForm.password.$dirty"></span>
                    </div>

                    <!-- Repeat Password -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : userForm.password_confirmation.$invalid && userForm.password_confirmation.$dirty , 'has-success' : !userForm.password_confirmation.$invalid && userForm.password_confirmation.$dirty }">
                        <label class="hide" for="password_confirmation">Confirm Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" ng-value="user.password_confirmation" ng-model="user.password_confirmation" compare-to="user.password" ng-min="6" ng-pattern="/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/" ng-required="action == 'insert'" />
                        <i class="fa fa-unlock color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="userForm.password_confirmation.$invalid && userForm.password_confirmation.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.password_confirmation.$invalid && userForm.password_confirmation.$dirty"></span>
                    </div>

                    <!-- City -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : userForm.city.$dirty && !userForm.city.$empty  }">
                        <label class="hide" for="city">City:</label>
                        <input type="text" id="city" name="city" placeholder="City" ng-value="user.city" ng-model="user.city" is-empty />
                        <i class="fa fa-building color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="userForm.city.$dirty && !userForm.city.$empty"></span>
                    </div>

                    <!-- Province -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : userForm.province.$dirty && !userForm.province.$empty }">
                        <label class="hide" for="province">Province:</label>
                        <input type="text" id="province" name="province" placeholder="Province" ng-value="user.province" ng-model="user.province" is-empty />
                        <i class="fa fa-map color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="userForm.province.$dirty && !userForm.province.$empty"></span>
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : !userForm.phone.$empty && userForm.phone.$invalid && userForm.phone.$dirty , 'has-success' : !userForm.phone.$empty && !userForm.phone.$invalid && userForm.phone.$dirty }">
                        <label class="hide" for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone" ng-value="user.phone" ng-model="user.phone" maxlength="14" phonenumber is-empty />
                        <i class="fa fa-phone color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="!userForm.phone.$empty && userForm.phone.$invalid && userForm.phone.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!userForm.phone.$empty && !userForm.phone.$invalid && userForm.phone.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <div class="fright">
                        
                            <!-- Form Error Notices -->
                            <small class="colorred submitnotice" ng-show="hasError( userForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} profile - Please wait.
                            </small>

                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( userForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="userForm.$invalid || sending">
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

