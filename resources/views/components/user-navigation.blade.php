
    <!-- User Navigation -->
    <section id="account" ng-controller="UserNavigationController as navigation" data-show>
        <div class="showbtn color888 hoverbg444 hovercoloraaa">
            <img src="/public/assets/images/logo-no-bg.png" alt="@{{ getName() }}" width="29" />
            <span class="colorfff">
                <span class="normalweight name" ng-cloak>@{{ getName() }}</span>
                <i class="fa fa-chevron-down color999"></i>
            </span>
        </div>
        <nav class="bgfff" data-hide>
            <ul>
                <li>
                    <a ng-click="open('/email-setup')" class="hoverbgeee hovercolor555 color000">
                        <i class="fa fa-envelope"></i>
                        Email Setup
                    </a>
                </li>
                <li>
                    <a ng-click="open('/profile')" class="hoverbgeee hovercolor555 color000">
                        <i class="fa fa-user"></i>
                        Update Profile
                    </a>
                </li>
                <li>
                    <a href="{{ URL::to('auth/logout') }}" target="_self" class="hoverbgeee hovercolor555 color000">
                        <i class="fa fa-power-off"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </section>