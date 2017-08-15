
    <!-- Navigation -->
    <nav id="navigation" ng-controller="NavigationController as navigation" ng-init="init()">
        <ul>
            <li ng-class="selected( '' , 'active drop' , 'bghover111' )" class="search">                
                <a class="font2 colorfff" ng-class="selected( '' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '' )">
                    <i class="fa fa-search colorfff"></i>
                    <span class="text">Search</span>
                    <span class="arrow"></span>
                </a>
            </li>
            <li ng-class="selected( '/' , 'active drop' , 'bghover111' )">
                <a class="font2 colorfff" ng-class="selected( '/' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/' )">
                    <i class="fa fa-home colorfff"></i>
                    <span class="text">Dashboard</span>
                    <span class="arrow"></span>
                </a>
            </li>
            <li ng-class="selected( '/admin*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('users') || hasPermission('roles') || hasPermission('positions') || hasPermission('stores') || hasPermission('events') || hasPermission('sickdays')">
                <a class="font2 colorfff" ng-class="selected( '/admin*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/admin/users' )">
                    <i class="fa fa-users colorfff"></i>
                    <span class="text">Administration</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/admin*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Administration</li>
                    <li class="font2" ng-class="selected( '/admin/users*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'users' )">
                        <a ng-click="open( '/admin/users' )" class="font2" ng-class="selected( '/admin/users*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-user"></i>
                            <span class="text">Users</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/roles*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'roles' )">
                        <a ng-click="open( '/admin/roles' )" class="font2" ng-class="selected( '/admin/roles*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-lock"></i>
                            <span class="text">Roles</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/positions*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'positions' )">
                        <a ng-click="open( '/admin/positions' )" class="font2" ng-class="selected( '/admin/positions*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-wrench"></i>
                            <span class="text">Positions</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/stores*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'stores' )">
                        <a ng-click="open( '/admin/stores' )" class="font2" ng-class="selected( '/admin/stores*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-map-marker"></i>
                            <span class="text">Stores</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/events*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'events' )">
                        <a ng-click="open( '/admin/events' )" class="font2" ng-class="selected( '/admin/events*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-calendar"></i>
                            <span class="text">Events</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/sickday*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'sickdays' )">
                        <a ng-click="open( '/admin/sickday' )" class="font2" ng-class="selected( '/admin/sickday*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-ambulance"></i>
                            <span class="text">Sick Days</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/admin/email-request*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'emailrequest' )">
                        <a ng-click="open( '/admin/email-request' )" class="font2" ng-class="selected( '/admin/email-request*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-envelope"></i>
                            <span class="text">Request an email</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/news*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('news')">
                <a class="font2 colorfff" ng-class="selected( '/news*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/news' )">
                    <i class="fa fa-bell-o colorfff"></i>
                    <span class="text">Company News</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/news*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Company News</li>
                    <li class="font2" ng-class="selected( '/news' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'news' )">
                        <a ng-click="open( '/news' )" class="font2" ng-class="selected( '/news' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-bell-o"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/news/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'news.create' )">
                        <a ng-click="open( '/news/add' )" class="font2" ng-class="selected( '/news/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Article</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/tech*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('tech')">
                <a class="font2 colorfff" ng-class="selected( '/tech*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/tech' )">
                    <i class="fa fa-cogs colorfff"></i>
                    <span class="text">Tech Talk</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/tech*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Tech Talk</li>
                    <li class="font2" ng-class="selected( '/tech' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'tech' )">
                        <a ng-click="open( '/tech' )" class="font2" ng-class="selected( '/tech' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-cogs"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/tech/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'tech.create' )">
                        <a ng-click="open( '/tech/add' )" class="font2" ng-class="selected( '/tech/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Product</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/promos*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('promos')">
                <a class="font2 colorfff" ng-class="selected( '/promos*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/promos' )">
                    <i class="fa fa-bar-chart colorfff"></i>
                    <span class="text">Marketing &amp; Promos</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/promos*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Marketing &amp; Promos</li>
                    <li class="font2" ng-class="selected( '/promos' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'promos' )">
                        <a ng-click="open( '/promos' )" class="font2" ng-class="selected( '/promos' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-bar-chart"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/promos/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'promos.create' )">
                        <a ng-click="open( '/promos/add' )" class="font2" ng-class="selected( '/promos/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Promotion</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/documents*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('documents')">
                <a class="font2 colorfff" ng-class="selected( '/documents*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/documents' )">
                    <i class="fa fa-folder-open colorfff"></i>
                    <span class="text">Documents</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/documents*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Documents</li>
                    <li class="font2" ng-class="selected( '/documents' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'documents' )">
                        <a ng-click="open( '/documents' )" class="font2" ng-class="selected( '/documents' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-folder-open"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/documents/folders' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'documents.manage' )">
                        <a ng-click="open( '/documents/folders' )" class="font2" ng-class="selected( '/documents/folders' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-folder"></i>
                            <span class="text">Manage Folders</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/documents/manage' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'documents.manage' )">
                        <a ng-click="open( '/documents/manage' )" class="font2" ng-class="selected( '/documents/manage' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Manage Documents</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/logouts*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('logouts')">
                <a class="font2 colorfff" ng-class="selected( '/logouts*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/logouts' )">
                    <i class="fa fa-sign-out colorfff"></i>
                    <span class="text">Daily Logouts</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/logouts*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Daily Logouts</li>
                    <li class="font2" ng-class="selected( '/logouts' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'logouts' )">
                        <a ng-click="open( '/logouts' )" class="font2" ng-class="selected( '/logouts' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-sign-out"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/logouts/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'logouts.create' )">
                        <a ng-click="open( '/logouts/add' )" class="font2" ng-class="selected( '/logouts/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Logout</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/logouts/report' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'logouts.report' )">
                        <a ng-click="open( '/logouts/report' )" class="font2" ng-class="selected( '/logouts/report' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-line-chart"></i>
                            <span class="text">View Report</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/buyers*' , 'active drop' , 'bghover111' )" ng-show="hasPermission( 'buyerslogouts' )">
                <a class="font2 colorfff" ng-class="selected( '/buyers*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/buyers' )">
                    <i class="fa fa-credit-card colorfff"></i>
                    <span class="text">Buyers Logout</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/buyers*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Buyer Logouts</li>
                    <li class="font2" ng-class="selected( '/buyers' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'buyerslogouts' )">
                        <a ng-click="open( '/buyers' )" class="font2" ng-class="selected( '/buyers' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-credit-card"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/buyers/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'buyerslogouts.create' )">
                        <a ng-click="open( '/buyers/add' )" class="font2" ng-class="selected( '/buyers/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Logout</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/buyers/report' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'buyerslogouts.report' )">
                        <a ng-click="open( '/buyers/report' )" class="font2" ng-class="selected( '/buyers/report' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-line-chart"></i>
                            <span class="text">View Report</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/medical*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('medical')">
                <a class="font2 colorfff" ng-class="selected( '/medical*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/medical' )">
                    <i class="fa fa-heartbeat colorfff"></i>
                    <span class="text">Medical Referrals</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/medical*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Medical Referral</li>
                    <li class="font2" ng-class="selected( '/medical' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'medical' )">
                        <a ng-click="open( '/medical' )" class="font2" ng-class="selected( '/medical' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-heartbeat"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/medical/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'medical.create' )">
                        <a ng-click="open( '/medical/add' )" class="font2" ng-class="selected( '/medical/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Referral</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/medical/doctors*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'medical.create' )">
                        <a ng-click="open( '/medical/doctors' )" class="font2" ng-class="selected( '/medical/doctors*' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-user-md"></i>
                            <span class="text">Doctors</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/payrolls*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('payrolls')">
                <a class="font2 colorfff" ng-class="selected( '/payrolls*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/payrolls' )">
                    <i class="fa fa-money colorfff"></i>
                    <span class="text">Payroll</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/payrolls*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Payroll</li>
                    <li class="font2" ng-class="selected( '/payrolls' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'payrolls' )">
                        <a ng-click="open( '/payrolls' )" class="font2" ng-class="selected( '/payrolls' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-money"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/payrolls/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'payrolls.create' )">
                        <a ng-click="open( '/payrolls/add' )" class="font2" ng-class="selected( '/payrolls/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Payroll</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '/reports*' , 'active drop' , 'bghover111' )" ng-show="hasPermission('reports')">
                <a class="font2 colorfff" ng-class="selected( '/reports*' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '/reports' )">
                    <i class="fa fa-area-chart colorfff"></i>
                    <span class="text">Weekly Report</span>
                    <span class="arrow"></span>
                </a>
                <ul class="color444 bg000">
                    <li class="font2 title" ng-class="selected( '/reports*' , 'bgred hoverbgred hovercolorfff colorfff' , '' )">Daily Logouts</li>
                    <li class="font2" ng-class="selected( '/reports' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'reports' )">
                        <a ng-click="open( '/reports' )" class="font2" ng-class="selected( '/reports' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-area-chart"></i>
                            <span class="text">View All</span>
                        </a>
                    </li>
                    <li class="font2" ng-class="selected( '/reports/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )" ng-show="hasPermission( 'reports.create' )">
                        <a ng-click="open( '/reports/add' )" class="font2" ng-class="selected( '/reports/add' , 'colorfff hovercolorfff' , 'color555 hovercolor888' )">
                            <i class="fa fa-plus-circle"></i>
                            <span class="text">Add Report</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li ng-class="selected( '' , 'active drop' , 'bghover111' )" ng-show="hasPermission('university')">
                <a class="font2 colorfff" ng-class="selected( '' , 'bgred hoverbgred hovercolorfff' , 'hovercolor555' )" ng-click="open( '' )">
                    <i class="fa fa-university colorfff"></i>
                    <span class="text">University</span>
                    <span class="arrow"></span>
                </a>
            </li>
        </ul>
    </nav>