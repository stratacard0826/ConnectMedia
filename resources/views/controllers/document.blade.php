

        <!-- controllers.documents success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    

        <!-- controllers.documents -->
        <section id="document" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-folder"></i>
                Documents

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/documents/manage')" ng-if="hasPermission('documents.manage')">
                        Manage Documents
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
                                    <option value="image">Images</option>
                                    <option value="video">Videos</option>
                                    <option value="text,word">Text Documents</option>
                                    <option value="powerpoint">Powerpoints</option>
                                    <option value="excel,csv">Spreadsheets</option>
                                    <option value="compress">Compressed Files</option>
                                </select>
                            </div>
                            <i class="fa fa-sort-amount-desc color888 fleft"></i>
                        </div>
                    </section>
                </form>

                <!-- List -->
                <ul ng-show="page.data.length > 0 && !list.loading" class="list">
                    <li ng-show="!list.loading" dir-paginate="file in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">

                        <!-- Main Image -->
                        <div class="image">
                            <a ng-click="view( file )" ng-show="file.mime.type == 'video' || file.image">
                                <img ng-src="@{{ file.image ? file.image : '/public/assets/images/default-document.jpg' }}" />
                            </a>
                            <img ng-src="@{{ file.image ? file.image : '/public/assets/images/default-document.jpg' }}" ng-show="file.mime.type != 'video' && !file.image" />
                        </div>

                        <!-- Title -->
                        <h2 class="colorred hovercolorred font2">
                            @{{ file.name }}
                        </h2>

                        <!-- View File & Download -->
                        <div class="details color000 clearfix">
                            <time datetime="@{{ file.created_at }}" class="fleft color000 hovercolor000">
                                <i class="fa fa-clock-o"></i>
                                @{{ file.created_at_time * 1000 | date:'mediumDate' }}
                            </time>
                            <a class="fright view color000 hovercolor000" ng-click="view( file )" ng-show="file.mime.type == 'video' || file.image">
                                <i class="fa fa-eye"></i>
                                View File
                            </a>
                        </div>

                        <!-- View File & Download -->
                        <div class="actions color000 clearfix">
                            <div class="fleft file color000 hovercolor000" ng-click="download( file )">
                                <i class="fa fa-file"></i>
                                @{{ file.filetype }}
                            </div>
                            <a class="fright view color000 hovercolor000" ng-click="download( file )">
                                <i class="fa fa-download"></i>
                                Download
                            </a>
                        </div>

                    </li>
                </ul>

                <!-- No Results -->
                <div ng-show="page.data.length == 0 && !list.loading" class="text-center font2 color000">
                    <p ng-if="hasPermission('documents.create')">You haven't added any Documents!</p>
                    <p ng-if="!hasPermission('documents.create')">No Documents Yet!</p>
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

