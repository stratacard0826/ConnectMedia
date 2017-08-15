

        <!-- controllers.news success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.news delete -->
        <div class="bs-callout bs-callout-danger" ng-show="errors.length"> 
            <h4 class="font2">Form Errors</h4> 
            <div>
                <ul>
                    <li ng-repeat="error in errors">
                        @{{ error }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- controllers.news -->
        <section id="news" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-bell-o"></i>
                Company News

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/news/add')" ng-if="hasPermission('news.create')">
                        Add Article
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">

                 <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Author</th>
                            <th class="text-left font2 color000">Subject</th>
                            <th class="text-left font2 color000">Summary</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="article in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ article.id }}</th>
                            <td>@{{ article.user.firstname }} @{{ article.user.lastname }}</td>
                            <td>@{{ article.subject }}</td>
                            <td>@{{ article.summary }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/news/view/' + article.id )" ng-show="hasPermission('news.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/news/edit/' + article.id )" ng-show="hasPermission('news.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( article )" ng-show="hasPermission('news.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td ng-if="hasPermission('news.create')" class="text-center" colspan="100%">You haven't added any Articles!</td>
                            <td ng-if="!hasPermission('news.create')" class="text-center" colspan="100%">No Articles Yet!</td>
                        </tr>

                    </tbody>
                </table>



                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

