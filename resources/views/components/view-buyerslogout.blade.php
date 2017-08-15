
            <!-- components.view-logout -->
            <main id="logout-view" class="border panel panel-default" ng-if="!loading">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <article class="panel-body">

                    <div class="logout-panel">

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
                                            <strong><i class="fa fa-clock-o coloraaa"></i> Start Date:</strong>
                                        </td>
                                        <td>
                                            @{{ logout.start }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-clock-o coloraaa"></i> End Date:</strong>
                                        </td>
                                        <td>
                                            @{{ logout.end }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-map-marker coloraaa"></i> Location:</strong>
                                        </td>
                                        <td>
                                            @{{ logout.location }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-comments coloraaa"></i> Recap:</strong>
                                        </td>
                                        <td>
                                            @{{ logout.recap }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-usd coloraaa"></i> Last Year Sales (MTD):</strong>
                                        </td>
                                        <td>
                                            @{{ logout.lymtd | currency }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-usd coloraaa"></i> This Year Sales (MTD):</strong>
                                        </td>
                                        <td>
                                            @{{ logout.mtd | currency }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="scalable">                                                
                                            <strong><i class="fa fa-usd coloraaa"></i> Footwear / Apparel Sales:</strong>
                                        </td>
                                        <td>
                                            @{{ logout.sales | currency }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                    <footer ng-if="hasPermission('buyerslogouts')">

                        <a ng-click="back('/buyers(/[0-9]+)?' , '/logouts')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                            <i class="fa fa-arrow-circle-o-left"></i>
                            Back to List
                        </a>

                    </footer>
                </article>
            </main>
