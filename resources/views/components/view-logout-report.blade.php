            
                        
            <!-- Logout Controls -->
            <form name="logoutForm" method="post" class="panel-body" novalidate>

                <!-- components.manage-logout error -->
                <div class="bs-callout bs-callout-danger" ng-show="hasError( logoutForm ) || errors.length"> 
                    <h4 class="font2">Form Errors</h4> 
                    <div>
                        <ul>

                            <!-- General Errors -->
                            <li ng-repeat="error in errors">
                                @{{ error }}
                            </li>

                        </ul>
                        <ul>

                            <!-- Event Start Time Error -->
                            <li ng-show="logoutForm.error.start = ( logoutForm.start.$dirty && logoutForm.start.$invalid )" ng-messages="logoutForm.start.$error">
                                <span ng-message="date-max">Start Time cannot start after the End Time</span>
                                <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                            </li>

                            <!-- Event End Time Error -->
                            <li ng-show="logoutForm.error.end = ( logoutForm.end.$dirty && logoutForm.end.$invalid )" ng-messages="logoutForm.end.$error">
                                <span ng-message="date-min">End Time cannot start before the Start Time</span>
                                <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                            </li>

                        </ul>
                    </div>
                </div>


                <!-- components.view-logout-report -->
                <main id="logout-report" class="border panel panel-default" ng-init="update( logoutForm )" ng-if="!loading">
                    <header class="font2 panel-heading">
                        
                        <i class="fa fa-@{{ icon }}"></i>
                        @{{ title }}

                    </header>
                    <article class="panel-body">

                        <div class="report-panel">

                            <p>Filter the Report by the fields below:</p>

                            <!-- Store -->
                            <div class="form-group clearfix">
                                <label class="hide" for="store_id">Store:</label>
                                <div class="select">
                                    <select type="text" id="store_id" name="store_id" ng-model="logout.store_id" ng-change="update( logoutForm )" ng-options="store.id as store.name for store in stores">
                                        <option value="">All Stores</option>
                                    </select>
                                </div>
                                <i class="fa fa-building color888 fleft"></i>
                            </div>

                            <!-- Start Date -->
                            <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="logout.calendars[0].open" ng-class="{ 'has-error' : logoutForm.start.$invalid && logoutForm.start.$dirty , 'has-success' : !logoutForm.start.$invalid && logoutForm.start.$dirty }">
                                <label class="hide" for="start">Start Date:</label>
                                <input type="datetime" id="start" name="start" ng-model="logout.start" placeholder="Start Date" date-compare date-max="logout.end" autocomplete="off" ng-change="update( logoutForm )" />
                                <i class="fa fa-clock-o color888 fleft"></i>
                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.start.$invalid && logoutForm.start.$dirty"></span>
                                <span class="input-group-btn">
                                    <button type="button" class="btn" ng-class="( logout.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                </span>
                                <div uib-dropdown-menu>
                                    <datetimepicker data-ng-model="logout.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day'  }" data-on-set-time="dateSelect( logoutForm , 'start' , 1 )"></datetimepicker>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="logout.calendars[1].open" ng-class="{ 'has-error' : logoutForm.end.$invalid && logoutForm.end.$dirty , 'has-success' : !logoutForm.end.$invalid && logoutForm.end.$dirty }">
                                <label class="hide" for="end">End Date:</label>
                                <input type="datetime" id="end" name="end" ng-model="logout.end" placeholder="End Date" date-compare date-min="logout.start" autocomplete="off" ng-change="update( logoutForm )" />
                                <i class="fa fa-clock-o color888 fleft"></i>
                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.end.$invalid && logoutForm.end.$dirty"></span>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" ng-class="( logout.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                </span>
                                <div uib-dropdown-menu>
                                    <datetimepicker data-ng-model="logout.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day'  }" data-on-set-time="dateSelect( logoutForm , 'end' , 2 )"></datetimepicker>
                                </div>
                            </div>

                        </form>

                        <hr />

                        <!-- Reports -->
                        <div class="reports" ng-show="report && report.total > 0">

                            <div class="summary clearfix">
                                
                                <!-- Sales Growth -->
                                <div>
                                    <canvas id="growth"></canvas>
                                </div>

                            </div>

                            <table class="table table-bordered table-striped list">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Average</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>                                                
                                                <strong><i class="fa fa-usd coloraaa"></i> Sales:</strong>
                                            </td>
                                            <td>
                                                @{{ report.accumulative.average_total_sales | currency  }}
                                            </td>
                                            <td>
                                                @{{ report.accumulative.sum_total_sales | currency  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>                                                
                                                <strong><i class="fa fa-male coloraaa"></i> Traffic:</strong>
                                            </td>
                                            <td>
                                                @{{ report.accumulative.average_total_traffic  }}
                                            </td>
                                            <td>
                                                @{{ report.accumulative.sum_total_traffic | number  }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>                                                
                                                <strong><i class="fa fa-pie-chart coloraaa"></i> Conversions:</strong>
                                            </td>
                                            <td>
                                                @{{ report.accumulative.average_total_conversions  }}%
                                            </td>
                                            <td>
                                                -
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>                                                
                                                <strong><i class="fa fa-shopping-basket coloraaa"></i> Insoles:</strong>
                                            </td>
                                            <td>
                                                @{{ report.accumulative.average_total_insoles  }}
                                            </td>
                                            <td>
                                                @{{ report.accumulative.sum_total_insoles | number  }}
                                            </td>
                                        </tr>
                                        <tr ng-show="report.staff.length > 0">
                                            <td colspan="3" class="bgfff">

                                                <p class="category"><strong>Staff Working:</strong></p>
                                                <table class="table table-bordered table-striped list">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <strong><i class="fa fa-user coloraaa"></i> Staff</strong>
                                                            </th>
                                                            <th>
                                                                <strong><i class="fa fa-clock-o coloraaa"></i> Hours</strong>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr ng-repeat="staff in report.staff">
                                                            <td>
                                                                @{{ staff.firstname }} @{{ staff.lastname }}
                                                            </td>
                                                            <td>
                                                                @{{ staff.sum_staff_hours }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                        <!-- Reports -->
                        <div class="reports" ng-show="!report || report.total == 0">

                            <p class="text-center">No Reports Available</p>

                        </div>

                        <footer ng-if="hasPermission('logouts')">

                            <a ng-click="back('/logouts(/[0-9]+)?' , '/logouts')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>

                        </footer>
                    </article>
                </main>

            </form>
