
    <main id="dashboard">

        <!-- Row 1 -->
        <div class="row">

            <!-- Company News -->
            <section id="news" class="col-lg-6 border bgfff" ng-controller="NewsDashboardController as news" ng-if="hasPermission('news.view')">
                <div class="panel panel-default">
                    <header class="bg000 colorfff font2 panel-heading">
                        
                        <i class="fa fa-bell-o"></i>
                        Company News

                        <a ng-click="open('/news')" class="fright colorfff hovercolor999 btn-sm btn-primary font2" ng-show="hasPermission('news')">
                            See All News
                            <i class="fa fa-arrow-circle-o-right"></i>
                        </a>

                    </header>
                    <div class="body panel-body clearfix" data-scroll-box>

                        <ol ng-show="list.length > 0">
                            <li ng-repeat="article in list">
                                <article class="clearfix">
                                    <!--<div class="col15 fleft center">
                                        <img src="/public/assets/images/logo-no-bg-inverted.png" height="43" />
                                    </div>-->
                                    <div class="col85 fleft">
                                        <h3 class="font2 color000">@{{ article.subject }}</h3>
                                        <p>@{{ article.summary }}</p>
                                    </div>
                                </article>
                            </li>
                        </ol>
                
                        <!-- Show No Notificatiosn -->
                        <p ng-show="list.length == 0" class="text-center none">
                            No Updates Yet!
                        </p>

                        <!-- Show Loading -->
                        <div ng-show="loading" class="text-center font2 color000">
                            <i class="fa fa-circle-o-notch fa-spin color000"></i>
                            <span>Loading</span>
                        </div>
                        
                        <!-- Load More -->
                        <a class="text-center font2 colorfff btn-sm btn-primary fright loadmore" ng-click="load()" ng-show="showLoadBtn">
                            Load More
                        </a>

                    </div>
                </div>
            </section>


            <!-- Recent Updates -->
            <section id="updates" class="col-lg-6 border block bgfff" ng-controller="NotificationsController as notification" ng-init="setup({ limit:10 })">
    	        <div class="panel panel-default">
                    <header class="bg000 colorfff font2 panel-heading">
                        
                        <i class="fa fa-exclamation-triangle"></i>
                        Recent Updates

        	        </header>
                    <div class="body panel-body clearfix" data-scroll-box in-view-container>

                        <!-- Show Notifications -->
            	        <ol ng-show="list.length > 0">
                            <li ng-repeat="item in list" data-notification-id="@{{ item.id }}">
                                <a ng-click="open( item.url )">
                                    <span>
                                        <i class="fa fa-@{{ item.icon }}"></i>
                                        @{{ item.type }}:
                                        <strong>@{{ item.details }}</strong>
                                    </span>
                                    <small class="colorred" ng-if="item.users.length == 0" in-view="$inview && read( item )" in-view-options="{ debounce:1000 }">new</small>
                                </a>
                            </li>
            	        </ol>
                
                        <!-- Show No Notificatiosn -->
                        <p ng-show="list.length == 0" class="text-center none">
                            No Updates Yet!
                        </p>

                        <!-- Show Loading -->
                        <div ng-show="loading" class="text-center font2 color000">
                            <i class="fa fa-circle-o-notch fa-spin color000"></i>
                            <span>Loading</span>
                        </div>
                        
                        <!-- Load More -->
                        <a class="text-center font2 colorfff btn-sm btn-primary fright loadmore" ng-click="load()" ng-show="showLoadBtn">
                            Load More
                        </a>

                    </div>
                </div>
            </section>

        </div>

        <!-- Row 2 -->
        <div class="row">

            <!-- Locations -->
            <section id="map" class="col-lg-6" ng-controller="MapController as stores">
            	<div class="panel panel-default">
                    <header class="bg000 colorfff font2 panel-heading">
                        
                        <i class="fa fa-map-marker"></i>
                        Store Locations

                    </header>
                    <div class="body panel-body clearfix">
                        <div ng-show="stores.length > 0 && !loading">
                            <map></map>
                            <div class="list" ng-show="reports.length > 0 && !loading">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-left font2 color000">Top Performing Stores</th>
                                            <th class="text-center" width="15"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="report in reports">
                                            <td>@{{ report.store.name }}</td>
                                            <td>
                                                <a ng-click="link( ':api/download/' + report.file.slug )" class="color000 hovercolor000" ng-show="report.file">
                                                    <i class="fa fa-file" class="color000"></i>
                                                </a>
                                                <i class="fa fa-file coloraaa" ng-show="!report.file" disabled></i>
                                            </td> 
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Show Loading -->
                    <div ng-show="loading" class="text-center font2 color000">
                        <i class="fa fa-circle-o-notch fa-spin color000"></i>
                        <span>Loading</span>
                    </div>
                
                    <!-- Show No Stores -->
                    <p ng-show="stores.length == 0" class="text-center none">
                        No Stores Yet!
                    </p>

                </div>
            </section>


            <!-- Calendar-->
            <section id="calendar" class="col-lg-6 border block" ng-controller="CalendarController as calendar">
                <div class="panel panel-default">
        	        <header class="bg000 colorfff font2 panel-heading">
                        
                        <i class="fa fa-calendar"></i>
                        Calendar

                        <a ng-click="open('/admin/events')" class="fright colorfff hovercolor999 btn-sm btn-primary font2" ng-show="hasPermission('events')">
                            See All Events
                            <i class="fa fa-arrow-circle-o-right"></i>
                        </a>

        	        </header>
                    <div class="body panel-body clearfix">

                        <div calendar ng-model="events" class="span8 calendar"></div> 

                    </div>
                </div>
    	    </section>

        </div>


    </main>