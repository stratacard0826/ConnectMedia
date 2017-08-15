

        <!-- controllers.user success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.user delete -->
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

        <!-- controllers.user -->
        <section id="user" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-user"></i>
                User Management

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/admin/users/add')" ng-if="hasPermission('users.create')">
                        Add User
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">
                <table class="table table-bordered table-striped list">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Name</th>
                            <th class="text-left font2 color000">Username</th>
                            <th class="text-left font2 color000">Email</th>
                            <th class="text-left font2 color000">City</th>
                            <th class="text-left font2 color000">Province</th>
                            <th class="text-left font2 color000">Phone</th>
                            <th class="text-center font2 color000 actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="user in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ user.id }}</th>
                            <td>@{{ user.firstname }} @{{ user.lastname }}</td>
                            <td>@{{ user.username }}</td>
                            <td>@{{ user.email }}</td>
                            <td>@{{ user.city }}</td>
                            <td>@{{ user.province }}</td>
                            <td>@{{ user.phone }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-primary" ng-click="open('/admin/users/edit/' + user.id )" ng-show="hasPermission('users.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( user )" ng-if="user.id != 1 && hasPermission('users.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td class="text-center" colspan="100%">You haven't added any Users!</td>
                        </tr>

                    </tbody>
                </table>
                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

