

        <!-- controllers.report success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.report delete -->
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

        <!-- controllers.report -->
        <section id="report" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-area-chart"></i>
                Weekly Reports

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/reports/add')" ng-if="hasPermission('reports.create')">
                        Add Report
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                 <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-left font2 color000">Week</th>
                            <th class="text-left font2 color000">Last Updated</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr ng-show="!list.loading" dir-paginate="report in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="color111">@{{ ( report.starttime * 1000 ) | date:'mediumDate' }} to @{{ ( report.endtime * 1000 ) | date:'mediumDate' }}</td>
                            <td class="color111" am-time-ago="report.updated_at | amUtc"></td>
                            <td class="color111 text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/reports/view/' + report.id )" ng-show="hasPermission('reports.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/reports/edit/' + report.id )" ng-show="hasPermission('reports.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( report )" ng-show="hasPermission('reports.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0 && !list.loading">
                            <td ng-if="hasPermission('reports.create')" class="text-center" colspan="100%">You haven't added any Weekly Reports!</td>
                            <td ng-if="!hasPermission('reports.create')" class="text-center" colspan="100%">No Weekly Reports Yet!</td>
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

