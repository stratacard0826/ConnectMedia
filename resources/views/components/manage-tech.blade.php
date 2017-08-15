
        <!-- components.manage-doctor form -->
        <form name="techForm" method="post" class="panel-body" ng-submit="submit( techForm )" novalidate>

            <!-- components.manage-doctor success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && techForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-doctor error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( techForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>
                        
                        <!-- Preset Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Customer Error -->
                        <li ng-show="techForm.error[ 'name' ] = techForm.name.$invalid && techForm.name.$dirty" ng-messages="techForm.name.$error">
                            <span ng-message="required">Name is required</span>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- components.manage-doctor success -->
            <main id="user-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                </header>
                <div class="panel-body">
                
                    <p>Who should receive the email?: <small>( <em>Leave Blank for Everyone</em> )</small></p>

                    <!-- Stores -->
                    <div class="form-group" ng-if="stores" ng-class="{ 'has-success' : !techForm.stores.$invalid && techForm.stores.$dirty }">
                        <label class="hide" for="stores">Stores:</label>
                        <multiselect id="stores" name="stores" ng-model="tech.stores" ng-value="tech.stores" options="stores.data" selected-model="tech.stores" extra-settings="stores.settings" events="stores.events" translation-texts="{ buttonDefaultText: 'Users in Stores' }" ng-dropdown-multiselect="stores"></multiselect>
                        <i class="fa fa-map-marker color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm.stores.$invalid && techForm.stores.$dirty"></span>
                    </div>

                    <!-- Roles -->
                    <div class="form-group" ng-if="roles" ng-class="{ 'has-success' : !techForm.roles.$invalid && techForm.roles.$dirty }">
                        <label class="hide" for="roles">Roles:</label>
                        <multiselect id="roles" name="roles" ng-model="tech.roles" ng-value="tech.roles" options="roles.data" selected-model="tech.roles" extra-settings="roles.settings" events="roles.events" translation-texts="{ buttonDefaultText: 'Users with Roles' }" ng-dropdown-multiselect="roles"></multiselect>
                        <i class="fa fa-lock color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm.roles.$invalid && techForm.roles.$dirty"></span>
                    </div>

                    <p>Add your Tech Talk Product Image: <small>(Note: Images Only)</small></p>

                    <!-- File Drop Zone -->
                    <div class="form-group">
                        <div ngf-pattern="image/*" ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ng-model="tech.attachment" class="drop-area text-center">
                            
                            <i class="fa fa-paperclip color888"></i>
                            Drop Attachment / Click Here

                        </div>
                    </div>

                    <!-- File List -->
                    <ul class="files clearfix" ng-show="tech.attachment">
                        <li>
                            <progress max="100" value="@{{ tech.attachment.progress }}" ng-class="tech.attachment.status ? tech.attachment.status : null"></progress>
                        
                            <div class="clearfix">                                             
                                <small class="fleft font2">
                                    @{{ tech.attachment.name || tech.attachment.filename }}
                                </small>
                                <i class="fa fa-times fright" ng-click="remove()"></i>
                            </div>
                        </li>
                    </ul>

                    <p>Fill out the product specifications</p>

                    <!-- Name -->
                    <div class="form-group" ng-class="{ 'has-error' : techForm.name.$invalid && techForm.name.$dirty , 'has-success' : !techForm.name.$invalid && techForm.name.$dirty }">
                        <label class="hide" for="tech-specifications-@{{ index }}">Name</label>
                        <input type="text" id="tech-specifications-@{{ index }}" name="name" ng-model="tech.name" placeholder="Name" required />
                        <i class="fa fa-shopping-bag color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="techForm.name.$invalid && techForm.name.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm.name.$invalid && techForm.name.$dirty"></span>
                    </div>

                    <!-- Products -->
                    <table class="table table-bordered table-striped list">
                        <thead>
                            <tr>
                                <th colspan="3" valign="middle">
                                    <i class="fa fa-shopping-bag"></i>
                                    Products
                                    <button type="button" ng-click="tech.specifications.push({});" class="btn-sm btn-primary fright">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Specification
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, data) in tech.specifications">
                                <td>

                                    <!-- Name -->
                                    <div class="form-group" ng-class="{ 'has-error' : techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty , 'has-success' : !techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty }">
                                        <label class="hide" for="tech-specifications-@{{ index }}">Name</label>
                                        <input type="text" id="tech-specifications-@{{ index }}" name="specifications[@{{ index }}][key]" ng-model="tech.specifications[ index ].key" placeholder="Name" required />
                                        <i class="fa fa-shopping-bag color888 fleft"></i>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty"></span>
                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty"></span>
                                    </div>

                                </td>
                                <td>

                                    <!-- Value -->
                                    <div class="form-group" ng-class="{ 'has-error' : techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty , 'has-success' : !techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty }">
                                        <label class="hide" for="tech-specifications-@{{ index }}">Value</label>
                                        <input type="text" id="tech-specifications-@{{ index }}" name="specifications[@{{ index }}][value]" ng-model="tech.specifications[ index ].value" placeholder="Value" required />
                                        <i class="fa fa-shopping-bag color888 fleft"></i>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty"></span>
                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm['specifications[' + index + ']'].$invalid && techForm['specifications[' + index + ']'].$dirty"></span>
                                    </div>

                                </td>
                                <td width="50" align="center">

                                    <!-- Delete Product -->
                                    <button type="button" ng-click="tech.specifications.splice(index,1);" class="btn-sm btn-danger font2">Delete</button>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Notes -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : !techForm.notes.$invalid && techForm.notes.$dirty }">
                        <label class="hide" for="notes">Notes</label>
                        <textarea id="notes" name="notes" placeholder="Notes" ng-model="tech.notes"></textarea>
                        <i class="fa fa-info-circle color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!techForm.notes.$invalid && techForm.notes.$dirty"></span>
                    </div>


                    <div class="form-group">
                    
                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/tech(/[0-9]+)?' , '/tech')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>

                        <div class="fright">
                        
                            <!-- Form Error Notices -->
                            <small class="colorred submitnotice" ng-show="hasError( techForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} product - Please wait.
                            </small>

                            <div>
                            
                                <!-- Send Email Button -->
                                <p class="fleft" style="padding-right:10px;">
                                    Send Email:
                                    <input type="checkbox" name="sendemail" ng-model="tech.sendemail" ng-checked="tech.sendemail" ng-true-value="1" />
                                </p>

                                <!-- Submit Button -->
                                <button name="submit" class="btn fright" ng-class="hasError( techForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="techForm.$invalid || sending">
                                    <i class="fa fa-@{{ icon }}"></i>
                                    @{{ button }}
                                </button>

                            </div>

                        <!-- /End .fright -->
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>
                
            </main>

        </form>

