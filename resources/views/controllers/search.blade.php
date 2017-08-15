
        <!-- controllers.search -->
        <main id="search-list" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-search"></i>
                Search

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                </div>
            </header>
            <div class="body clearfix panel-body">

                 <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000" width="50">#</th>
                            <th class="text-left font2 color000" width="150">Type</th>
                            <th class="text-left font2 color000">Search</th>
                            <th class="text-center actions font2 color000" width="50">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="search in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ search.id }}</th>
                            <td>@{{ search.type }}</td>
                            <td>@{{ search.title }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open( search.url )">View</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">No Search Results Returned!</td>
                        </tr>

                    </tbody>
                </table>



                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </main>

