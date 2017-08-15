
        @extends('layouts.master')
        @section('title','Unsupported Browser')
        @section('id','pbrowser')
        @section('content')

        <section ng-controller="AuthorizationController as auth">
            <img src="/public/assets/images/logo.png" alt="{{ getenv('COMPANY') }}" />
            <form action="{{ action('Auth\AuthController@getLogin') }}" method="post" name="loginForm" class="clearfix" ng-submit="submit(loginForm.$valid)" novalidate>
                <h1 class="color000 font2 text-center">Unsupported Browser</h1>

                <hr />

                <p>To continue you will need to update your web browser to the latest version. Click the link below to upgrade:</p>

                <hr />

                <div class="text-center">

                    <a href="http://browsehappy.com/" class="btn btn-primary text-left">
                        <i class="fa fa-eye"></i>
                        View Browser Options
                    </a>

                </div>

            </form>
        </section>

        @stop