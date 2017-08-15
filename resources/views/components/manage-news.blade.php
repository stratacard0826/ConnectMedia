
        <form name="newsForm" method="post" ng-submit="submit( newsForm )" novalidate>
        
            <!-- components.manage-user success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-user error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( newsForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Article Error -->
                        <li ng-show="newsForm.error.article = ( newsForm.article.$dirty && newsForm.article.$invalid )" ng-messages="newsForm.article.$error">
                            <span ng-message="required">Article is required</span>
                        </li>

                        <!-- Subject Error -->
                        <li ng-show="newsForm.error.subject = ( newsForm.subject.$dirty && newsForm.subject.$invalid )" ng-messages="newsForm.subject.$error">
                            <span ng-message="required">Subject is required</span>
                            <span ng-message="minlength">Subject must be between 1 and 100 characters</span>
                            <span ng-message="maxlength">Subject must be between 1 and 100 characters</span>
                        </li>

                        <!-- Event Start Time Error -->
                        <li ng-show="newsForm.error.start = ( news.createevent == '1' && ( newsForm.start.$dirty && newsForm.start.$invalid ) )" ng-messages="newsForm.start.$error">
                            <span ng-message="required">Start Time is required</span>
                            <span ng-message="date-min">Start Time must begin after today</span>
                            <span ng-message="date-max">Start Time cannot start after the End Time</span>
                            <span ng-message="date-invalid">Start Time Requires a Valid Date</span>
                        </li>

                        <!-- Event End Time Error -->
                        <li ng-show="newsForm.error.end = ( news.createevent == '1' && ( newsForm.end.$dirty && newsForm.end.$invalid ) )" ng-messages="newsForm.end.$error">
                            <span ng-message="required">End Time is required</span>
                            <span ng-message="date-min">End Time cannot start before the Start Time</span>
                            <span ng-message="date-invalid">End Time Requires a Valid Date</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-news success -->
            <main id="news-management" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">
                
                    <p>Who has access to the Article?: <small>( <em>Leave Blank for Everyone</em> )</small></p>

                    <!-- Stores -->
                    <div class="form-group" ng-if="stores" ng-class="{ 'has-success' : !newsForm.stores.$invalid && newsForm.stores.$dirty }">
                        <label class="hide" for="stores">Stores:</label>
                        <multiselect id="stores" name="stores" ng-model="news.stores" ng-value="news.stores" options="stores.data" selected-model="news.stores" extra-settings="stores.settings" news="stores.news" translation-texts="{ buttonDefaultText: 'Users in Stores' }" ng-dropdown-multiselect="stores"></multiselect>
                        <i class="fa fa-map-marker color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.stores.$invalid && newsForm.stores.$dirty"></span>
                    </div>

                    <!-- Roles -->
                    <div class="form-group" ng-if="roles" ng-class="{ 'has-success' : !newsForm.roles.$invalid && newsForm.roles.$dirty }">
                        <label class="hide" for="roles">Roles:</label>
                        <multiselect id="roles" name="roles" ng-model="news.roles" ng-value="news.roles" options="roles.data" selected-model="news.roles" extra-settings="roles.settings" news="roles.news" translation-texts="{ buttonDefaultText: 'Users with Roles' }" ng-dropdown-multiselect="roles"></multiselect>
                        <i class="fa fa-lock color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.roles.$invalid && newsForm.roles.$dirty"></span>
                    </div>

                    <p>Do you want to create an event for this article?:</p>

                            
                    <!-- Send Email Button -->
                    <div class="form-group clearfix">
                        <label class="hide" for="createevent">Create Event:</label>
                        <div class="select">
                            <select type="text" id="createevent" name="createevent" ng-model="news.createevent">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        <i class="fa fa-calendar color888 fleft"></i>
                    </div>

                    <!-- Date Range -->
                    <div ng-show="news.createevent == '1'">

                        <p>Between what date range does the event take place?:</p>

                        <!-- Start Date -->
                        <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="news.calendars[0].open" ng-class="{ 'has-error' : newsForm.start.$invalid && newsForm.start.$dirty , 'has-success' : !newsForm.start.$invalid && newsForm.start.$dirty }">
                            <label class="hide" for="start">Event Start:</label>
                            <input type="datetime" id="start" name="start" ng-model="news.start" placeholder="Start Time" date-compare date-min="today" date-max="news.end" autocomplete="off" ng-required="news.createevent == '1'" required="@{{ news.createevent == '1' ? 'true' : 'false' }}" />
                            <i class="fa fa-clock-o color888 fleft"></i>
                            <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="newsForm.start.$invalid && newsForm.start.$dirty"></span>
                            <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.start.$invalid && newsForm.start.$dirty"></span>
                            <span class="input-group-btn">
                                <button type="button" class="btn" ng-class="( news.calendars[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                            </span>
                            <div uib-dropdown-menu>
                                <datetimepicker data-ng-model="news.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( newsForm , 'start' , 1 )" data-before-render="setAllowedDates( $view , $dates , $leftDate, $upDate, $rightDate )"></datetimepicker>
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="news.calendars[1].open" ng-class="{ 'has-error' : newsForm.end.$invalid && newsForm.end.$dirty , 'has-success' : !newsForm.end.$invalid && newsForm.end.$dirty }">
                            <label class="hide" for="end">Event End:</label>
                            <input type="datetime" id="end" name="end" ng-model="news.end" placeholder="End Time" date-compare date-min="news.start" autocomplete="off" ng-required="news.createevent == '1'" required="@{{ news.createevent == '1' ? 'true' : 'false' }}" />
                            <i class="fa fa-clock-o color888 fleft"></i>
                            <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="newsForm.end.$invalid && newsForm.end.$dirty"></span>
                            <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.end.$invalid && newsForm.end.$dirty"></span>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-class="( news.calendars[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                            </span>
                            <div uib-dropdown-menu>
                                <datetimepicker data-ng-model="news.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD HH:mm:ss' }" data-on-set-time="dateSelect( newsForm , 'end' , 2 )" data-before-render="setAllowedDates( $view , $dates , $leftDate, $upDate, $rightDate )"></datetimepicker>
                            </div>
                        </div>

                    </div>
                    <div ng-if="news.createevent == '1'">
                        @include('components/reminders')  
                    </div>                  

                    <p>Attach a File:</p>

                    <!-- File Drop Zone -->
                    <div class="form-group">
                        <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ngf-max-size="'10MB'" ng-model="news.files" class="drop-area text-center" ngf-multiple="true">
                            
                            <i class="fa fa-paperclip color888"></i>
                            Drop Attachment / Click Here

                        </div>
                    </div>

                    <!-- File List -->
                    <ul class="files clearfix">
                        <li ng-repeat="file in news.files" class="fleft clearfix">
                            <progress max="100" value="@{{ file.progress }}" ng-class="file.status ? file.status : null"></progress>
                            <small class="fleft font2">@{{ file.name }}</small>
                            <i class="fa fa-times fright" ng-click="remove( $index )"></i>
                        </li>
                    </ul>

                    <p>Create your News Article:</p>

                    <!-- Subject -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : newsForm.subject.$invalid && newsForm.subject.$dirty , 'has-success' : !newsForm.subject.$invalid && newsForm.subject.$dirty }">
                        <label class="hide" for="subject">Subject:</label>
                        <input id="subject" type="text" name="subject" ng-model="news.subject" ng-value="news.subject" placeholder="Subject" ng-minlength="1" ng-maxlength="100" maxlength="100" required />
                        <i class="fa fa-bookmark color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="newsForm.subject.$invalid && newsForm.subject.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.subject.$invalid && newsForm.subject.$dirty"></span>
                    </div>

                    <!-- Article -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : newsForm.article.$invalid && newsForm.article.$dirty , 'has-success' : !newsForm.article.$invalid && newsForm.article.$dirty }">
                        <label class="hide" for="article">Article:</label>
                        <text-angular id="article" name="article" ng-model="news.article" placeholder="Article" ng-value="news.article" ta-toolbar="[['bold', 'italics', 'ul', 'ol', 'quote', 'insertLink']]" ng-if="action" required></text-angular> 
                        <!-- Note: text-angular[ng-if="action"] Prnews double loading when switching from Edit / Insert Page -->
                        <i class="fa fa-paragraph color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="newsForm.article.$invalid && newsForm.article.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!newsForm.article.$invalid && newsForm.article.$dirty"></span>
                    </div>

                    <div class="form-group">

                        <!-- Back -->
                        <div class="fleft">
                            <a ng-click="back('/news(/[0-9]+)?' , '/news')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
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
                            <small class="colorred text-right submitnotice" ng-show="hasError( newsForm ) || Upload.isUploadInProgress()">
                                <span ng-show="hasError( newsForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                                <span ng-show="Upload.isUploadInProgress()">You must wait for the uploads before proceeding</span>
                            </small>

                            <div>
                            
                                <!-- Send Email Button -->
                                <p class="fleft" style="padding-right:10px;">
                                    Send Email:
                                    <input type="checkbox" name="sendemail" ng-model="news.sendemail" ng-checked="news.sendemail" ng-true-value="1" />
                                </p>

                                <!-- Submit -->
                                <button name="submit" class="btn fright" ng-class="hasError( newsForm ) || errors.length ? 'btn-danger' : 'btn-primary'" style="clear:right;" ng-disabled="newsForm.$invalid || Upload.isUploadInProgress() || sending">
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

