
            <!-- components.view-event -->
            <main id="event-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <article class="panel-body">

                    <header class="clearfix">
                        
                        <small class="fleft">
                            Posted By: <i class="fa fa-user"></i> <strong>@{{ event.author.firstname }} @{{ event.author.lastname }}</strong> on <i class="fa fa-calendar"></i> <strong>@{{ event.created }}</strong>
                        </small>

                        <ul class="fright">
                            <li ng-repeat="attachment in event.attachments">
                                <a ng-click="link( ':api/download/' + attachment.slug )">
                                    <small class="color000">
                                        <i class="fa fa-file"></i>
                                        @{{ attachment.filename }}
                                    </small>
                                </a>
                            </li>
                        </ul>

                        <small class="fright">
                            <i class="fa fa-calendar"></i> <strong>@{{ event.start }}</strong> to <i class="fa fa-calendar"></i> <strong>@{{ event.end }}</strong>
                        </small>

                    </header>

                    <h1 class="font2">
                        @{{ event.name }}
                    </h1>

                    <hr />

                    <div ng-bind-html="event.details" class="color000">
                        <i class="fa fa-bookmark color888 fleft"></i>
                    </div>

                    <footer ng-if="hasPermission('events')">

                        <a ng-click="back('/admin/events(/[0-9]+)?' , '/admin/events')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

                    </footer>
                </article>
            </main>
