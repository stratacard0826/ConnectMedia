
        <!-- components.manage-sickday -->
        <form name="sickDayForm" method="post" ng-submit="submit( sickDayForm )" novalidate>


            <!-- components.manage-sickday success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && sickDayForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-sickday error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( sickDayForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Store Error -->
                        <li ng-show="sickDayForm.error[ 'store' ] = sickDayForm.store_id.$invalid && sickDayForm.store_id.$dirty" ng-messages="sickDayForm.store_id.$error">
                            <span ng-message="required">Store is required</span>
                        </li>

                        <!-- User Error -->
                        <li ng-show="sickDayForm.error[ 'user' ] = sickDayForm.user_id.$invalid && sickDayForm.user_id.$dirty" ng-messages="sickDayForm.user_id.$error">
                            <span ng-message="required">User is required</span>
                        </li>

                        <!-- Date Error -->
                        <li ng-show="sickDayForm.error[ 'date' ] = sickDayForm.date.$invalid && sickDayForm.date.$dirty" ng-messages="sickDayForm.date.$error">
                            <span ng-message="required">Date is required</span>
                        </li>

                        <!-- Details Error -->
                        <li ng-show="sickDayForm.error[ 'details' ] = sickDayForm.details.$invalid && sickDayForm.details.$dirty" ng-messages="sickDayForm.details.$error">
                            <span ng-message="required">Details are required</span>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-sickday -->
            <main id="sickday-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                    <div class="form-group fright">
                            
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} Sick Day - Please wait.
                        </small>

                        <!-- Form Error Notices -->
                        <small class="colorred text-right fleft submitnotice" ng-show="hasError( sickDayForm )">
                            <span ng-show="hasError( sickDayForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                        </small>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn-sm fright" ng-class="hasError( sickDayForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="sickDayForm.$invalid || sending">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .form-group -->
                    </div>

                </header>
                <div class="panel-body">

                    <div class="sickday-panel">

                        <!-- Tabs -->
                        <div class="tab-content">

                            <!-- Form Data -->
                            <div class="form-data" ng-show="stores.length">

                                <p>Fill out the fields below to add your Sick Day</p>

                                <!-- Date -->
                                <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="sickday.open" ng-class="{ 'has-error' : sickDayForm.date.$invalid && sickDayForm.date.$dirty , 'has-success' : !sickDayForm.date.$invalid && sickDayForm.date.$dirty }">
                                    <label class="hide" for="date">Date:</label>
                                    <input type="datetime" id="date" name="date" ng-model="sickday.date" placeholder="Date" date-compare date-max="sickday.end" autocomplete="off" required />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="sickDayForm.date.$invalid && sickDayForm.date.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!sickDayForm.date.$invalid && sickDayForm.date.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( sickday.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="sickday.date" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( sickDayForm , 'date' , 1 )"></datetimepicker>
                                    </div>
                                </div>

                                <!-- Store -->
                                <div class="form-group clearfix">
                                    <label class="hide" for="store">Store:</label>
                                    <div class="select">
                                        <select type="text" id="store" name="store_id" ng-model="sickday.store_id" ng-change="update( sickday.store_id )" ng-options="store.id as store.name for store in stores" required>
                                            <option value="">Select a Store</option>
                                        </select>
                                    </div>
                                    <i class="fa fa-building color888 fleft"></i>
                                </div>

                                <!-- User -->
                                <div class="form-group clearfix">
                                    <label class="hide" for="user">User:</label>
                                    <div class="select">
                                        <select type="text" id="user" name="user_id" ng-model="sickday.user_id" ng-options="user.id as user.firstname + ' ' + user.lastname for user in users" required>
                                            <option value="">Select a User</option>
                                        </select>
                                    </div>
                                    <i class="fa fa-user-md color888 fleft"></i>
                                </div>

                                <!-- Details -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : sickDayForm.details.$invalid && sickDayForm.details.$dirty , 'has-success' : !sickDayForm.details.$invalid && sickDayForm.details.$dirty }">
                                    <label class="hide" for="details">Details:</label>
                                    <input type="text" id="details" name="details" placeholder="Other Notes" ng-model="sickday.details" required is-empty></textarea>
                                    <i class="fa fa-info-circle color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="sickDayForm.details.$invalid && sickDayForm.details.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!sickDayForm.details.$invalid && sickDayForm.details.$dirty"></span>
                                </div>

                            <!-- /End .tab-pane -->
                            </div>

                        <!-- /End .form-data -->
                        </div>

                        <!-- No Stores -->
                        <div ng-show="!stores.length" class="text-center none">
                            Your account isn't associated to any Stores!
                        </div>

                    <!-- /End .sickday-panel -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

