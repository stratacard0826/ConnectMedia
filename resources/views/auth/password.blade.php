
        @extends('layouts.master')
        @section('title','Forgot Password')
        @section('id','pforgot')
        @section('content')

        <!-- auth/password.blade.php -->
        <section ng-controller="AuthorizationController as auth" ng-init="hasError( '' )">
            <img src="/public/assets/images/logo-no-bg.png" height="50" alt="{{ getenv('COMPANY') }}" />
            <form action="{{ action('Auth\PasswordController@postEmail') }}" method="post" name="resetForm" class="clearfix">
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
                        </ul>
                    </div>
                </div>

                <div class="form-group" ng-class="{ 'has-error' : resetForm.email.$invalid && resetForm.email.$dirty , 'has-success' : !resetForm.email.$invalid && resetForm.email.$dirty }">
                    <label class="hide" for="email">Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" aria-required placeholder="Email" required ng-model="email" />
                    <i class="fleft fa fa-envelope colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!resetForm.email.$invalid && resetForm.email.$dirty"></span>
                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="resetForm.email.$invalid && resetForm.email.$dirty"></span>
                </div>

                <div class="fright reset bg888 hoverbg000" ng-disabled="resetForm.$invalid">
                    <input type="submit" value="Reset Password" class="font2" ng-disabled="resetForm.$invalid" />
                    <i class="fright fa fa-arrow-circle-o-right colorfff" ng-disabled="resetForm.$invalid"></i>
                    {!! csrf_field() !!}
                </div>
            
            </form>
        </section>


        @stop