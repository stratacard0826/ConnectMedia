

        <!-- controllers.payroll success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.payroll delete -->
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

        <!-- controllers.payroll -->
        <section id="payroll" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-money"></i>
                Payroll

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/payrolls/add')" ng-show="hasPermission('payrolls.create')">
                        Add Payroll
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Start Date</th>
                            <th class="text-left font2 color000">End Date</th>
                            <th class="text-left font2 color000">Store</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="payroll in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ payroll.id }}</td>
                            <td>@{{ payroll.starttime | date:'fullDate' }}</td>
                            <td>@{{ payroll.endtime | date:'fullDate' }}</td>
                            <td>@{{ payroll.store.name }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/payrolls/view/' + payroll.id )" ng-show="hasPermission('payrolls.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/payrolls/edit/' + payroll.id )" ng-show="hasPermission('payrolls.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( payroll )" ng-show="hasPermission('payrolls.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any Payrolls!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

