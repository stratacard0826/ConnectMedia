

        <!-- controllers.event success -->
        <div class="bs-callout bs-callout-info" ng-show="success.length"> 
            <h4 class="font2">Success!</h4> 
            <p>@{{ success }}</p>
        </div>    


        <!-- controllers.event delete -->
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

        <!-- controllers.event -->
        <section id="event" class="border panel panel-default">
            <header class="font2 panel-heading clearfix">
                
                <i class="fa fa-calendar"></i>
                Events

                <div class="fright">

                    <pagination-limit class="fleft"></pagination-limit>

                    <span class="vdivider bgddd"></span>

                    <a class="fright colorfff font2 btn-sm btn-primary" ng-click="open('/admin/events/add')" ng-if="hasPermission('events.create')">
                        Add Event
                        <i class="fa fa-arrow-circle-o-right"></i>
                    </a>

                </div>
            </header>
            <div class="body clearfix panel-body">

                 <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center font2 color000">#</th>
                            <th class="text-left font2 color000">Event Name</th>
                            <th class="text-left font2 color000">Starts At</th>
                            <th class="text-left font2 color000">Ends At</th>
                            <th class="text-center actions font2 color000">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <!-- List -->
                        <tr dir-paginate="event in page.data | itemsPerPage:page.showing" total-items="page.total" current-page="page.current">
                            <td class="text-center">@{{ event.id }}</th>
                            <td>@{{ event.name }}</td>
                            <td>@{{ event.start | date:'MMM d, y h:mm a' }}</td>
                            <td>@{{ event.end | date:'MMM d, y h:mm a' }}</td>
                            <td class="text-center actions">
                                <a class="btn-sm btn-default" ng-click="open('/admin/events/view/' + event.id )" ng-show="hasPermission('events.view')">View</a>
                                <a class="btn-sm btn-primary" ng-click="open('/admin/events/edit/' + event.id )" ng-show="hasPermission('events.edit')">Edit</a>
                                <a class="btn-sm btn-danger" ng-click="delete( event )" ng-show="hasPermission('events.delete')">Delete</a>
                            </td>
                        </tr>

                        <!-- No Results -->
                        <tr ng-show="page.data.length == 0">
                            <td ng-if="hasPermission('events.create')" class="text-center" colspan="100%">You haven't added any Events!</td>
                            <td ng-if="!hasPermission('events.create')" class="text-center" colspan="100%">No Events Yet!</td>
                        </tr>

                    </tbody>
                </table>



                <dir-pagination-controls on-page-change="load(newPageNumber)"></dir-pagination-controls>
            </div>
        </section>

