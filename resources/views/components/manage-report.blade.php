
        <!-- components.manage-report -->
        <form name="reportForm" method="post" ng-submit="submit( reportForm )" novalistart>


            <!-- components.manage-report success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && reportForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-report error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( reportForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Event Start Time Error -->
                        <li ng-show="reportForm.error.start = ( reportForm.start.$dirty && reportForm.start.$invalid )" ng-messages="reportForm.start.$error">
                            <span ng-message="required">Start Time is required</span>
                            <span ng-message="date-min">Start Time must begin after today</span>
                            <span ng-message="date-max">Start Time cannot start after the End Time</span>
                            <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                        </li>

                        <!-- Event End Time Error -->
                        <li ng-show="reportForm.error.end = ( reportForm.end.$dirty && reportForm.end.$invalid )" ng-messages="reportForm.end.$error">
                            <span ng-message="required">End Time is required</span>
                            <span ng-message="date-min">End Time cannot start before the Start Time</span>
                            <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-report -->
            <main id="report-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Fill out the fields below to add your new Report</p>

                    <!-- Start Date -->
                    <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="report.calendars[0].open" ng-class="{ 'has-error' : reportForm.start.$invalid && reportForm.start.$dirty , 'has-success' : !reportForm.start.$invalid && reportForm.start.$dirty }">
                        <label class="hide" for="start">Reporting Week Start:</label>
                        <input type="datetime" id="start" name="start" ng-model="report.start" placeholder="Reporting Week Start" date-compare date-max="report.end" autocomplete="off" required />
                        <i class="fa fa-clock-o color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="reportForm.start.$invalid && reportForm.start.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!reportForm.start.$invalid && reportForm.start.$dirty"></span>
                        <span class="input-group-btn">
                            <button type="button" class="btn" ng-class="( report.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                        </span>
                        <div uib-dropdown-menu>
                            <datetimepicker data-ng-model="report.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( reportForm , 'start' , 1 )"></datetimepicker>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="report.calendars[1].open" ng-class="{ 'has-error' : reportForm.end.$invalid && reportForm.end.$dirty , 'has-success' : !reportForm.end.$invalid && reportForm.end.$dirty }">
                        <label class="hide" for="end">Reporting Week End:</label>
                        <input type="datetime" id="end" name="end" ng-model="report.end" placeholder="Reporting Week End" date-compare date-min="report.start" autocomplete="off" required />
                        <i class="fa fa-clock-o color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="reportForm.end.$invalid && reportForm.end.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!reportForm.end.$invalid && reportForm.end.$dirty"></span>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-class="( report.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                        </span>
                        <div uib-dropdown-menu>
                            <datetimepicker data-ng-model="report.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( reportForm , 'end' , 2 )"></datetimepicker>
                        </div>
                    </div>

                    <!-- Files Upload -->                    
                     <table class="table table-bordered table-striped list">
                        <thead>
                            <tr>
                                <th width="50"></th>
                                <th class="text-left font2 color000 col20">Store</th>
                                <th class="text-left font2 color000">File</th>
                                <th class="text-left font2 color000">Upload</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="( index , item ) in report.files">
                                <td>
                                    <select ng-change="move( index , report.files[ index ].order )" ng-model="report.files[ index ].order" ng-options="(i + 1) for i in [] | range:0:report.files.length">
                                    
                                    </select>
                                </td>
                                <td>@{{ item.store.name }}</td>
                                <td>                                    

                                    <!-- Show Files -->
                                    <ul class="files clearfix">
                                        <li ng-if="report.files[ index ].file" class="clearfix">
                                            <progress max="100" value="@{{ report.files[ index ].file.progress }}" ng-class="report.files[ index ].file.status ? report.files[ index ].file.status : null"></progress>
                                            <div class="clearfix">                                             
                                                <small class="fleft font2">
                                                    @{{ report.files[ index ].file.filename }}
                                                </small>
                                                <i class="fa fa-times fright" ng-click="remove( index )"></i>
                                            </div>
                                        </li>
                                    </ul>

                                </td>
                                <td>

                                    <!-- Drop Attachment -->
                                    <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ng-model="report.files[ index ].file" class="drop-area text-center" ngf-multiple="false" required-any="files">
                                        
                                        <i class="fa fa-paperclip color888"></i>
                                        Drop Attachment / Click Here

                                    </div>

                                </td>
                            </tr>
                            <tr ng-show="report.stores.length == 0">
                                <td colspan="100%" class="none text-center" ng-if="hasPermission('stores.create')">
                                    <a ng-click="open('/admin/stores/add')" class="color000 hovercolor000">You haven't added any Stores yet!</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Submit Actions -->
                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/reports(/[0-9]+)?' , '/reports')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                        
                            <!-- Form Error Notice -->
                            <small class="colorred submitnotice" ng-show="hasError( reportForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} report - Please wait.
                            </small>
    
                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( reportForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="reportForm.$invalid || sending">
                                <i class="fa fa-@{{ icon }}"></i>
                                @{{ button }}
                            </button>

                        <!-- /End .fright -->
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

