

        <!-- controllers.bar success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.bar delete -->
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

        <!-- controllers.bar -->
        <section id="bar" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-martini"></i>
                Bar &amp; Drinks

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/bar/add')" ng-if="hasPermission('bar.create')">
                        Add Recipe
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">

                <form class="search clearfix" name="searchForm" ng-submit="search( searchForm )" novalidate>
                    <label for="search" class="color000 font2 hide">Search:</label>
                    <input type="text" name="query" ng-model="list.query" placeholder="Search" ng-keyup="search( searchForm )" />
                    <i class="fa fa-sitemap color888 fleft"></i>
                    <button name="submit" class="fa fa-search"></button>
                </form>

                 <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center" width="80"></th>
                            <th class="text-left font2 color000">Name</th>
                            <th class="text-left font2 color000">Type</th>
                            <th class="text-left font2 color000">Last Updated</th>
                            <th class="text-left font2 color000">Status</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr ng-show="!list.loading" dir-paginate="bar in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="color111 text-center">
                                <img ng-src="@{{ bar.image }}" ng-if="bar.image" width="75" height="50" />
                            </td>
                            <td class="color111">@{{ bar.name }}</td>
                            <td class="color111">@{{ bar.type }}</td>
                            <td class="color111" am-time-ago="bar.updated_at | amUtc"></td>
                            <td class="color111">                                
                                <span class="status @{{ bar.status_class }}">
                                    @{{ bar.status }}
                                    <span ng-if="bar.status != 'Active' && bar.status_date">
                                        - @{{ bar.status_date * 1000 | date:'mediumDate' }}
                                    </span>
                                </span>
                            </td>
                            <td class="color111 text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/bar/view/' + bar.id )" ng-show="hasPermission('bar.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/bar/edit/' + bar.id )" ng-show="hasPermission('bar.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( bar )" ng-show="hasPermission('bar.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0 && !list.loading">
                            <td ng-if="hasPermission('bar.create')" class="text-center" colspan="100%">You haven't added any Drink Items!</td>
                            <td ng-if="!hasPermission('bar.create')" class="text-center" colspan="100%">No Drink Items Yet!</td>
                        </tr>

                        <!-- Loading -->
                        <tr ng-show="list.loading">
                            <td colspan="100%" class="bgfff">
                                <div class="text-center font2 color000">
                                    <i class="fa fa-circle-o-notch fa-spin color000" style="font-size:20px;margin-right:5px;"></i>
                                    <span style="font-size:15px;">Loading</span>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>



                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

