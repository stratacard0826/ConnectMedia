

        <!-- controllers.medical success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.medical delete -->
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

        <!-- controllers.medical -->
        <section id="medical" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-heartbeat"></i>
                Medical Referrals

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/medical/add')" ng-show="hasPermission('medical.create')">
                        Add Referral
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
                            <th class="text-left font2 color000">Date</th>
                            <th class="text-left font2 color000">Doctor</th>
                            <th class="text-left font2 color000">Store</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="medical in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ medical.id }}</th>
                            <td>@{{ medical.creator.firstname }} @{{ medical.creator.lastname }}</th>
                            <td> @{{ medical.date | date:'fullDate' }}</td>
                            <td>@{{ medical.doctor.firstname }} @{{ medical.doctor.lastname }}</td>
                            <td>@{{ medical.store.name }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/medical/view/' + medical.id )" ng-show="hasPermission('medical.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/medical/edit/' + medical.id )" ng-show="hasPermission('medical.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( medical )" ng-show="hasPermission('medical.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any Medical Referrals!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

