
            <!-- components.view-news success -->
            <main id="news-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <article class="panel-body">

                    <header class="clearfix">
                        
                        <small class="fleft">
                            Posted By: <i class="fa fa-user"></i> <strong>@{{ article.author.firstname }} @{{ article.author.lastname }}</strong> on <i class="fa fa-calendar"></i> <strong>@{{ article.created }}</strong>
                        </small>

                        <ul class="fright">
                            <li ng-repeat="attachment in article.attachments">
                                <a ng-click="link( ':api/download/' + attachment.slug )">
                                    <small class="color000">
                                        <i class="fa fa-file"></i>
                                        @{{ attachment.filename }}
                                    </small>
                                </a>
                            </li>
                        </ul>

                    </header>

                    <hr />

                    <h1 class="font2">@{{ article.subject }}</h1>

                    <hr />

                    <div ng-bind-html="article.article" class="color000"></div>

                    <footer ng-if="hasPermission('news')">

                        <a ng-click="back('/news(/[0-9]+)?' , '/news')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

                    </footer>
                </article>
            </main>
