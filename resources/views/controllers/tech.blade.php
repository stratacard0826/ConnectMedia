

        <!-- controllers.tech success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.tech delete -->
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

        <!-- controllers.tech -->
        <section id="tech" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-cogs"></i>
                Tech Talk Products

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd fleft"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/tech/add')" ng-show="hasPermission('tech.create')">
                        Add Product
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                <div class="grid" id="masonry">

                    <!-- -->
                    <div class="grid-item" dir-paginate="tech in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                        <div class="box">

                            <!-- Images -->
                            <img ng-src="@{{ tech.attachment }}" class="card-img-top" />

                            <div class="block">
                                
                                <h4 class="font2 color000">@{{ tech.name }}</h4>

                                <!-- -->
                                <div class="notes">
                                    @{{ tech.notes }}
                                </div>

                                <!-- Specifications -->
                                <table class="table list">
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr ng-repeat="spec in tech.specifications">
                                            <td><strong>@{{ spec.key }}:</strong></td>
                                            <td>@{{ spec.value }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="clearfix">

                                    <a class="fleftfont2 color000 hovercolor000" ng-if="hasPermission('tech.edit')" ng-click="open('/tech/edit/' + tech.id )">
                                        <i class="fa fa-pencil"></i>
                                        Edit
                                    </a>


                                    <a class="fright font2 color000 hovercolor000" ng-if="hasPermission('promos.delete')" ng-click="delete( tech )">
                                        <i class="fa fa-remove"></i>
                                        Delete
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- No Results -->
                    <div ng-show="page.data.length == 0">
                        <p class="text-center none">You haven't added any Tech Talk Products!</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
                    <div class="clearfix"></div>
            </div>
        </section>

