
        @extends('layouts.master')
        @section('title','Login')
        @section('id','plogin')
        @section('content')

        <section ng-controller="AuthorizationController as auth">
            <img src="/public/assets/images/logo-no-bg.png" alt="{{ getenv('COMPANY') }}" height="50" />
            <form action="{{ action('Auth\AuthController@getLogin') }}" method="post" name="loginForm" class="clearfix" ng-submit="submit(loginForm.$valid)" novalidate>
                <h1 class="color000 font2">Login to your account</h1>

                @if($errors->has())
                    <div class="bs-callout bs-callout-danger"> 
                        <h4 class="font2">Form Errors</h4> 
                        <ul>
                            <li>{{ $errors->first() }}</li>
                        </ul>
                    </div>
                @endif

                <div class="form-group clearfix" ng-class="{ 'has-success' : !loginForm.login.$invalid && loginForm.login.$dirty }">
                    <label class="hide" for="login">Username / Login:</label>
                    <input type="text" name="login" value="{{ old('login') }}" aria-required placeholder="Username / Email" id="login" ng-model="login" required />
                    <i class="fleft fa fa-user colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!loginForm.login.$invalid && loginForm.login.$dirty"></span>
                </div>

                <div class="form-group clearfix" ng-class="{ 'has-success' : !loginForm.password.$invalid && loginForm.password.$dirty }">
                    <input type="password" name="password" value="" aria-required placeholder="Password" ng-model="password" required />
                    <i class="fleft fa fa-lock colorccc"></i>
                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!loginForm.password.$invalid && loginForm.password.$dirty"></span>
                    <div class="fleft">
                        <input type="checkbox" name="remember" id="remember" /> 
                        <label for="remember" class="color999 font2">Remember Me</label>               
                    </div>
                    <a href="{{ url('password/email') }}" target="_self" class="color999 font2 login fright">Forgot your Password?</a>
                </div>

                <div class="fright submit bg888 hoverbg000" ng-disabled="loginForm.$invalid">
                    <input type="submit" value="Login" class="font2" ng-disabled="loginForm.$invalid" />
                    <i class="fright fa fa-arrow-circle-o-right colorfff" ng-disabled="loginForm.$invalid" ></i>
                    {!! csrf_field() !!}
                </div>

            </form>
        </section>

        @stop