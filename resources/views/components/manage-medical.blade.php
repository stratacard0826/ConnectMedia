
        <!-- components.manage-doctor form -->
        <form name="medicalForm" method="post" class="panel-body" ng-submit="submit( medicalForm )" novalidate>

            <!-- components.manage-doctor success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && medicalForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>

            <!-- components.manage-doctor error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( medicalForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>
                        
                        <!-- Preset Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>

                        <!-- Store Error -->
                        <li ng-show="medicalForm.error[ 'store' ] = medicalForm.store_id.$invalid && medicalForm.store_id.$dirty" ng-messages="medicalForm.store_id.$error">
                            <span ng-message="required">Store is required</span>
                        </li>

                        <!-- User Error -->
                        <li ng-show="medicalForm.error[ 'user' ] = medicalForm.user_id.$invalid && medicalForm.user_id.$dirty" ng-messages="medicalForm.user_id.$error">
                            <span ng-message="required">Doctor is required</span>
                        </li>

                        <!-- Customer Error -->
                        <li ng-show="medicalForm.error[ 'customer' ] = medicalForm.customer.$invalid && medicalForm.customer.$dirty" ng-messages="medicalForm.customer.$error">
                            <span ng-message="required">Doctor is required</span>
                        </li>

                        <!-- Products Error -->
                        <li ng-show="medicalForm.error[ 'products' ] = medicalForm.products.$invalid && medicalForm.products.$dirty" ng-messages="medicalForm.products.$error">
                            <span ng-message="required">Products are required</span>
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

                    <p>Fill out the fields below to add your User</p>

                    <!-- Store -->
                    <div class="form-group clearfix">
                        <label class="hide" for="store">Store:</label>
                        <div class="select">
                            <select type="text" id="store" name="store_id" ng-model="medical.store_id" ng-options="store.id as store.name for store in stores" required>
                                <option value="">Select a Store</option>
                            </select>
                        </div>
                        <i class="fa fa-building color888 fleft"></i>
                    </div>

                    <!-- Doctor -->
                    <div class="form-group clearfix">
                        <label class="hide" for="doctor">Doctor:</label>
                        <div class="select">
                            <select type="text" id="doctor" name="doctor_id" ng-model="medical.doctor_id" ng-options="doctor.id as doctor.firstname + ' ' + doctor.lastname for doctor in doctors" required>
                                <option value="">Select a Doctor</option>
                            </select>
                        </div>
                        <i class="fa fa-user-md color888 fleft"></i>
                    </div>

                    <!-- Customer -->
                    <div class="form-group clearfix" ng-class="{ 'has-error' : medicalForm.customer.$invalid && medicalForm.customer.$dirty , 'has-success' : !medicalForm.customer.$invalid && medicalForm.customer.$dirty }">
                        <label class="hide" for="customer">Customer:</label>
                        <input type="text" id="customer" name="customer" placeholder="Customer" ng-model="medical.customer" required is-empty></textarea>
                        <i class="fa fa-male color888 fleft"></i>
                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="medicalForm.customer.$invalid && medicalForm.customer.$dirty"></span>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!medicalForm.customer.$invalid && medicalForm.customer.$dirty"></span>
                    </div>

                    <!-- Products -->
                    <table class="table table-bordered table-striped list">
                        <thead>
                            <tr>
                                <th colspan="3" valign="middle">
                                    <i class="fa fa-shopping-bag"></i>
                                    Products
                                    <button type="button" ng-click="medical.products.push({});" class="btn-sm btn-primary fright">
                                        <i class="fa fa-plus-circle"></i>
                                        Add Product
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="(index, data) in medical.products">
                                <td>

                                    <!-- Role -->
                                    <div class="form-group" ng-class="{ 'has-error' : medicalForm['products[' + index + ']'].$invalid && medicalForm['products[' + index + ']'].$dirty , 'has-success' : !medicalForm['products[' + index + ']'].$invalid && medicalForm['products[' + index + ']'].$dirty }">
                                        <label class="hide" for="medical-products-@{{ index }}">User Role</label>
                                        <input type="text" id="medical-products-@{{ index }}" name="products[@{{ index }}][product]" ng-model="medical.products[ index ].product" placeholder="Product" required />
                                        <i class="fa fa-shopping-bag color888 fleft"></i>
                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="medicalForm['products[' + index + ']'].$invalid && medicalForm['products[' + index + ']'].$dirty"></span>
                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!medicalForm['products[' + index + ']'].$invalid && medicalForm['products[' + index + ']'].$dirty"></span>
                                    </div>

                                </td>
                                <td width="50" align="center">

                                    <!-- Delete Product -->
                                    <button type="button" ng-click="medical.products.splice(index,1);" class="btn-sm btn-danger font2">Delete</button>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Notes -->
                    <div class="form-group clearfix" ng-class="{ 'has-success' : !medicalForm.notes.$invalid && medicalForm.notes.$dirty }">
                        <label class="hide" for="notes">notes</label>
                        <textarea id="notes" name="notes" placeholder="notes" ng-model="medical.notes"></textarea>
                        <i class="fa fa-info-circle color888 fleft"></i>
                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!medicalForm.notes.$invalid && medicalForm.notes.$dirty"></span>
                    </div>


                    <div class="form-group">
                    
                        <!-- Back Button -->
                        <div class="fleft">
                            <a ng-click="back('/medical(/[0-9]+)?' , '/medical')" class="btn bgeee coloraaa hoverbg555 hovercolorfff">
                                <i class="fa fa-arrow-circle-o-left"></i>
                                Back to List
                            </a>
                        </div>

                        <div class="fright">
                        
                            <!-- Form Error Notices -->
                            <small class="colorred submitnotice" ng-show="hasError( medicalForm )">
                                You must fix any form errors before proceeding * See Errors at Top *
                            </small>
                            
                            <!-- Submitting Wait Notice -->
                            <small class="colorinfo submitnotice" ng-show="sending">
                                <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                                @{{ running }} doctor - Please wait.
                            </small>

                            <!-- Submit Button -->
                            <button name="submit" class="btn fright" ng-class="hasError( medicalForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="medicalForm.$invalid || sending">
                                <i class="fa fa-@{{ icon }}"></i>
                                @{{ button }}
                            </button>

                        <!-- /End .fright -->
                        </div>

                    <!-- /End .form-group -->
                    </div>

                <!-- /End .panel-body -->
                </div>
                
            </main>

        </form>

