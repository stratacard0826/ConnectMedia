
    <!-- Alerts -->
    <section id="alerts" data-show  ng-controller="NotificationsController as notification" ng-init="setup({ limit:5 });" ng-mouseover="visible = true" ng-mouseout="visible = false">
        <div class="showbtn color888">
            <span class="bgred colorfff rounded" ng-show="total > 0" ng-cloak>@{{ total }}</span>
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <div class="bgfff" data-hide data-scroll-box in-view-container>

            <!-- Show List -->
            <ol ng-show="list.length > 0">
                <li ng-repeat="item in list" data-notification-id="@{{ item.id }}">
                    <a ng-click="open( item.url )">
                        <span>
                            <i class="fa fa-@{{ item.icon }}"></i>
                            @{{ item.type }}:
                            <strong>@{{ item.details }}</strong>
                        </span>
                        <small class="colorred" ng-if="visible && item.users.length == 0" in-view="$inview && read( item )" in-view-options="{ debounce:500 }">new</small>
                    </a>
                </li>
            </ol>
            
            <!-- Show No Notificatiosn -->
            <p ng-show="list.length == 0 && !loading" class="text-center none">
                No Updates Yet!
            </p>
            
            <!-- Show Loading -->
            <div ng-show="loading" class="text-center font2 color000 loader">
                <i class="fa fa-circle-o-notch fa-spin color000"></i>
                <span>Loading</span>
            </div>
                        
            <!-- Load More -->
            <a class="text-center font2 colorfff btn-sm btn-primary fright loadmore" ng-click="load()" ng-show="showLoadBtn">
                Load More
            </a>

        </div>
    </section>