

        <!-- controllers.logout success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.logout delete -->
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

        <!-- controllers.logout -->
        <section id="logout" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-sign-out"></i>
                Buyers Logout

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fleft colorfff font2 btn-sm btn-primary" ng-click="open('/buyers/report')" ng-show="hasPermission('buyerslogouts.report')">
                        View Report
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/buyers/add')" ng-show="hasPermission('buyerslogouts.create')">
                        Add logout
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Date</th>
                            <th class="text-left font2 color000">Store</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="logout in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ logout.id }}</th>
                            <td> @{{ logout.date | date:'fullDate' }}</td>
                            <td>@{{ logout.store.name }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/buyers/view/' + logout.id )" ng-show="hasPermission('buyerslogouts.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/buyers/edit/' + logout.id )" ng-show="hasPermission('buyerslogouts.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( logout )" ng-show="hasPermission('buyerslogouts.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any logouts!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

