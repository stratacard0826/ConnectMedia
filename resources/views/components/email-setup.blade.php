<div class="border panel panel-default">
    <header class="font2 panel-heading ng-binding">
        <i class="fa fa-envelope"></i>&nbsp; Email Set-Up
    </header>
    <div class="body clearfix panel-body">
        
        <div id="email-app-container" >
            <h1 class="color000">Email Set-Up</h1>
            <p>To help setup your email app select an option below and follow the on going instructions.</p>
            <br><br>
            <div class="progress-container">
                <div class="progress-1 active">
                    <span class="circle br-circle br-done">1</span>
                    <div class="title">Select a Platform</div>
                </div>
                <div class="progress-2">
                    <span class="circle br-circle">2</span>
                    <div class="title">Select a Mail App</div>
                </div>
                <div class="progress-sidebar bar1"></div>
                <div class="progress-3">
                    <span class="circle br-circle">3</span>
                    <div class="title"><span id="device-name"></span> Instructions</div>
                </div>
                <div class="progress-sidebar bar2"></div>
                <div class="clearfix"></div>
            </div>
            <div class="row custom-container" id="platform-container">
                <div class="col-sm-6">
                    <div class="platform-holder" data-value="desktop">
                        <img src="/public/assets/images/emailsetup/desktop.png" alt=""><br>
                        <span>Desktop</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="platform-holder" data-value="mobile">
                        <img src="/public/assets/images/emailsetup/mobile.png" alt=""><br>
                        <span>Mobile & Tablet</span>
                    </div>
                </div>
            </div>
            <div class="row hideen custom-container" id="mailapp-container">
                <div class="col-sm-6">
                    <div class="platform-holder" data-value="windows-mail">
                        <img src="/public/assets/images/emailsetup/windows-mail.png" alt=""><br>
                        <span>Windows Live Mail</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="platform-holder" data-value="mac-mail">
                        <img src="/public/assets/images/emailsetup/mac-mail.png" alt=""><br>
                        <span>Mac Mail</span>
                    </div>
                </div>
            </div>
            <div class="row hideen custom-container" id="mobile-container">
                <div class="col-sm-4">
                    <div class="platform-holder" data-value="iphone">
                        <img src="/public/assets/images/emailsetup/iphone.png" alt=""><br>
                        <span>iPhone/iPad</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="platform-holder" data-value="android">
                        <img src="/public/assets/images/emailsetup/android.png" alt=""><br>
                        <span>Android</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="platform-holder" data-value="blackberry">
                        <img src="/public/assets/images/emailsetup/blackberry.png" alt=""><br>
                        <span>Blackberry</span>
                    </div>
                </div>
            </div>
            <div id="button-container" class="button-container">
                <span class="previous hideen"><i class="fa fa-angle-left"></i>&nbsp; PREVIOUS</span>
                <span class="next hideen">NEXT &nbsp;<i class="fa fa-angle-right"></i></span>
                <div class="clearfix"></div>
            </div>
            
            <div class="content-container hideen custom-container" id="mac-mail-content">
                <div class="banner-holder">
                    <img src="/public/assets/images/emailsetup/mac-banner.png" >
                </div>
                @include('_partials.emailsetup-mac-mail')
            </div>
            <div class="content-container hideen custom-container" id="windows-mail-content">
                <div class="banner-holder">
                    <img src="/public/assets/images/emailsetup/windows-banner.png" >
                </div>
                @include('_partials.emailsetup-windows-mail')
            </div>
            <div class="content-container hideen custom-container" id="android-content">
                <div class="banner-holder">
                    <img src="/public/assets/images/emailsetup/android-banner.png" >
                </div>
                @include('_partials.emailsetup-android')
            </div>
            <div class="content-container hideen custom-container" id="iphone-content">
                <div class="banner-holder">
                    <img src="/public/assets/images/emailsetup/iphone-banner.png" >
                </div>
                @include('_partials.emailsetup-iphone')
            </div>
            <div class="content-container hideen custom-container" id="blackberry-content">
                <div class="banner-holder">
                    <img src="/public/assets/images/emailsetup/blackberry-banner.png" >
                </div>
                @include('_partials.emailsetup-blackberry')
            </div>
        </div>        
        
    </div>
</div>
