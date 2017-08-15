
        @extends('layouts.master')
        @section('title','Reset your Password')
        @section('id','preset')
        @section('content')

        <section ng-controller="AuthorizationController as auth">
            <img src="/public/assets/images/logo-no-bg.png" height="50" alt="{{ getenv('COMPANY') }}" />
            <form action="{{ action('Auth\PasswordController@postReset') }}" name="resetForm" method="post" class="clearfix" ng-submit="submit(resetForm.$valid)" novalidate>
                <h1 class="color000 font2">Reset your Password</h1>

                @if(Session::has('status'))
                    <div class="bs-callout bs-callout-info"> 
                        <h4 class="font2">Success!</h4> 
                        <p>{{ Session::get('status') }}</p>
                    </div>
                @endif

                <div class="bs-callout bs-callout-danger" ng-show="hasError( resetForm )"> 
                    <h4 class="font2">Form Errors</h4> 
                    <div>
                        <ul>
                            @if($errors->has())
                                <li ng-show="resetForm.error.system = 1">
                                    {{ $errors->first() }}
                                </li>
                            @endif
                            <li ng-show="resetForm.error.email = ( resetForm.email.$dirty && resetForm.email.$invalid )" ng-messages="resetForm.email.$error">
                                <span ng-message="required">Email is required</span>
                                <span ng-message="email">You've entered an invalid email address</span>
                            </li>
                            <li ng-show="resetForm.error.phone = ( resetForm.password_confirmation.$dirty && resetForm.password_confirmation.$invalid )" ng-messages="resetForm.password_confirmation.$error">
                                <span ng-message="compareTo">The Passwords Entered do not match</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="form-group clearfix" ng-class="{ 'has-error' : resetForm.email.$invalid && resetForm.email.$dirty , 'has-success' : !resetForm.email.$invalid && resetForm.email.$dirty }">
                    <label for="email" class="hide">Email Address:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" aria-required placeholder="Email" ng-model="email" required />
                    <i class="fleft fa fa-user colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!loginForm.email.$invalid && loginForm.email.$dirty"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="resetForm.email.$invalid && resetForm.email.$dirty"></span>
                </div>

                <div class="form-group clearfix" ng-class="{ 'has-error' : resetForm.password.$invalid && resetForm.password.$dirty , 'has-success' : !resetForm.password.$invalid && resetForm.password.$dirty }">
                    <label for="password" class="hide">Password:</label>
                    <input type="password" name="password" id="password" aria-required placeholder="New Password" ng-model="password" required />
                    <i class="fleft fa fa-lock colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!loginForm.password.$invalid && loginForm.password.$dirty"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="resetForm.password.$invalid && resetForm.password.$dirty"></span>
                </div>

                <div class="form-group clearfix" ng-class="{ 'has-error' : resetForm.password_confirmation.$invalid && resetForm.password_confirmation.$dirty , 'has-success' : !resetForm.password_confirmation.$invalid && resetForm.password_confirmation.$dirty }">
                    <label for="password_confirmation" class="hide">Password Confirmation:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" ng-model="password_confirmation" aria-required placeholder="Confirm New Password" compare-to="password" required />
                    <i class="fleft fa fa-lock colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!resetForm.password_confirmation.$invalid && resetForm.password_confirmation.$dirty"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="resetForm.password_confirmation.$invalid && resetForm.password_confirmation.$dirty"></span>
                </div>
                
                <div class="fright reset bg888 hoverbg000" ng-disabled="resetForm.$invalid">
                    <input type="submit" value="Reset Password" class="font2" ng-disabled="resetForm.$invalid" />
                    <i class="fright fa fa-arrow-circle-o-right colorfff" ng-disabled="resetForm.$invalid" ></i>
                    <input type="hidden" name="token" value="{{ $token }}" />
                    {!! csrf_field() !!}
                </div>
            
            </form>
        </section>

        @stop