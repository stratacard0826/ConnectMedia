
            <!-- components.view-news success -->
            <main id="promo" ng-if="!loading">
                <nav>

                    <!-- Navigation -->
                    <ul class="nav nav-tabs">
                        <li ng-class="!tab || tab == 1 ? 'active color000' : 'color777 hovercolor444'">
                            <a ng-click="tab = 1" class="font2">
                                Overview
                            </a>
                        </li>
                        <li ng-class="tab == 2 ? 'active color000' : 'color777 hovercolor444'">
                            <a ng-click="tab = 2" class="font2">
                                Feedback
                            </a>
                        </li>
                        <li ng-class="tab == 3 ? 'active color000' : 'color777 hovercolor444'" ng-show="promo.faq.length">
                            <a ng-click="tab = 3" class="font2">
                                FAQ
                            </a>
                        </li>
                    </ul>

                </nav>
                <section ng-show="!tab || tab == 1" class="clearfix">

                    <section class="top clearfix">

                        <!-- Details -->
                        <article id="information" class="col-md-8 clearfix">

                            <!-- Media -->
                            <div ng-if="promo.attachment_id" class="media">
                                
                                <!-- Show Image -->
                                <img ng-if="promo.attachment_id" ng-src="@{{ promo.attachment_id.image ? promo.attachment_id.image : '/public/assets/images/default-document-large.jpg' }}" width="100%" />

                            </div>

                            <!-- Information -->
                            <div class="details">

                                <h1 class="color000 font2">@{{ promo.name }}</h1>
                                <p>@{{ promo.description }}</p>

                                <ul>

                                    <!-- Last Updated -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-clock-o"></i>
                                            Last Updated:
                                        </strong>
                                        <span am-time-ago="promo.updated_at | amUtc"></span>
                                    </li>

                                    <!-- Promotion Date -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-calendar"></i>
                                            Promotion Date:
                                        </strong>
                                        <span>
                                            <time datetime="@{{ promo.start }}">@{{ promo.start_time * 1000 | date:'mediumDate' }}</time> to <time datetime="@{{ promo.end }}">@{{ promo.end_time * 1000 | date:'mediumDate' }}</time>
                                        </span>
                                    </li>

                                    <!-- Item Status -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-power-off"></i>
                                            Promotion Status:
                                        </strong>
                                        <div class="fleft">
                                            <span class="status @{{ promo.status_class }}">
                                                @{{ promo.status }}
                                            </span>
                                        </div>
                                    </li>

                                </ul>

                            </div>

                        </article>

                    </section>

                    <section id="downloads" ng-show="promo.files.length > 0">

                        <header class="clearfix">

                            <h2 class="color000 font2">Downloads</h2>

                        </header>

                        <!-- List -->
                        <ul class="list">
                            <li ng-repeat="file in promo.files">

                                <!-- Main Image -->
                                <div class="image">
                                    <img ng-src="@{{ file.image ? file.thumbnail : '/public/assets/images/default-document.jpg' }}" />
                                </div>

                                <!-- Title -->
                                <h2>
                                    <a class="colorred hovercolorred font2">
                                        @{{ file.name }}
                                    </a>
                                </h2>

                                <!-- Created Date & Document Type -->
                                <div class="details color000 clearfix">
                                    <time datetime="@{{ file.updated_at_time * 1000 | date:'yyyy-MM-dd HH:mm:ss' }}" class="fleft">
                                        <i class="fa fa-clock-o"></i>
                                        @{{ file.updated_at_time * 1000 | date:'mediumDate'  }}
                                    </time>
                                    <div class="fright category">
                                        <i class="fa fa-file"></i>
                                        @{{ file.category.length ? file.category : 'None' }}
                                    </div>
                                </div>

                                <!-- View File & Download -->
                                <div class="actions color000 clearfix">
                                    <a class="fleft action color000 hovercolor000" ng-show="file.image.length > 0" ng-click="view( file )">
                                        <i class="fa fa-eye"></i>
                                        View Files
                                    </a>
                                    <a class="fright action color000 hovercolor000" ng-click="download( file )">
                                        <i class="fa fa-download"></i>
                                        Download
                                    </a>
                                </div>

                            </li>
                        </ul>

                    </section>

                </section>
                <section ng-show="tab == 2">
                <form id="feedback" name="feedbackForm" ng-submit="sendFeedback( feedbackForm )" ng-init="promo.feedback.type='Problem'">

                    <nav class="col-md-3">
                        <ul>
                            <li ng-repeat="(index , type) in [['times', 'Problem'], ['exclamation', 'Suggestion'], ['check', 'Compliment'], ['question', 'Other']]" ng-class="{ 'active' : promo.feedback.type == type[1] }" ng-click="promo.feedback.type=type[1]">
                                <a ng-class="{ 'colorfff hovercolorfff' : promo.feedback.type == type[1] , 'color555 hovercolor000' : promo.feedback.type != type[1] }"> 
                                    <span class="icon colorccc">
                                        <i class="fa fa-@{{ type[0] }}-circle"></i>
                                    </span>
                                    <span class="text">
                                        @{{ type[1] }}
                                    </span>
                                    <span class="arrow"></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="col-md-9">    

                        <div class="form-group">                   

                            <!-- Add Feedback -->
                            <div class="form-group clearfix" ng-class="{ 'has-error' : feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty , 'has-success' : !feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty }">
                                <label class="hide" for="feedback_textarea">Enter the Feedback:</label>
                                <textarea id="feedback_textarea" name="feedback" placeholder="Enter the Feedback" ng-model="promo.feedback.message" required></textarea>
                                <i class="fa fa-comment color888 fleft"></i>
                                <span class="glyphicon glyphicon-remove form-control-feedback push-right" ng-show="feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty"></span>
                                <span class="glyphicon glyphicon-ok form-control-feedback push-right" ng-show="!feedbackForm.feedback.$invalid && feedbackForm.feedback.$dirty"></span>
                            </div>

                        </div>


                        <div class="form-group">                            

                            <div class="fright">
                            
                                <!-- Submitting Wait Notice -->
                                <small class="colorinfo text-right submitnotice" ng-show="sending">
                                    <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                    Sending Feedback - Please wait.
                                </small>
                            
                                <!-- Form Error Notices -->
                                <small class="colorred submitnotice" ng-show="hasError( feedbackForm )">
                                    You must fix any form errors before proceeding
                                </small>

                                <!-- Submit Button -->
                                <button name="submit" class="btn fright" ng-class="hasError( feedbackForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="feedbackForm.$pristine || feedbackForm.$invalid || sending">
                                    <i class="fa fa-paper-plane"></i>
                                    Send Feedback
                                </button>

                            <!-- /End .fright -->
                            </div>

                        <!-- /End .form-group -->
                        </div>

                    </div>

                </form>
                </section>
                <section id="faq" ng-show="tab == 3">

                    <nav class="col-md-3">

                        <!-- Navigation -->
                        <ul>
                            <li ng-repeat="( cat , obj ) in faqs" ng-class="{ 'active' : ( $index == 0 && !faq ) || $parent.faq == $index }" ng-click="$parent.faq = $index; log( $index );">
                                <a ng-class="{ 'colorfff hovercolorfff' : ( $index == 0 && !faq ) || $parent.faq == $index , 'color555 hovercolor000' : $parent.faq != $index }">
                                    <span class="icon colorccc">
                                        <i class="fa fa-@{{ obj.icon }}"></i>
                                    </span>
                                    <span class="text">
                                        @{{ cat }}
                                    </span>
                                    <span class="arrow"></span>
                                </li>
                                </a>
                            </li>
                        </ul>

                    </nav>

                    <!-- General Questions -->
                    <div ng-repeat="( cat , obj ) in faqs">
                        <uib-accordion close-others="true" class="col-md-9" ng-show="( !faq && $index == 0 ) || faq == $index">
                            <uib-accordion-group ng-repeat="(index , q) in faqs[ cat ].list" ng-init="status.open = ( index == 0 )" heading="@{{ ( index + 1 ) + '. ' + q.question }}" is-open="status.open">
                                @{{ q.answer }}
                            </uib-accordion-group>
                        </uib-accordion>
                    </div>

                </section>
            </main>






