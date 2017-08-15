
        <!-- components.manage-logout -->
        <form name="logoutForm" method="post" ng-submit="submit( logoutForm )" novalidate>


            <!-- components.manage-logout success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && logoutForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-logout error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( logoutForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Start Date Error -->
                        <li ng-show="logoutForm.error[ 'start' ] = logoutForm.start.$invalid && logoutForm.start.$dirty" ng-messages="logoutForm.start.$error">
                            <span ng-message="required">Start Date is required</span>
                            <span ng-message="date-max">Start Time cannot start after the End Time</span>
                            <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                        </li>

                        <!-- End Date Error -->
                        <li ng-show="logoutForm.error[ 'end' ] = logoutForm.end.$invalid && logoutForm.end.$dirty" ng-messages="logoutForm.end.$error">
                            <span ng-message="required">End Date is required</span>
                            <span ng-message="date-min">End Time cannot start before the Start Time</span>
                            <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                        </li>

                        <!-- Recap Error -->
                        <li ng-show="logoutForm.error[ 'recap' ] = logoutForm.recap.$invalid && logoutForm.recap.$dirty" ng-messages="logoutForm.recap.$error">
                            <span ng-message="required">Recap is required</span>
                        </li>

                        <!-- Last Year Month to Date Sales Error -->
                        <li ng-show="logoutForm.error[ 'lymtd' ] = logoutForm.lymtd.$invalid && logoutForm.lymtd.$dirty" ng-messages="logoutForm.lymtd.$error">
                            <span ng-message="required">Last Years Month to Date Sales is required</span>
                        </li>

                        <!-- Month to Date Sales Error -->
                        <li ng-show="logoutForm.error[ 'mtd' ] = logoutForm.mtd.$invalid && logoutForm.mtd.$dirty" ng-messages="logoutForm.mtd.$error">
                            <span ng-message="required">Month to Date Sales are required</span>
                        </li>

                        <!-- Sales Error -->
                        <li ng-show="logoutForm.error[ 'sales' ] = logoutForm.sales.$invalid && logoutForm.sales.$dirty" ng-messages="logoutForm.sales.$error">
                            <span ng-message="required">Today's Sales are required</span>
                        </li>

                        <!-- Returns Error -->
                        <li ng-show="logoutForm.error[ 'returns' ] = logoutForm.returns.$invalid && logoutForm.returns.$dirty" ng-messages="logoutForm.returns.$error">
                            <span ng-message="required">Returns are required</span>
                        </li>

                        <!-- Staff Working Errors -->
                        <li ng-repeat="(index , data) in logout.staff" ng-show="logoutForm.error[ 'staff-name-' + index ] || logoutForm.error[ 'staff-hours-' + index ]">
                            Staff Working @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Staff Name -->
                                <li ng-show="logoutForm.error[ 'staff-name-' + index ] = ( logoutForm[ 'staff[' + index + '][id]' ].$invalid && logoutForm[ 'staff[' + index + '][id]' ].$dirty )" ng-messages="logoutForm[ 'staff[' + index + '][id]' ].$error">
                                    <span ng-message="required">Staff Member is required</span>
                                </li>
                                
                                <!-- Hours -->
                                <li ng-show="logoutForm.error[ 'staff-hours-' + index ] = ( logoutForm[ 'staff[' + index + '][hours]' ].$invalid && logoutForm[ 'staff[' + index + '][hours]' ].$dirty )" ng-messages="logoutForm[ 'staff[' + index + '][hours]' ].$error">
                                    <span ng-message="required">Hours are required</span>
                                </li>

                            </ul>
                        </li>

                        <!-- Traffic Error -->
                        <li ng-show="logoutForm.error[ 'traffic' ] = logoutForm.traffic.$invalid && logoutForm.traffic.$dirty" ng-messages="logoutForm.traffic.$error">
                            <span ng-message="required">Traffic is required</span>
                        </li>

                        <!-- Conversion Rate Error -->
                        <li ng-show="logoutForm.error[ 'conversions' ] = logoutForm.conversions.$invalid && logoutForm.conversions.$dirty" ng-messages="logoutForm.conversions.$error">
                            <span ng-message="required">Conversion Rate is required</span>
                        </li>

                        <!-- Insoles Error -->
                        <li ng-show="logoutForm.error[ 'insoles' ] = logoutForm.insoles.$invalid && logoutForm.insoles.$dirty" ng-messages="logoutForm.insoles.$error">
                            <span ng-message="required">Insoles is required</span>
                        </li>

                        <!-- Other Notes Error -->
                        <li ng-show="logoutForm.error[ 'notes' ] = logoutForm.notes.$invalid && logoutForm.notes.$dirty" ng-messages="logoutForm.notes.$error">
                            <span ng-message="required">Other Notes are required</span>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-logout -->
            <main id="logout-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                    <div class="form-group fright">
                            
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} logout - Please wait.
                        </small>
                            
                        <!-- Saving Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="saving">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            Saving - Please wait.
                        </small>

                        <!-- Form Error Notices -->
                        <small class="colorred text-right fleft submitnotice" ng-show="hasError( logoutForm )">
                            <span ng-show="hasError( logoutForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                        </small>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn-sm fright" ng-class="hasError( logoutForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="logoutForm.$invalid || sending || saving">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .form-group -->
                    </div>

                </header>
                <div class="panel-body">

                    <div class="logout-panel">

                        <!-- Tabs -->
                        <div class="tab-content">

                            <!-- Form Data -->
                            <div class="form-data" ng-show="stores.length && !loading">

                                <!-- Store Selector -->
                                <div ng-show="action == 'insert' && stores.length > 1">

                                    <p>Select the Store for the Logout:</p>

                                    <!-- Store -->
                                    <div class="form-group clearfix">
                                        <label class="hide" for="store_id">Store:</label>
                                        <div class="select">
                                            <select type="text" id="store_id" name="store_id" ng-model="logout.store_id" ng-change="update( logout.store_id )" required>
                                                <option ng-repeat="store in stores" value="@{{ store.id }}" ng-selected="store == logout.store_id">@{{ store.name }}</option>
                                            </select>
                                        </div>
                                        <i class="fa fa-building color888 fleft"></i>
                                    </div>

                                    <hr />

                                </div>

                                <p>Fill out the fields below to add your Menu Item</p>


                                <!-- Start Date -->
                                <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="logout.calendars[0].open" ng-class="{ 'has-error' : logoutForm.start.$invalid && logoutForm.start.$dirty , 'has-success' : !logoutForm.start.$invalid && logoutForm.start.$dirty }">
                                    <label class="hide" for="start">Start Date:</label>
                                    <input type="datetime" id="start" name="start" ng-model="logout.start" placeholder="Start Date" date-compare date-max="logout.end" ng-change="save( logoutForm )" autocomplete="off" required />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.start.$invalid && logoutForm.start.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.start.$invalid && logoutForm.start.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( logout.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="logout.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( logoutForm , 'start' , 1 )"></datetimepicker>
                                    </div>
                                </div>


                                <!-- End Date -->
                                <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="logout.calendars[1].open" ng-class="{ 'has-error' : logoutForm.end.$invalid && logoutForm.end.$dirty , 'has-success' : !logoutForm.end.$invalid && logoutForm.end.$dirty }">
                                    <label class="hide" for="end">End Date:</label>
                                    <input type="datetime" id="end" name="end" ng-model="logout.end" placeholder="End Date" date-compare date-min="logout.start" ng-change="save( logoutForm )" autocomplete="off" required />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.end.$invalid && logoutForm.end.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.end.$invalid && logoutForm.end.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( logout.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="logout.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( logoutForm , 'end' , 2 )"></datetimepicker>
                                    </div>
                                </div>

                                <!-- Recap -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.recap.$invalid && logoutForm.recap.$dirty , 'has-success' : !logoutForm.recap.$invalid && logoutForm.recap.$dirty }">
                                    <label class="hide" for="recap">Recap</label>
                                    <textarea id="recap" name="recap" placeholder="Recap" ng-model="logout.recap" ng-change="save( logoutForm )" required is-empty></textarea>
                                    <i class="fa fa-comments color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.recap.$invalid && logoutForm.recap.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.recap.$invalid && logoutForm.recap.$dirty"></span>
                                </div>

                                <!-- Last Year Month to Date Sales -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.lymtd.$invalid && logoutForm.lymtd.$dirty , 'has-success' : !logoutForm.lymtd.$invalid && logoutForm.lymtd.$dirty }">
                                    <label class="hide" for="lymtd">Last Year Month to Date Sales:</label>
                                    <input type="text" id="lymtd" name="lymtd" placeholder="Last Year MTD Sales" ng-model="logout.lymtd" ng-change="save( logoutForm )" currency required />
                                    <i class="fa fa-usd color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.lymtd.$invalid && logoutForm.lymtd.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.lymtd.$invalid && logoutForm.lymtd.$dirty"></span>
                                </div>

                                <!-- Month to Date Sales -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.mtd.$invalid && logoutForm.mtd.$dirty , 'has-success' : !logoutForm.mtd.$invalid && logoutForm.mtd.$dirty }">
                                    <label class="hide" for="mtd">Month to Date Sales:</label>
                                    <input type="text" id="mtd" name="mtd" placeholder="MTD Sales" ng-model="logout.mtd" ng-change="save( logoutForm )" currency required />
                                    <i class="fa fa-usd color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.mtd.$invalid && logoutForm.mtd.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.mtd.$invalid && logoutForm.mtd.$dirty"></span>
                                </div>

                                <!-- Today Sales -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.sales.$invalid && logoutForm.sales.$dirty , 'has-success' : !logoutForm.sales.$invalid && logoutForm.sales.$dirty }">
                                    <label class="hide" for="sales">Today's Sales:</label>
                                    <input type="text" id="sales" name="sales" placeholder="Today's Sales" ng-model="logout.sales" ng-change="save( logoutForm )" currency required />
                                    <i class="fa fa-usd color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.sales.$invalid && logoutForm.sales.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.sales.$invalid && logoutForm.sales.$dirty"></span>
                                </div>

                                <!-- Returns -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.returns.$invalid && logoutForm.returns.$dirty , 'has-success' : !logoutForm.returns.$invalid && logoutForm.returns.$dirty }">
                                    <label class="hide" for="returns">Returns:</label>
                                    <input type="text" id="returns" name="returns" placeholder="Returns" ng-model="logout.returns" ng-change="save( logoutForm )" currency required />
                                    <i class="fa fa-undo color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.returns.$invalid && logoutForm.returns.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.returns.$invalid && logoutForm.returns.$dirty"></span>
                                </div>

                                <!-- Staff Working -->
                                <table class="table table-bordered table-striped list">
                                    <thead>
                                        <tr>
                                            <th colspan="4" valign="middle">
                                                <i class="fa fa-users"></i>
                                                Staff Working
                                                <button type="button" ng-click="logout.staff.push({}); save( logoutForm );" class="btn-sm btn-primary fright">
                                                    <i class="fa fa-plus-circle"></i>
                                                    Add Staff
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-repeat="(index, data) in logout.staff">
                                            <td>

                                                <!-- Staff Working -->
                                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.staff[ index ].id.$invalid && logoutForm.staff[ index ].id.$dirty , 'has-success' : !logoutForm.staff[ index ].id.$invalid && logoutForm.staff[ index ].id.$dirty }">
                                                    <label class="hide" for="staff-@{{ index }}-id">Staff Member</label>
                                                    <div class="select">
                                                        <select id="staff-@{{ index }}-id" name="staff[@{{ index }}][id]" ng-model="logout.staff[ index ].id" ng-change="save( logoutForm )" ng-options="user.id as user.firstname + ' ' + user.lastname for user in users | users:logout.staff:index" required>
                                                            <option value="">Staff Member</option>
                                                        </select>
                                                    </div>
                                                    <i class="fa fa-users color888 fleft"></i>
                                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.staff[ index ].id.$invalid && logoutForm.staff[ index ].id.$dirty"></span>
                                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.staff[ index ].id.$invalid && logoutForm.staff[ index ].id.$dirty"></span>
                                                </div>

                                            </td>
                                            <td width="50%">

                                                <!-- Hours -->
                                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.staff[ index ].hours.$invalid && logoutForm.staff[ index ].hours.$dirty , 'has-success' : !logoutForm.staff[ index ].hours.$invalid && logoutForm.staff[ index ].hours.$dirty }">
                                                    <label class="hide" for="staff-@{{ index }}-.hours">Today's Sales:</label>
                                                    <input type="number" id="staff-@{{ index }}-hours" name="staff[@{{ index }}][hours]" placeholder="Hours Worked" ng-model="logout.staff[ index ].hours" ng-change="save( logoutForm )" required />
                                                    <i class="fa fa-clock-o color888 fleft"></i>
                                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.staff[ index ].hours.$invalid && logoutForm.staff[ index ].hours.$dirty"></span>
                                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.staff[ index ].hours.$invalid && logoutForm.staff[ index ].hours.$dirty"></span>
                                                </div>

                                            </td>
                                            <td width="50" align="center">

                                                <!-- Delete Product -->
                                                <button type="button" ng-click="logout.staff.splice(index,1); save( logoutForm );" class="btn-sm btn-danger font2">Delete</button>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Traffic -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.traffic.$invalid && logoutForm.traffic.$dirty , 'has-success' : !logoutForm.traffic.$invalid && logoutForm.traffic.$dirty }">
                                    <label class="hide" for="traffic">Traffic</label>
                                    <input type="number" id="traffic" name="traffic" placeholder="Traffic" ng-model="logout.traffic" ng-change="save( logoutForm )" step="1" required is-empty />
                                    <i class="fa fa-male color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.traffic.$invalid && logoutForm.traffic.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.traffic.$invalid && logoutForm.traffic.$dirty"></span>
                                </div>

                                <!-- Conversion Rate -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.conversions.$invalid && logoutForm.conversions.$dirty , 'has-success' : !logoutForm.conversions.$invalid && logoutForm.conversions.$dirty }">
                                    <label class="hide" for="conversions">Conversion Rate</label>
                                    <input type="text" id="conversions" name="conversions" placeholder="Conversion Rate" ng-model="logout.conversions" ng-change="save( logoutForm )" step="0.01" percentage required />
                                    <i class="fa fa-pie-chart color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.conversions.$invalid && logoutForm.conversions.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.conversions.$invalid && logoutForm.conversions.$dirty"></span>
                                </div>

                                <!-- Insoles -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.insoles.$invalid && logoutForm.insoles.$dirty , 'has-success' : !logoutForm.insoles.$invalid && logoutForm.insoles.$dirty }">
                                    <label class="hide" for="insoles">Insoles</label>
                                    <input type="number" id="insoles" name="insoles" placeholder="Insoles" ng-model="logout.insoles" ng-change="save( logoutForm )" step="1" required is-empty />
                                    <i class="fa fa-shopping-basket color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.insoles.$invalid && logoutForm.insoles.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.insoles.$invalid && logoutForm.insoles.$dirty"></span>
                                </div>

                                <!-- Other Notes -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : logoutForm.notes.$invalid && logoutForm.notes.$dirty , 'has-success' : !logoutForm.notes.$invalid && logoutForm.notes.$dirty }">
                                    <label class="hide" for="notes">Other Notes</label>
                                    <textarea id="notes" name="logout[notes]" placeholder="Other Notes" ng-model="logout.notes" ng-change="save( logoutForm )" required is-empty></textarea>
                                    <i class="fa fa-info-circle color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="logoutForm.notes.$invalid && logoutForm.notes.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!logoutForm.notes.$invalid && logoutForm.notes.$dirty"></span>
                                </div>

                            <!-- /End .tab-pane -->
                            </div>

                        <!-- /End .form-data -->
                        </div>

                        <!-- No Stores -->
                        <div ng-show="!stores.length && !loading" class="text-center none">
                            Your account isn't associated to any Stores!
                        </div>

                        <!-- Show Loading -->
                        <div ng-show="loading" class="text-center font2 color000">
                            <i class="fa fa-circle-o-notch fa-spin color000"></i>
                            <span>Loading</span>
                        </div>

                    <!-- /End .logout-panel -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

