
        <!-- components.manage-promo -->
        <form name="promoForm" method="post" ng-submit="submit( promoForm )" novalidate>


            <!-- components.manage-promo success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && promoForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-promo error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( promoForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>
                    
                        <!-- Name Error -->
                        <li ng-show="promoForm.error.name = ( promoForm.name.$dirty && promoForm.name.$invalid )" ng-messages="promoForm.name.$error">
                            <span ng-message="required">Name is required</span>
                        </li>
                    
                        <!-- Description Error -->
                        <li ng-show="promoForm.error.description = ( promoForm.description.$dirty && promoForm.description.$invalid )" ng-messages="promoForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>
                    
                        <!-- Start Date Error -->
                        <li ng-show="promoForm.error.start = ( promoForm.start.$dirty && promoForm.start.$invalid )" ng-messages="promoForm.start.$error">
                            <span ng-message="required">Promotion Start Date is required</span>
                            <span ng-message="date-max">Start Date cannot start after the End Date</span>
                            <span ng-message="date-invalid">Start Date Requires a Valid Date</span>
                        </li>
                    
                        <!-- End Date Error -->
                        <li ng-show="promoForm.error.end = ( promoForm.end.$dirty && promoForm.end.$invalid )" ng-messages="promoForm.end.$error">
                            <span ng-message="required">End Date is required</span>
                            <span ng-message="date-min">End Date cannot start before the Start Date</span>
                            <span ng-message="date-invalid">End Date Requires a Valid Date</span>
                        </li>

                        <!-- FAQ Error -->
                        <li ng-repeat="(index , faq) in promo.faq" ng-show=" promoForm.error[ 'faq' + index + 'category'] || promoForm.error[ 'faq' + index + 'question' ] || promoForm.error[ 'faq' + index + 'answer' ]">
                            FAQ @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Sides Name -->
                                <li ng-show="promoForm.error[ 'faq' + index + 'category' ] = hasArrayError( 'faq' , index , 'category' )" ng-messages="promoForm[ 'faq[' + index + '][category]' ].$error">
                                    <span ng-message="required">Category is required</span>
                                </li>

                                <!-- Sides Volume -->
                                <li ng-show="promoForm.error[ 'faq' + index + 'question' ] = hasArrayError( 'faq' , index , 'question' )" ng-messages="promoForm[ 'faq[' + index + '][question]' ].$error">
                                    <span ng-message="required">Question is required</span>
                                </li>

                                <!-- Sides Unit -->
                                <li ng-show="promoForm.error[ 'faq' + index + 'answer' ] = hasArrayError( 'faq' , index , 'answer' )" ng-messages="promoForm[ 'faq[' + index + '][answer]' ].$error">
                                    <span ng-message="required">Answer is required</span>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-promo -->
            <main id="promo-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                    <div class="form-group fright">
                        
                        <!-- Form Error Notices -->
                        <small class="colorred text-right fleft submitnotice" ng-show="hasError( promoForm ) || Upload.isUploadInProgress()">
                            <span ng-show="hasError( promoForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                            <span ng-show="Upload.isUploadInProgress()">You must wait for the uploads before proceeding</span>
                        </small>
                            
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} promotion - Please wait.
                        </small>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn-sm fright" ng-class="hasError( promoForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="promoForm.$invalid || sending || Upload.isUploadInProgress()">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .form-group -->
                    </div>

                </header>
                <div class="panel-body">

                    <div class="promo-panel">

                        <!-- Navigation -->
                        <ul class="nav nav-tabs">
                            <li ng-class="!tab || tab == 1 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 1" class="font2">
                                    <i class="fa fa-info-circle"></i>
                                    Overview
                                </a>
                            </li>
                            <li ng-class="tab == 7 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 7" class="font2">
                                    <i class="fa fa-file"></i>
                                    Documents
                                </a>
                            </li>
                            <li ng-class="tab == 8 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 8" class="font2">
                                    <i class="fa fa-question-circle"></i>
                                    FAQ
                                </a>
                            </li>
                        </ul>

                        <!-- Tabs -->
                        <div class="tab-content">

                            <!-- Overview Tab -->
                            <div class="tab-pane" ng-class="!tab || tab == 1 ? 'active' : ''" ng-show="!tab || tab == 1">

                                <p>Fill out the fields below to add your Menu Item</p>

                                <!-- Menu Name -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : promoForm.name.$invalid && promoForm.name.$dirty , 'has-success' : !promoForm.name.$invalid && promoForm.name.$dirty }">
                                    <label class="hide" for="name">Name:</label>
                                    <input type="text" id="name" name="name" placeholder="Name" ng-model="promo.name" required is-empty />
                                    <i class="fa fa-asterisk color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promoForm.name.$invalid && promoForm.name.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promoForm.name.$invalid && promoForm.name.$dirty"></span>
                                </div>

                                <!-- Menu Description -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : promoForm.description.$invalid && promoForm.description.$dirty , 'has-success' : !promoForm.description.$invalid && promoForm.description.$dirty }">
                                    <label class="hide" for="name">Description:</label>
                                    <textarea id="description" name="description" placeholder="Description" ng-model="promo.description" required is-empty></textarea>
                                    <i class="fa fa-info-circle color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promoForm.description.$invalid && promoForm.description.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promoForm.description.$invalid && promoForm.description.$dirty"></span>
                                </div>

                                <!-- Start Date -->
                                <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="promo.calendar[0].open" ng-class="{ 'has-error' : promoForm.start.$invalid && promoForm.start.$dirty , 'has-success' : !promoForm.start.$invalid && promoForm.start.$dirty }">
                                    <label class="hide" for="start">Start Date:</label>
                                    <input type="datetime" id="start" name="start" ng-model="promo.start" placeholder="Start Date" date-compare date-max="promo.end" autocomplete="off" required is-empty />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promoForm.start.$invalid && promoForm.start.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promoForm.start.$invalid && promoForm.start.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( promo.calendar[0].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="promo.start" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( promoForm , 'start' , 1 )"></datetimepicker>
                                    </div>
                                </div>

                                <!-- End Date -->
                                <div class="form-group clearfix" uib-dropdown uib-dropdown-toggle is-open="promo.calendar[1].open" ng-class="{ 'has-error' : promoForm.end.$invalid && promoForm.end.$dirty , 'has-success' : !promoForm.end.$invalid && promoForm.end.$dirty }">
                                    <label class="hide" for="end">End Date:</label>
                                    <input type="datetime" id="end" name="end" ng-model="promo.end" placeholder="End Date" date-compare date-min="promo.start"  autocomplete="off" required is-empty />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="promoForm.end.$invalid && promoForm.end.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!promoForm.end.$invalid && promoForm.end.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( promo.calendar[1].open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="promo.end" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( promoForm , 'end' , 2 )"></datetimepicker>
                                    </div>
                                </div>


                            <!-- /End .tab-pane -->
                            </div>




                            <!-- Media Tab -->
                            <div class="tab-pane" ng-class="tab == 7 ? 'active' : ''" ng-show="tab == 7">
                                <p>
                                    Attach a File:
                                    <small><em>Note: Check the "Checkbox" Beside the Media Item to set it as the Primary Media for the Food &amp; Menu Item</em></small>
                                </p>

                                <!-- File Drop Zone -->
                                <div class="form-group">
                                    <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ng-model="promo.files" class="drop-area text-center" ngf-multiple="true">
                                        
                                        <i class="fa fa-paperclip color888"></i>
                                        Drop Attachment / Click Here

                                    </div>
                                </div>

                                <!-- File List -->
                                <ul class="files clearfix">
                                    <li ng-repeat="( index , file ) in promo.files" class="fleft clearfix">

                                        <progress max="100" value="@{{ file.progress }}" ng-class="file.status ? file.status : null"></progress>
                                    
                                        <div class="clearfix">                                             
                                            <small class="fleft font2">
                                                <input type="checkbox" name="primary" ng-model="promo.attachment_id" value="@{{ file.attachment_id }}" class="fleft" ng-click="select( promo.files , index )" ng-checked="file.checked" />
                                                <input type="text" name="name" ng-model="file.custom_name" value="@{{ file.name }}" class="fleft" ng-init="file.custom_name = file.name" />
                                            </small>
                                            <i class="fa fa-times fright" ng-click="remove( file , index )"></i>
                                        </div>

                                        <div class="clearfix">
                                            <select name="status" ng-model="file.category">
                                                <option value="">Select Document Type</option>
                                                <option value="Print">Print</option>
                                                <option value="Web">Web</option>
                                                <option value="Email">Email</option>
                                                <option value="Social Media">Social Media</option>
                                                <option value="Document">Document</option>
                                                <option value="Document">Other</option>
                                            </select>
                                        </div>

                                    </li>
                                </ul>

                            <!-- /End .tab-pane -->
                            </div>



                            <!-- FAQ Tab -->
                            <div class="tab-pane" ng-class="tab == 8 ? 'active' : ''" ng-show="tab == 8">
                                
                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Question</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="promo.faq.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Question
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered">
                                        <tbody>

                                            <tr ng-repeat="(index, faq) in promo.faq">

                                                <!-- Q&A -->
                                                <td>

                                                    <!-- Category -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'category' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'category' ) }">
                                                        <label class="hide" for="side-category-@{{ index }}">Unit</label>
                                                        <div class="select">
                                                            <select id="side-category-@{{ index }}" name="faq[@{{ index }}][category]" ng-model="promo.faq[ index ].category" required>
                                                                <option value="" disabled selected hidden>Category</option>
                                                                <option value="General Questions">General Questions</option>
                                                                <option value="Tips for Marketing">Tips for Marketing</option>
                                                                <option value="Customer Promotions">Customer Promotions</option>
                                                                <option value="Branding">Branding</option>
                                                                <option value="Other Questions">Other Questions</option>
                                                            </select>
                                                        </div>
                                                        <i class="fa fa-bookmark color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'category' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'category' )"></span>
                                                    </div>

                                                    <!-- Question -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'question' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'question' ) }">
                                                        <label class="hide" for="faq-question-@{{ index }}">Question</label>
                                                        <input type="text" id="faq-question-@{{ index }}" name="faq[@{{ index }}][question]" placeholder="Question" ng-model="promo.faq[ index ].question" required />
                                                        <i class="fa fa-question-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'question' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'faq' , index , 'question' )"></span>
                                                    </div>

                                                    <!-- Answer -->
                                                    <div class="form-group nospace" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'answer' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'answer' ) }">
                                                        <label class="hide" for="faq-answer-@{{ index }}">Answer</label>
                                                        <textarea id="faq-answer-@{{ index }}" name="faq[@{{ index }}][answer]" placeholder="Answer" ng-model="promo.faq[ index ].answer" required></textarea>
                                                        <i class="fa fa-exclamation-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'answer' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'faq' , index , 'answer' )"></span>
                                                    </div>

                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="promo.faq.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                            <!-- /End .tab-pane -->
                            </div>

                        <!-- /End .tab-content -->
                        </div>

                    <!-- /End .promo-panel -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

