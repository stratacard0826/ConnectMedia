
            <!-- components.view-logout -->
            <main id="logout-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }} - @{{ payroll.starttime | date:'fullDate' }} to @{{ payroll.endtime | date:'fullDate' }}

                </header>
                <article class="panel-body">

                    <div class="logout-panel">

                        <div ng-repeat="( index , user ) in users" ng-show="payroll.hours[ user.id ]">
                        
                            <table class="table table-bordered table-striped list">
                                <thead>
                                    <tr>
                                        <th><strong>@{{ user.firstname + ' ' + user.lastname }}</strong></th>
                                        <th><strong>Rate</strong></th>
                                        <th><strong>Hours</strong></th>
                                        <th><strong>Overtime</strong></th>
                                        <th><strong>Total</strong></th>
                                    </tr>
                                </thead>
                                <tbody ng-init="total = { hours:0, overtime:0, value:0 }">
                                    <tr ng-repeat="( index , data ) in payroll.hours[ user.id ]" ng-init="calculate( data , total );">
                                        <td>                                                
                                            @{{ data.time | date:'fullDate' }}
                                        </td>
                                        <td>
                                            $@{{ data.rate.toFixed(2) }}
                                        </td>
                                        <td>
                                            @{{ data.hours.toFixed(2) }}
                                        </td>
                                        <td>
                                            @{{ data.overtime.toFixed(2) }}
                                        </td>
                                        <td>
                                            $@{{ data.value.toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2">
                                            <strong>Total:</strong>
                                        </td>
                                        <td>
                                            <strong>@{{ total.hours.toFixed(2) }}</strong>
                                        </td>
                                        <td>
                                           <strong>@{{ total.overtime.toFixed(2) }}</strong>
                                        </td>
                                        <td>
                                            <strong>$@{{ total.value.toFixed(2) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>

                            <hr />
                        
                        </div>

                    <footer ng-if="hasPermission('payrolls')">

                        <a ng-click="back('/payrolls(/[0-9]+)?' , '/payrolls')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

    
                        <!-- Submit Button -->
                        <a class="btn fright btn-primary" ng-click="download()">
                            <i class="fa fa-download"></i>
                            Download
                        </a>

                    </footer>
                </article>
            </main>
