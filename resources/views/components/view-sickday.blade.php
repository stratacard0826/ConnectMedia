
            <!-- components.view-sickday -->
            <main id="sickday-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <article class="panel-body">

                    <div class="sickday-panel">

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
                                            @{{ sickday.date }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-building coloraaa"></i> store:</strong>
                                        </td>
                                        <td>
                                            @{{ sickday.store.name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-user coloraaa"></i> User:</strong>
                                        </td>
                                        <td>
                                            @{{ sickday.user.firstname }} @{{ sickday.user.lastname }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-info-circle coloraaa"></i> Details:</strong>
                                        </td>
                                        <td>
                                            @{{ sickday.details }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                    <footer ng-if="hasPermission('sickdays')">

                        <a ng-click="back('/admin/sickday(/[0-9]+)?' , '/admin/sickday')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

                    </footer>
                </article>
            </main>
