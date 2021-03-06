

        <!-- controllers.store success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.store delete -->
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

        <!-- controllers.store -->
        <section id="store" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-map-marker"></i>
                Store Management

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/admin/stores/add')" ng-show="hasPermission('stores.create')">
                        Add Store
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Name</th>
                            <th class="text-left font2 color000">Address</th>
                            <th class="text-center font2 color000 actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- List -->
                        <tr dir-paginate="store in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ store.id }}</th>
                            <td> @{{ store.name }}</td>
                            <td>@{{ store.address }}, @{{ store.city }}, @{{ store.province }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-primary" ng-click="open('/admin/stores/edit/' + store.id )" ng-show="hasPermission('stores.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( store )" ng-show="hasPermission('stores.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">
                                You haven't added any Stores! 
                            </td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

