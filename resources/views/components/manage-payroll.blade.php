    
        <!-- components.manage-payroll form -->
        <form name="payrollForm" method="post" ng-submit="submit( payrollForm )" novalidate>

            <!-- components.manage-payroll success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && payrollForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-payroll error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( payrollForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Event Start Time Error -->
                        <li ng-show="payrollForm.error.start = ( payrollForm.start.$dirty && payrollForm.start.$invalid )" ng-messages="payrollForm.start.$error">
                            <span ng-message="required">Start Time is required</span>
                            <span ng-message="date-min">Start Time must begin after today</span>
                            <span ng-message="date-max">Start Time cannot start after the End Time</span>
                            <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                        </li>

                        <!-- Event End Time Error -->
                        <li ng-show="payrollForm.error.end = ( payrollForm.end.$dirty && payrollForm.end.$invalid )" ng-messages="payrollForm.end.$error">
                            <span ng-message="required">End Time is required</span>
                            <span ng-message="date-min">End Time cannot start before the Start Time</span>
                            <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                        </li>

                        <!-- Event End Time Error -->
                        <li ng-show="payrollForm.error.end = ( payrollForm.end.$dirty && payrollForm.end.$invalid )" ng-messages="payrollForm.end.$error">

                    </ul>
                    <ul ng-repeat="( index , user ) in users">
                        <li ng-repeat="(index, data) in payroll.hours[ user.id ]" ng-show="hasArrayError( user.id , index , 'position_id' ) || hasArrayError( user.id , index , 'date' ) || hasArrayError( user.id , index , 'hours' ) || hasArrayError( user.id , index , 'rate' )">
                            @{{ user.firstname + ' ' + user.lastname }} @{{ index + 1 }} Errors:
                            <ul>

                                <!-- Position Error -->
                                <li ng-show="payrollForm.error[ user.id + '' + index + 'position' ] = hasArrayError( user.id , index , 'position_id' )" ng-messages="payrollForm[ 'payroll.hours[' + user.id + '][' + index + '][position_id]' ].$error">
                                    <span ng-message="required"> Position is Required</span>
                                </li>

                                <!-- Date Error -->
                                <li ng-show="payrollForm.error[ user.id + '' + index + 'date' ] = hasArrayError( user.id , index , 'date' )" ng-messages="payrollForm[ 'payroll.hours[' + user.id + '][' + index + '][date]' ].$error">
                                    <span ng-message="required">Date is required</span>
                                    <span ng-message="date-min">Date cannot start before the Payroll Start Date</span>
                                    <span ng-message="date-max">Date cannot start after the Payroll End Date</span>
                                    <span ng-message="date-invalid">Date Requires a Valid Date</span>
                                </li>

                                <!-- Hours Error -->
                                <li ng-show="payrollForm.error[ user.id + '' + index + 'hours' ] = hasArrayError( user.id , index , 'hours' )" ng-messages="payrollForm[ 'payroll.hours[' + user.id + '][' + index + '][hours]' ].$error">
                                    <span ng-message="required">Hours are Required</span>
                                </li>

                                <!-- Rate Error -->
                                <li ng-show="payrollForm.error[ user.id + '' + index + 'rate' ] = hasArrayError( user.id , index , 'rate' )" ng-messages="payrollForm[ 'payroll.hours[' + user.id + '][' + index + '][rate]' ].$error">
                                    <span ng-message="required">Rate is Required</span>
                                </li>
                    
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- components.manage-payroll success -->
            <main id="payroll-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}


                    <div class="fright">
                            
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} payroll - Please wait.
                        </small>
                            
                        <!-- Saving Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="saving">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            Saving - Please wait.
                        </small>
                    
                        <!-- Form Error Notice -->
                        <small class="colorred submitnotice fleft" ng-show="hasError( payrollForm )">
                            You must fix any form errors before proceeding * See Errors at Top *
                        </small>

                        <!-- Submit Button -->
                        <button name="submit" class="btn fright" ng-class="hasError( payrollForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="payrollForm.$invalid || sending || saving">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .fright -->
                    </div>

                </header>
                <div class="panel-body">

                    <div ng-show="stores.length > 0">

                        <!-- Store Selector -->
                        <div ng-show="action == 'insert' && stores.length > 1">

                            <p>Select the Store for the Logout:</p>

                            <!-- Store -->
                            <div class="form-group clearfix">
                                <label class="hide" for="store_id">Store:</label>
                                <div class="select">
                                    <select type="text" id="store_id" name="store_id" ng-model="payroll.store_id" ng-change="update( payroll.store_id )" required>
                                        <option ng-repeat="store in stores" value="@{{ store.id }}" ng-selected="store == payroll.store_id">@{{ store.name }}</option>
                                    </select>
                                </div>
                                <i class="fa fa-building color888 fleft"></i>
                            </div>

                            <hr />

                        </div>

                        <div ng-show="!loading">

                            <p>Fill out the fields below to add your Payroll</p>

                            <!-- Start Date -->
                            <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="payroll.calendars[0].open" ng-class="{ 'has-error' : payrollForm.start.$invalid && payrollForm.start.$dirty , 'has-success' : !payrollForm.start.$invalid && payrollForm.start.$dirty }">
                                <label class="hide" for="start">Start Date:</label>
                                <input type="datetime" id="start" name="start" ng-model="payroll.start" placeholder="Start Date" date-compare date-max="payroll.end" autocomplete="off" required />
                                <i class="fa fa-clock-o color888 fleft"></i>
                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="payrollForm.start.$invalid && payrollForm.start.$dirty"></span>
                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!payrollForm.start.$invalid && payrollForm.start.$dirty"></span>
                                <span class="input-group-btn">
                                    <button type="button" class="btn" ng-class="( payroll.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                </span>
                                <div uib-dropdown-menu>
                                    <datetimepicker data-ng-model="payroll.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( payrollForm , 'start' , 1 )"></datetimepicker>
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="payroll.calendars[1].open" ng-class="{ 'has-error' : payrollForm.end.$invalid && payrollForm.end.$dirty , 'has-success' : !payrollForm.end.$invalid && payrollForm.end.$dirty }">
                                <label class="hide" for="end">End Date:</label>
                                <input type="datetime" id="end" name="end" ng-model="payroll.end" placeholder="End Date" date-compare date-min="payroll.start" autocomplete="off" required />
                                <i class="fa fa-clock-o color888 fleft"></i>
                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="payrollForm.end.$invalid && payrollForm.end.$dirty"></span>
                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!payrollForm.end.$invalid && payrollForm.end.$dirty"></span>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" ng-class="( payroll.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                </span>
                                <div uib-dropdown-menu>
                                    <datetimepicker data-ng-model="payroll.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( payrollForm , 'end' , 2 )"></datetimepicker>
                                </div>
                            </div>

                            <!-- Management Promotions -->
                            <table class="table table-bordered table-striped list" ng-repeat="( index , user ) in users">
                                <thead>
                                    <tr>
                                        <th colspan="3" valign="middle">
                                            <i class="fa fa-user"></i>
                                            @{{ user.firstname + ' ' + user.lastname }}
                                            <button type="button" ng-click="add( user , payrollForm )" class="btn-sm btn-primary fright">
                                                <i class="fa fa-plus-circle"></i>
                                                Add Hours
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="(index, data) in payroll.hours[ user.id ]">
                                        <td>

                                            <!-- Role -->
                                            <div class="form-group" ng-class="{ 'has-error' : hasArrayError( user.id , index , 'position_id' ) , 'has-success' : hasArraySuccess( user.id , index , 'position_id' ) }">
                                                <label class="hide" for="payroll-@{{ user.id }}-@{{ index }}-position_id">User Role</label>
                                                <div class="select">
                                                    <select id="payroll-@{{ user.id }}-@{{ index }}-position_id" name="payroll.hours[@{{ user.id }}][@{{ index }}][position_id]" placeholder="User Role" ng-model="payroll.hours[ user.id ][ index ].position_id" ng-change="save( payrollForm )" required ng-options="position.id as position.name for position in positions">
                                                        <option value="">Select Position</option>
                                                    </select>
                                                </div>
                                                <i class="fa fa-wrench color888 fleft"></i>
                                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( user.id , index , 'position_id' )"></span>
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( user.id , index , 'position_id' )"></span>
                                            </div>

                                            <!-- Date -->
                                            <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="payroll.calendars[ user.id + '-' + index ].open" ng-class="{ 'has-error' : hasArrayError( user.id , index , 'date' ) , 'has-success' : hasArraySuccess( user.id , index , 'date' ) }">
                                                <label class="hide" for="payroll-@{{ user.id }}-@{{ index }}-date">Date:</label>
                                                <input type="datetime" id="payroll-@{{ user.id }}-@{{ index }}-date" name="payroll.hours[@{{ user.id }}][@{{ index }}][date]" ng-model="payroll.hours[ user.id ][ index ].date" placeholder="Date" date-compare date-min="payroll.start" date-max="payroll.end" autocomplete="off" required />
                                                <i class="fa fa-clock-o color888 fleft"></i>
                                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( user.id , index , 'date' )"></span>
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( user.id , index , 'date' )"></span>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn" ng-class="( payroll.calendars[ user.id + '-' + index ].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                                </span>
                                                <div uib-dropdown-menu>
                                                    <datetimepicker data-ng-model="payroll.hours[ user.id ][ index ].date" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( payrollForm , 'payroll.hours[' + user.id + '][' + index + '][date]' , 1 )"></datetimepicker>
                                                </div>
                                            </div>

                                        </td>
                                        <td>

                                            <!-- Hours -->
                                            <div class="form-group" ng-class="{ 'has-error' : hasArrayError( user.id , index , 'hours' ) , 'has-success' : hasArraySuccess( user.id , index , 'hours' ) }">
                                                <label class="hide" for="payroll-@{{ user.id }}-@{{ index }}-hours">Hours</label>
                                                <input type="text" id="payroll-@{{ user.id }}-@{{ index }}-hours" name="payroll.hours[@{{ user.id }}][@{{ index }}][hours]" placeholder="Hours" ng-model="payroll.hours[ user.id ][ index ].hours" ng-change="save( payrollForm )" number required />
                                                <i class="fa fa-hourglass-end color888 fleft"></i>
                                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( user.id , index , 'hours' )"></span>
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( user.id , index , 'hours' )"></span>
                                            </div>

                                            <!-- Rate -->
                                            <div class="form-group" ng-class="{ 'has-error' : hasArrayError( user.id , index , 'rate' ) , 'has-success' : hasArraySuccess( user.id , index , 'rate' ) }">
                                                <label class="hide" for="payroll-@{{ user.id }}-@{{ index }}-rate">Rate</label>
                                                <input type="text" id="payroll-@{{ user.id }}-@{{ index }}-rate" name="payroll.hours[@{{ user.id }}][@{{ index }}][rate]" placeholder="Rate" ng-model="payroll.hours[ user.id ][ index ].rate" currency ng-change="save( payrollForm )" required />
                                                <i class="fa fa-usd color888 fleft"></i>
                                                <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( user.id , index , 'rate' )"></span>
                                                <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( user.id , index , 'rate' )"></span>
                                            </div>

                                        </td>
                                        <td width="50" align="center">

                                            <!-- Delete QSA -->
                                            <button type="button" ng-click="payroll.hours[ user.id ].splice(index,1); save( payrollForm );" class="btn-sm btn-danger font2">Delete</button>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                    <!-- No Stores -->
                    <div ng-show="!loading && stores.length == 0">
                        <p class="text-center none">Your account isn't associated to any Stores!</p>
                    </div>

                    <!-- Show Loading -->
                    <div ng-show="loading" class="text-center font2 color000">
                        <i class="fa fa-circle-o-notch fa-spin color000"></i>
                        <span>Loading</span>
                    </div>

                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/payrolls(/[0-9]+)?' , '/payrolls')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>
                
            </main>

        </form>

