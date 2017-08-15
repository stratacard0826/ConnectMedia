
    <!-- components.view-report -->
    <main id="view-report" class="border panel panel-default">
        <header class="font2 panel-heading">
            
            <i class="fa fa-@{{ icon }}"></i>
            @{{ title }}: @{{ report.name }}

        </header>
        <div class="panel-body">

            <h2 class="color000 font2">
                Stores are Ordered based on Highest Weekly Performance
                <small class="font1">* Note: You can only download files from stores you're assigned to</small>
            </h2>

            <!-- Files Upload -->                    
             <table class="table table-bordered table-striped list">
                <thead>
                    <tr>
                        <th width="50" class="text-center font2">Order</th>
                        <th class="text-left font2 color000">Store</th>
                        <th width="50" class="text-center font2 color000">Report</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="( index , item ) in report.files" ng-show="item.exists">
                        <td class="text-center">
                            @{{ index + 1 }}
                        </td>
                        <td>@{{ item.store.name }}</td>
                        <td class="text-center">
                            <a ng-click="link( ':api/download/' + item.file.slug )" class="color000 hovercolor000" ng-show="item.file">
                                <i class="fa fa-file" class="color000"></i>
                            </a>
                            <i class="fa fa-file coloraaa" ng-show="!item.file" disabled></i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Submit Actions -->
            <div class="form-group">

                <!-- Back Button -->
                <div class="fleft">
                    <a ng-click="back('/reports(/[0-9]+)?' , '/reports')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                        <i class="fa fa-arrow-circle-o-left"></i>
                        Back to List
                    </a>
                </div>

            <!-- /End .form-group -->
            </div>

        <!-- /End .panel-body -->
        </div>

    </main>

