
        @extends('layouts.master')

        @section('content')

        <!-- Navigation -->
        <aside id="sidebar" class="bg000">

            <!-- Logo -->
            <div class="logo">
                <a ng-click="open('/')">
                    <img src="/public/assets/images/logo-no-bg.png" height="50" alt="{{ getenv('COMPANY') }}" />
                </a>
            </div>

            <!-- components.search -->
            @include('components.search')

            <!-- components.navigation -->
            @include('components.navigation')

        </aside>

        <!-- Wrapper -->
        <div id="site">

            <!-- Header -->
            <header id="header" class="bg000 clearfix">

                <!-- MMenu Bars -->
                <a class="menu fleft hovercolor555 colr888">
                    <i class="fa fa-bars" data-menu-control></i>
                </a>

                <!-- Logo -->
                <div class="logo fleft">
                    <a ng-click="open('/')">
                        <img src="/public/assets/images/logo-no-bg.png" height="50" alt="{{ getenv('COMPANY') }}" />
                    </a>
                </div>

                <!-- Align Right -->
                <div class="fright">

                    <!-- components.alerts -->
                    @include('components.alerts')

                    <!-- components.user-navigation -->
                    @include('components.user-navigation')

                </div>

            </header>



            <!-- Content -->
            <div id="content" class="clearfix">
                
                <!-- Loading -->
                <div ng-show="loading" class="text-center font2 color000">
                    <i class="fa fa-circle-o-notch fa-spin color000" style="font-size:20px;margin-right:5px;"></i>
                    <span style="font-size:15px;">Loading</span>
                </div>

                <!-- Title -->
                <h1 class="color000 font2" ng-hide="loading" ng-cloak>@{{ get().title }}</h1>

                <!-- Breadcrumbs -->
                <ol class="breadcrumb" ng-hide="loading" ng-cloak>
                    <li ng-repeat="breadcrumb in get().breadcrumbs">
                        <i class="fa fa-@{{ breadcrumb.icon }}"></i>
                        <a ng-click="open(breadcrumb.url)" ng-if="!$last">@{{ breadcrumb.name }}</a>
                        <span ng-if="$last">@{{ breadcrumb.name }}</span>
                    </li>
                </ol>

                <!-- The Page -->
                <div ui-view ng-hide="loading"></div>

            </div>

            <!-- Spacer -->
            <div id="footer-spacer"></div>
        </div>



        <!-- Footer -->
        <footer id="footer" class="bg000">
            <p class="font1 color999">Built by <a href="http://www.wishmedia.ca" class="font1 color999 hovercolorfff" target="_blank">Wishmedia Ltd.</a> | {{ date('Y') }} &copy; {{ getenv('COMPANY') }}</p>
        </footer>

        @stop
