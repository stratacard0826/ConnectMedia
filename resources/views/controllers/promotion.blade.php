

        <!-- controllers.promotion success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.promotion delete -->
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

        <!-- controllers.promotion -->
        <section id="promotion" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-star"></i>
                Promotion Management

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/admin/promotions/add')" ng-show="hasPermission('promotions.create')">
                        Add Promotion
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
                            <th class="text-left font2 color000">Description</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="promotion in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ promotion.id }}</th>
                            <td> @{{ promotion.name }}</td>
                            <td>@{{ promotion.description }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-primary" ng-click="open('/admin/promotions/edit/' + promotion.id )" ng-show="hasPermission('promotions.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( promotion )" ng-show="hasPermission('promotions.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any Promotions!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

