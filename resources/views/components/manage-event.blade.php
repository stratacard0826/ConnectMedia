
        <form name="eventForm" method="post" ng-submit="submit( eventForm )" autocomplete="off" novalidate>
        
            <!-- components.manage-user success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-user error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( eventForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Event Details Error -->
                        <li ng-show="eventForm.error.details = ( eventForm.details.$dirty && eventForm.details.$invalid )" ng-messages="eventForm.details.$error">
                            <span ng-message="required">Details are required</span>
                        </li>

                        <!-- Event Subject Error -->
                        <li ng-show="eventForm.error.name = ( eventForm.name.$dirty && eventForm.name.$invalid )" ng-messages="eventForm.name.$error">
                            <span ng-message="required">Event Name is required</span>
                            <span ng-message="minlength">Event Name must be between 1 and 100 characters</span>
                            <span ng-message="maxlength">Event Name must be between 1 and 100 characters</span>                        
                        </li>

                        <!-- Event Start Time Error -->
                        <li ng-show="eventForm.error.start = ( eventForm.start.$dirty && eventForm.start.$invalid )" ng-messages="eventForm.start.$error">
                            <span ng-message="required">Start Time is required</span>
                            <span ng-message="date-min">Start Time must begin after today</span>
                            <span ng-message="date-max">Start Time cannot start after the End Time</span>
                            <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                        </li>

                        <!-- Event End Time Error -->
                        <li ng-show="eventForm.error.end = ( eventForm.end.$dirty && eventForm.end.$invalid )" ng-messages="eventForm.end.$error">
                            <span ng-message="required">End Time is required</span>
                            <span ng-message="date-min">End Time cannot start before the Start Time</span>
                            <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-event success -->
            <main id="event-management" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">

                    <p>Who can view this event?: <small>( <em>Leave blank for everyone</em> )</small></p>

                    <!-- Stores -->
                    <div class="form-group" ng-if="stores" ng-class="{ 'has-success' : !eventForm.stores.$invalid && eventForm.stores.$dirty }">
                        <label class="hide" for="stores">Stores:</label>
                        <multiselect id="stores" name="stores" ng-model="events.stores" ng-value="events.stores" options="stores.data" selected-model="events.stores" extra-settings="stores.settings" translation-texts="{ buttonDefaultText: 'Users in Stores' }" events="stores.events" ng-dropdown-multiselect="stores"></multiselect>
                        <i class="fa fa-map-marker color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.stores.$invalid && eventForm.stores.$dirty"></span>
                    </div>

                    <!-- Roles -->
                    <div class="form-group" ng-if="roles" ng-class="{ 'has-success' : !eventForm.roles.$invalid && eventForm.roles.$dirty }">
                        <label class="hide" for="roles">Roles:</label>
                        <multiselect id="roles" name="roles" ng-model="events.roles" ng-value="events.roles" options="roles.data" selected-model="events.roles" extra-settings="roles.settings" translation-texts="{ buttonDefaultText: 'Users with Roles' }" events="roles.events" ng-dropdown-multiselect="roles"></multiselect>
                        <i class="fa fa-lock color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.roles.$invalid && eventForm.roles.$dirty"></span>
                    </div>

                    <p>Between what date range does the event take place?:</p>

                    <!-- Start Date -->
                    <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="events.calendars[0].open" ng-class="{ 'has-error' : eventForm.start.$invalid && eventForm.start.$dirty , 'has-success' : !eventForm.start.$invalid && eventForm.start.$dirty }">
                        <label class="hide" for="start">Event Start:</label>
                        <input type="datetime" id="start" name="start" ng-model="events.start" placeholder="Start Time" date-compare date-min="today" date-max="events.end" autocomplete="off" required />
                        <i class="fa fa-clock-o color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="eventForm.start.$invalid && eventForm.start.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.start.$invalid && eventForm.start.$dirty"></span>
                        <span class="input-group-btn">
                            <button type="button" class="btn" ng-class="( events.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                        </span>
                        <div uib-dropdown-menu>
                            <datetimepicker data-ng-model="events.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( eventForm , 'start' , 1 )" data-before-render="setAllowedDates( $view , $dates , $leftDate, $upDate, $rightDate )"></datetimepicker>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="events.calendars[1].open" ng-class="{ 'has-error' : eventForm.end.$invalid && eventForm.end.$dirty , 'has-success' : !eventForm.end.$invalid && eventForm.end.$dirty }">
                        <label class="hide" for="end">Event End:</label>
                        <input type="datetime" id="end" name="end" ng-model="events.end" placeholder="End Time" date-compare date-min="events.start" autocomplete="off" required />
                        <i class="fa fa-clock-o color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="eventForm.end.$invalid && eventForm.end.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.end.$invalid && eventForm.end.$dirty"></span>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-class="( events.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                        </span>
                        <div uib-dropdown-menu>
                            <datetimepicker data-ng-model="events.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( eventForm , 'end' , 2 )" data-before-render="setAllowedDates( $view , $dates , $leftDate, $upDate, $rightDate )"></datetimepicker>
                        </div>
                    </div>

                    <p>Attach a File:</p>

                    <!-- File Drop Zone -->
                    <div class="form-group">
                        <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ngf-max-size="'10MB'" ng-model="events.files" class="drop-area text-center" ngf-multiple="true">
                            
                            <i class="fa fa-paperclip color888"></i>
                            Drop Attachment / Click Here

                        </div>
                    </div>

                    <!-- File List -->
                    <ul class="files clearfix">
                        <li ng-repeat="file in events.files" class="fleft clearfix">
                            <progress max="100" value="@{{ file.progress }}" ng-class="file.status ? file.status : null"></progress>
                            <small class="fleft font2">@{{ file.name }}</small>
                            <i class="fa fa-times fright" ng-click="remove( $index )"></i>
                        </li>
                    </ul>

                    <p>Create your News Article:</p>

                    <!-- Event Name -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : eventForm.name.$invalid && eventForm.name.$dirty , 'has-success' : !eventForm.name.$invalid && eventForm.name.$dirty }">
                        <label class="hide" for="name">Event Name:</label>
                        <input id="name" type="text" name="name" ng-model="events.name" ng-value="events.name" placeholder="Event Name" required />
                        <i class="fa fa-bookmark color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="eventForm.name.$invalid && eventForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.name.$invalid && eventForm.name.$dirty"></span>
                    </div>

                    <!-- Event Details -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : eventForm.details.$invalid && eventForm.details.$dirty , 'has-success' : !eventForm.details.$invalid && eventForm.details.$dirty }">
                        <label class="hide" for="details">Event Details:</label>
                        <text-angular id="details" name="details" ng-model="events.details" placeholder="Event Description" ng-value="events.article" ta-toolbar="[['bold', 'italics', 'ul', 'ol', 'quote', 'insertLink']]" required></text-angular>
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="eventForm.details.$invalid && eventForm.details.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!eventForm.details.$invalid && eventForm.details.$dirty"></span>
                    </div>

                    @include('components/reminders')                    


                    <div class="form-group">

                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/admin/events(/[0-9]+)?' , '/admin/events')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>


                        <div class="fright">
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo text-right submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} article - Please wait.
                            </small>

                            <!-- Form Errors Notice -->
                            <small class="colorred text-right submitnotice" ng-show="hasError( eventForm )">
                                <span ng-show="hasError( eventForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                            </small>

                            <div>
                            
                                <!-- Send Email Button -->
                                <p class="fleft" style="padding-right:10px;">
                                    Send Email:
                                    <input type="checkbox" name="sendemail" ng-model="events.sendemail" ng-checked="events.sendemail" ng-true-value="1" />
                                </p>
    
                                <!-- Submit Button -->
                                <button name="submit" class="btn fright" ng-class="hasError( eventForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="eventForm.$invalid || sending">
                                    <i class="fa fa-@{{ icon }}"></i>
                                    @{{ button }}
                                </button>

                            </div>

                        <!-- /End .fright -->
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

