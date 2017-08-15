

        <!-- controllers.promos success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.promos delete -->
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

        <!-- controllers.promos -->
        <section id="promo" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-bar-chart"></i>
                Marketing &amp; Promos

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/promos/add')" ng-if="hasPermission('promos.create')">
                        Add Promotion
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">

                <!-- Search Form -->
                <form class="search clearfix" name="searchForm" ng-submit="search( searchForm )" novalidate>
                    <section class="col-md-8 column">
                        <div>
                            <label for="search" class="color000 font2 hide">Search:</label>
                            <input type="text" name="query" ng-model="list.query" placeholder="Search" ng-keyup="search( searchForm )" />
                            <i class="fa fa-sitemap color888 fleft"></i>
                            <button name="submit" class="fa fa-search"></button>
                        </div>
                    </section>
                    <section class="col-md-2 column pad">
                        <div>
                            <div class="select" select-wrapper>
                                <select ng-model="list.sort" ng-change="search( searchForm )">
                                    <option value="">Sort by</option>
                                    <option value="name">Name</option>
                                    <option value="date">Date</option>
                                    <option value="created_at">Created Date</option>
                                    <option value="updated_at">Updated Date</option>
                                </select>
                            </div>
                            <i class="fa fa-sort-alpha-desc color888 fleft"></i>
                        </div>
                    </section>
                    <section class="col-md-2 column">
                        <div>
                            <div class="select" select-wrapper>
                                <select ng-model="list.document" ng-change="search( searchForm )">
                                    <option value="" selected>Show All</option>
                                    <option value="Web">Web</option>
                                    <option value="Email">Email</option>
                                    <option value="Print">Print</option>
                                    <option value="Social Media">Social Media</option>
                                    <option value="Document">Document</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <i class="fa fa-sort-amount-desc color888 fleft"></i>
                        </div>
                    </section>
                </form>

                <!-- List -->
                <ul ng-show="page.data.length > 0 && !list.loading" class="list">
                    <li ng-show="!list.loading" dir-paginate="promotion in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">

                        <!-- Main Image -->
                        <div class="image">
                            <a ng-click="open('/promos/view/' + promotion.id )">
                                <img ng-src="@{{ promotion.image ? promotion.image : '/public/assets/images/default-document.jpg' }}" />
                            </a>
                        </div>

                        <!-- Title -->
                        <h2>
                            <a ng-click="open('/promos/view/' + promotion.id )" class="colorred hovercolorred font2">
                                @{{ promotion.name }}
                            </a>
                        </h2>

                        <!-- Created Date & View Button -->
                        <div class="details color000 text-center clearfix">
                            <a class="fleft action color000 hovercolor000" ng-if="hasPermission('promos.view')" ng-click="open('/promos/view/' + promotion.id )">
                                <i class="fa fa-eye"></i>
                                View
                            </a>
                            <a class="edit action color000 hovercolor000" ng-if="hasPermission('promos.edit')" ng-click="open('/promos/edit/' + promotion.id )">
                                <i class="fa fa-pencil"></i>
                                Edit
                            </a>
                            <a class="fright action color000 hovercolor000" ng-if="hasPermission('promos.delete')" ng-click="delete( promotion )">
                                <i class="fa fa-remove"></i>
                                Delete
                            </a>
                        </div>

                        <!-- Document Types Included -->
                        <p class="documents color000">
                            <i class="fa fa-file"></i>
                            @{{ promotion.documents.length > 0 ? promotion.documents.join(', ') : 'None Yet' }}
                        </p>

                    </li>
                </ul>

                <!-- No Results -->
                <div ng-show="page.data.length == 0 && !list.loading" class="text-center font2 color000">
                    <p ng-if="hasPermission('promos.create')">You haven't added any Marketing Promotion!</p>
                    <p ng-if="!hasPermission('promos.create')">No Marketing Promotion Yet!</p>
                </div>

                <!-- Loading -->
                <div ng-show="list.loading">
                    <div class="text-center font2 color000">
                        <i class="fa fa-circle-o-notch fa-spin color000" style="font-size:20px;margin-right:5px;"></i>
                        <span style="font-size:15px;">Loading</span>
                    </div>
                </div>



                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

