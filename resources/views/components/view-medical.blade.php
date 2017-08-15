
            <!-- components.view-medical -->
            <main id="medical-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <article class="panel-body">

                    <div class="medical-panel">

                        <!-- Tabs -->
                        <div class="tab-content">

                            <table class="table table-bordered table-striped list">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-clock-o coloraaa"></i> Date:</strong>
                                        </td>
                                        <td>
                                            @{{ medical.created_at }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-building coloraaa"></i> Store:</strong>
                                        </td>
                                        <td>
                                            @{{ medical.store.name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-user-md coloraaa"></i> Doctor:</strong>
                                        </td>
                                        <td>
                                            @{{ medical.doctor.firstname }} @{{ medical.doctor.lastname }}
                                        </td>
                                    </tr>
                                    <tr ng-repeat="item in medical.products">
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-shopping-bag coloraaa"></i> Product:</strong>
                                        </td>
                                        <td>
                                            @{{ item.product }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-info-circle coloraaa"></i> Notes:</strong>
                                        </td>
                                        <td>
                                            @{{ medical.notes }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                    <footer ng-if="hasPermission('medical')">

                        <a ng-click="back('/medical(/[0-9]+)?' , '/medical')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

                    </footer>
                </article>
            </main>
