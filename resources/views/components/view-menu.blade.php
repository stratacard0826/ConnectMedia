
            <!-- components.view-news success -->
            <main id="recipe" ng-if="!loading">
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
                                Media
                            </a>
                        </li>
                        <li ng-class="tab == 3 ? 'active color000' : 'color777 hovercolor444'">
                            <a ng-click="tab = 3" class="font2">
                                Feedback
                            </a>
                        </li>
                        <li ng-class="tab == 4 ? 'active color000' : 'color777 hovercolor444'" ng-show="menu.faq.length">
                            <a ng-click="tab = 4" class="font2">
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
                            <div ng-if="menu.primary_media" class="media">
                                
                                <!-- Show Image -->
                                <img ng-if="menu.primary_media" ng-src="@{{ menu.primary_media.image }}" width="100%" />

                            </div>

                            <!-- Information -->
                            <div class="details">

                                <h1 class="color000 font2">@{{ menu.name }}</h1>
                                <p>@{{ menu.description }}</p>

                                <ul>

                                    <!-- Last Updated -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-calendar"></i>
                                            Last Updated:
                                        </strong>
                                        <span am-time-ago="menu.updated_at | amUtc"></span>
                                    </li>

                                    <!-- Item Type -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-tag"></i>
                                            Item Type:
                                        </strong>
                                        <div class="fleft">
                                            @{{ menu.type }}
                                        </div>
                                    </li>

                                    <!-- Item Status -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-power-off"></i>
                                            Item Status:
                                        </strong>
                                        <div class="fleft">
                                            <span class="status @{{ menu.status_class }}">
                                                @{{ menu.status }}
                                                <span ng-if="menu.status != 'Active' && menu.status_date">
                                                    - @{{ menu.status_date * 1000 | date:'mediumDate' }}
                                                </span>
                                            </span>
                                        </div>
                                    </li>

                                    <!-- Cook Time -->
                                    <li class="clearfix" ng-show="menu.cook_time.length">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-clock-o"></i>
                                            Cooking Time:
                                        </strong>
                                        <div class="fleft">
                                            @{{ menu.cook_time }}
                                        </div>
                                    </li>

                                    <!-- Calories -->
                                    <li class="clearfix" ng-show="menu.calories.length">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-bar-chart"></i>
                                            Total Calories:
                                        </strong>
                                        <div class="fleft">
                                            @{{ menu.calories }}
                                        </div>
                                    </li>

                                    <!-- Serving Dish(es) -->
                                    <li class="clearfix" ng-show="menu.serverware.length">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa"></i>
                                            Serving Dish:
                                        </strong>
                                        <div class="fleft">
                                            <div ng-repeat="serveware in menu.serveware">
                                                @{{ serveware.name }}                                                
                                                <i class="fa fa-info-circle" qtip="@{{ serveware.notes }}" ng-if="serveware.notes.length"></i>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Side Sauces / Dips -->
                                    <li class="clearfix" ng-show="menu.sides.length">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa"></i>
                                            Sauces / Dips:
                                        </strong>
                                        <div class="fleft">
                                            <div ng-repeat="side in menu.sides">
                                                @{{ side.volume }}
                                                @{{ side.unit }}
                                                @{{ side.name }}
                                                <i class="fa fa-info-circle" qtip="@{{ side.notes }}" ng-if="side.notes.length"></i>
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Gluten Free -->
                                    <li class="clearfix">
                                        <strong class="color000 font2 fleft">
                                            <i class="fa fa-check-square-o"></i>
                                            Gluten Free:
                                        </strong>
                                        <div class="fleft">
                                            @{{ menu.gluten_free ? 'Yes' : 'No' }}
                                            <i class="fa fa-info-circle" qtip="@{{ menu.gluten_free_notes }}" ng-if="!menu.gluten_free && menu.gluten_free_notes.length"></i>
                                        </div>
                                    </li>

                                    <!-- Red Flag Items -->
                                    <li class="clearfix" ng-show="menu.redflag.length">
                                        <strong class="colorred font2 fleft">
                                            <i class="fa fa-flag"></i>
                                            Red Flag:
                                        </strong>
                                        <div class="fleft">
                                            <div ng-repeat="redflag in menu.redflag">
                                                @{{ redflag.name }}
                                                <i class="fa fa-info-circle" qtip="@{{ redflag.notes }}" ng-if="redflag.notes.length"></i>
                                            </div>
                                        </div>
                                    </li>

                                </ul>

                            </div>

                        </article>
                        

                        <!-- Ingredients -->
                        <aside id="ingredients" class="col-md-4">

                            <header class="clearfix">

                                <h2 class="color000 font2 fleft">Ingredients</h2>

                                <select name="multiplier" ng-model="menu.multiplier" ng-init="menu.multiplier = '1'" class="fright">
                                    <option value="1" selected>Showing x1</option>
                                    <option value="2">Showing x2</option>
                                    <option value="5">Showing x5</option>
                                    <option value="10">Showing x10</option>
                                </select>

                            </header>

                            <ul>
                                <li class="clearfix" ng-repeat="ingredient in menu.ingredients">
                                    <span class="fleft ingredient">
                                        @{{ ingredient.name }}
                                        <i class="fa fa-info-circle" qtip="@{{ ingredient.notes }}" ng-if="ingredient.notes.length"></i>
                                    </span>
                                    <span class="fright total colorred">
                                        <span>
                                            @{{ ingredient.volume * menu.multiplier }}
                                        </span>
                                        @{{ ingredient.unit }}
                                    </span>
                                </li>
                            </ul>

                        </aside>

                    </section>

                    <section id="directions">

                        <header class="clearfix">

                            <h2 class="color000 font2 fleft">Method</h2>

                            <ul class="fright elapsed">
                                
                                <!-- Prep Time -->
                                <li class="coloraaa font2" ng-if="menu.prep_time">
                                    <i class="fa fa-cutlery fleft colorccc"></i>
                                    Prep: <strong class="color000">@{{ menu.prep_time }}m</strong>
                                </li>

                                <!-- Cook Time -->
                                <li class="coloraaa font2" ng-if="menu.cook_time">
                                    <i class="fa fa-stop-watch fleft colorccc"></i>
                                    Cook: <strong class="color000">@{{ menu.cook_time }}m</strong>
                                </li>

                                <!-- Total Time -->
                                <li class="coloraaa font2" ng-if="menu.prep_time && menu.cook_time">
                                    <i class="fa fa-clock-o fleft colorccc"></i>
                                    Total: <strong class="color000">@{{ menu.total_time }}m</strong>
                                </li>                                

                            </ul>

                            <small class="fleft color000 font2">
                                <strong>*Always wash your hands before handling any food or equipment</strong>
                            </small>

                        </header>

                        <ol>
                            <li class="clearfix" ng-repeat="direction in menu.directions">
                                <div class="number fleft">
                                    <span class="font2 coloraaa">@{{ direction.order }}</span>
                                </div>
                                <p class="direction fleft">
                                    @{{ direction.direction }}
                                </p>
                            </li>
                        </ol>

                    </section>

                </section>
                <section id="media" ng-show="tab == 2">

                    <ul>

                        <li class="fleft clearfix" ng-repeat="media in menu.media">

                            <!-- Media Item -->
                            <a class="media" ng-click="view(media)">
                                <img ng-src="@{{ media.thumbnail }}" />
                                <span ng-if="media.mime.type == 'video'" class="play">
                                    <i class="fa fa-play-circle color000"></i>
                                </span> 
                            </a>

                            <!-- Title -->
                            <h3 class="colorred font2 name">@{{ media.name }}</h3>

                            <!-- Published Details -->
                            <small class="color000 published">
                                <i class="fa fa-clock-o"></i>
                                Published: @{{ media.created_at_time * 1000 | date:longDate }}
                            </small>

                            <!-- View -->
                            <a class="color000 view" ng-click="view(media)">
                                <i class="fa fa-eye"></i>
                                View @{{ media.mime.type | capitalize }}
                            </a>

                            <!-- Download -->
                            <a class="color000 download" ng-click="download(media)">
                                <i class="fa fa-download"></i>
                                Download
                            </a>



                        </li>

                    </ul>

                </section>
                <form id="feedback" name="feedbackForm" ng-submit="sendFeedback( feedbackForm )" ng-show="tab == 3" ng-init="menu.feedback.type='Problem'">

                    <nav class="col-md-3">
                        <ul>
                            <li ng-repeat="(index , type) in [['times', 'Problem'], ['exclamation', 'Suggestion'], ['check', 'Compliment'], ['question', 'Other']]" ng-class="{ 'active' : menu.feedback.type == type[1] }" ng-click="menu.feedback.type=type[1]">
                                <a ng-class="{ 'colorfff hovercolorfff' : menu.feedback.type == type[1] , 'color555 hovercolor000' : menu.feedback.type != type[1] }"> 
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
                                <textarea id="feedback_textarea" name="feedback" placeholder="Enter the Feedback" ng-model="menu.feedback.message" required></textarea>
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
                <section id="faq" ng-show="tab == 4">

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






