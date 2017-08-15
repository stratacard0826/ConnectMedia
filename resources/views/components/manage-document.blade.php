
        <!-- components.manage-document -->
        <form name="documentForm" method="post" ng-submit="submit( documentForm )" novalidate>


            <!-- components.manage-document success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && documentForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-document -->
            <main id="document-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                    <div class="form-group fright">
                        
                        <!-- Form Error Notices -->
                        <small class="colorred text-right fleft submitnotice" ng-show="Upload.isUploadInProgress()">
                            <span ng-show="Upload.isUploadInProgress()">You must wait for the uploads before proceeding</span>
                        </small>
                            
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} documents - Please wait.
                        </small>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn-sm fright btn-primary" ng-disabled="Upload.isUploadInProgress() || sending">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .form-group -->
                    </div>

                </header>
                <div class="panel-body">

                    <div class="document-panel">

                        <!-- File Drop Zone -->
                        <div class="form-group">
                            <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ng-model="document.files" class="drop-area text-center" ngf-multiple="true">
                                
                                <i class="fa fa-paperclip color888"></i>
                                Drop Attachment / Click Here

                            </div>
                        </div>

                        <!-- File List -->
                        <ul class="files clearfix">
                            <li ng-repeat="( index , file ) in document.files" class="fleft clearfix">

                                <progress max="100" value="@{{ file.progress }}" ng-class="file.status ? file.status : null"></progress>
                            
                                <div class="clearfix">                                             
                                    <small class="fleft font2">
                                        <input type="text" name="name" ng-model="file.custom_name" value="@{{ file.name }}" class="fleft" ng-init="file.custom_name = file.name" />
                                    </small>
                                    <i class="fa fa-times fright" ng-click="remove( file , index )"></i>
                                </div>

                            </li>
                        </ul>

                    <!-- /End .document-panel -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

