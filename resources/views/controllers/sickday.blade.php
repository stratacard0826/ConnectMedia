

        <!-- controllers.sick success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.sick delete -->
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

        <!-- controllers.sick -->
        <section id="sick" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-ambulance"></i>
                Sick Days

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/admin/sickday/add')" ng-show="hasPermission('sickdays.create')">
                        Add Sick Day
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
                            <th class="text-left font2 color000">User</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="sickday in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ sickday.id }}</th>
                            <td> @{{ sickday.date | date:'fullDate' }}</td>
                            <td>@{{ sickday.user.firstname }} @{{ sickday.user.lastname }}</td>
                            <td>@{{ sickday.store.name }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/admin/sickday/view/' + sickday.id )" ng-show="hasPermission('sickdays.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/admin/sickday/edit/' + sickday.id )" ng-show="hasPermission('sickdays.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( sickday )" ng-show="hasPermission('sickdays.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any Sick Days!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

