
        <!-- components.manage-bar -->
        <form name="barForm" method="post" ng-submit="submit( barForm )" novalidate>


            <!-- components.manage-bar success -->
            <div class="bs-callout bs-callout-info" ng-show="success.length && barForm.$pristine"> 
                <h4 class="font2">Success!</h4> 
                <p>@{{ success }}</p>
            </div>


            <!-- components.manage-bar error -->
            <div class="bs-callout bs-callout-danger" ng-show="hasError( barForm ) || errors.length"> 
                <h4 class="font2">Form Errors</h4> 
                <div>
                    <ul>

                        <!-- General Errors -->
                        <li ng-repeat="error in errors">
                            @{{ error }}
                        </li>
                    
                        <!-- Name Error -->
                        <li ng-show="barForm.error.name = ( barForm.name.$dirty && barForm.name.$invalid )" ng-messages="barForm.name.$error">
                            <span ng-message="required">Name is required</span>
                        </li>
                    
                        <!-- Description Error -->
                        <li ng-show="barForm.error.description = ( barForm.description.$dirty && barForm.description.$invalid )" ng-messages="barForm.description.$error">
                            <span ng-message="required">Description is required</span>
                        </li>
                    
                        <!-- Status Error -->
                        <li ng-show="barForm.error.status = ( barForm.status.$dirty && barForm.status.$invalid )" ng-messages="barForm.status.$error">
                            <span ng-message="required">Status is required</span>
                        </li>

                        <!-- Serveware Error -->
                        <li ng-repeat="(index , side) in bar.serveware" ng-show="barForm.error[ 'serveware' + index + 'name'] || barForm.error[ 'serveware' + index + 'volume' ] || barForm.error[ 'serveware' + index + 'unit' ] ">
                            Serveware @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Serveware Name -->
                                <li ng-show="barForm.error[ 'serveware' + index + 'name' ] = hasArrayError( 'serveware' , index , 'name' )" ng-messages="barForm[ 'serveware[' + index + '][name]' ].$error">
                                    <span ng-message="required">Name is required</span>
                                </li>

                            </ul>
                        </li>

                        <!-- Sides Error -->
                        <li ng-repeat="(index , side) in bar.sides" ng-show="barForm.error[ 'sides' + index + 'name'] || barForm.error[ 'sides' + index + 'volume' ] || barForm.error[ 'sides' + index + 'unit' ] ">
                            Sides @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Sides Name -->
                                <li ng-show="barForm.error[ 'sides' + index + 'name' ] = hasArrayError( 'sides' , index , 'name' )" ng-messages="barForm[ 'sides[' + index + '][name]' ].$error">
                                    <span ng-message="required">Name is required</span>
                                </li>

                                <!-- Sides Volume -->
                                <li ng-show="barForm.error[ 'sides' + index + 'volume' ] = hasArrayError( 'sides' , index , 'volume' )" ng-messages="barForm[ 'sides[' + index + '][volume]' ].$error">
                                    <span ng-message="required">Volume is required</span>
                                </li>

                                <!-- Sides Unit -->
                                <li ng-show="barForm.error[ 'sides' + index + 'unit' ] = hasArrayError( 'sides' , index , 'unit' )" ng-messages="barForm[ 'sides[' + index + '][unit]' ].$error">
                                    <span ng-message="required">Unit is required</span>
                                </li>

                            </ul>
                        </li>

                        <!-- Red Flag Error -->
                        <li ng-repeat="(index , redflag) in bar.redflag" ng-show="barForm.error[ 'redflag' + index + 'name' ]">
                            Red Flag @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Sides Name -->
                                <li ng-show="barForm.error[ 'redflag' + index + 'name' ] = hasArrayError( 'redflag' , index , 'name' )" ng-messages="barForm[ 'redflag[' + index + '][name]' ].$error">
                                    <span ng-message="required">Name is required</span>
                                </li>

                            </ul>
                        </li>

                        <!-- Ingredients Error -->
                        <li ng-repeat="(index , ingredient) in bar.ingredients" ng-show="barForm.error[ 'ingredients' + index + 'name'] || barForm.error[ 'ingredients' + index + 'volume' ] || barForm.error[ 'ingredients' + index + 'unit' ] ">
                            Ingredients @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Ingredients Name -->
                                <li ng-show="barForm.error[ 'ingredients' + index + 'name' ] = hasArrayError( 'ingredients' , index , 'name' )" ng-messages="barForm[ 'ingredients[' + index + '][name]' ].$error">
                                    <span ng-message="required">Name is required</span>
                                </li>

                                <!-- Ingredients Volume -->
                                <li ng-show="barForm.error[ 'ingredients' + index + 'volume' ] = hasArrayError( 'ingredients' , index , 'volume' )" ng-messages="barForm[ 'ingredients[' + index + '][volume]' ].$error">
                                    <span ng-message="required">Volume is required</span>
                                </li>

                                <!-- Ingredients Unit -->
                                <li ng-show="barForm.error[ 'ingredients' + index + 'unit' ] = hasArrayError( 'ingredients' , index , 'unit' )" ng-messages="barForm[ 'ingredients[' + index + '][unit]' ].$error">
                                    <span ng-message="required">Unit is required</span>
                                </li>

                            </ul>
                        </li>

                        <!-- Directions Error -->
                        <li ng-repeat="(index , direction) in bar.directions" ng-show="barForm.error[ 'directions' + index ]">
                            <div ng-show="barForm.error[ 'directions' + index ] = ( barForm[ 'directions[' + index + ']' ].$dirty && barForm[ 'directions[' + index + ']' ].$invalid )" ng-messages="barForm[ 'directions[' + index + ']' ].$error">
                                <span ng-message="required">Direction @{{ index + 1 }} is required</span>
                            </div>
                        </li>

                        <!-- FAQ Error -->
                        <li ng-repeat="(index , faq) in bar.faq" ng-show=" barForm.error[ 'faq' + index + 'category'] || barForm.error[ 'faq' + index + 'question' ] || barForm.error[ 'faq' + index + 'answer' ]">
                            FAQ @{{ index + 1 }} Errors:
                            <ul>
                                
                                <!-- Sides Name -->
                                <li ng-show="barForm.error[ 'faq' + index + 'category' ] = hasArrayError( 'faq' , index , 'category' )" ng-messages="barForm[ 'faq[' + index + '][category]' ].$error">
                                    <span ng-message="required">Category is required</span>
                                </li>

                                <!-- Sides Volume -->
                                <li ng-show="barForm.error[ 'faq' + index + 'question' ] = hasArrayError( 'faq' , index , 'question' )" ng-messages="barForm[ 'faq[' + index + '][question]' ].$error">
                                    <span ng-message="required">Question is required</span>
                                </li>

                                <!-- Sides Unit -->
                                <li ng-show="barForm.error[ 'faq' + index + 'answer' ] = hasArrayError( 'faq' , index , 'answer' )" ng-messages="barForm[ 'faq[' + index + '][answer]' ].$error">
                                    <span ng-message="required">Answer is required</span>
                                </li>

                            </ul>
                        </li>

                    </ul>
                </div>
            </div>


            <!-- components.manage-bar -->
            <main id="bar-management" class="border panel panel-default">
                <header class="font2 panel-heading">
                    
                    <i class="fa fa-@{{ icon }}"></i>
                    @{{ title }}

                    <div class="form-group fright">
                        
                        <!-- Form Error Notices -->
                        <small class="colorred text-right fleft submitnotice" ng-show="hasError( barForm ) || Upload.isUploadInProgress()">
                            <span ng-show="hasError( barForm )">You must fix any form errors before proceeding * See Errors at Top *</span>
                            <span ng-show="Upload.isUploadInProgress()">You must wait for the uploads before proceeding</span>
                        </small>
                        
                        <!-- Submitting Wait Notice -->
                        <small class="colorinfo text-right fleft submitnotice" ng-show="sending">
                            <i class="fa fa-circle-o-notch fa-spin colorinfo"></i>
                            @{{ running }} bar item - Please wait.
                        </small>

                        <!-- Submit Button -->
                        <button type="submit" name="submit" class="btn-sm fright" ng-class="hasError( barForm ) || errors.length ? 'btn-danger' : 'btn-primary'" ng-disabled="barForm.$invalid || sending || Upload.isUploadInProgress()">
                            <i class="fa fa-@{{ icon }}"></i>
                            @{{ button }}
                        </button>

                    <!-- /End .form-group -->
                    </div>

                </header>
                <div class="panel-body">

                    <div class="bar-panel">

                        <!-- Navigation -->
                        <ul class="nav nav-tabs">
                            <li ng-class="!tab || tab == 1 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 1" class="font2">
                                    <i class="fa fa-info-circle"></i>
                                    Overview
                                </a>
                            </li>
                            <li ng-class="tab == 2 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 2" class="font2">
                                    <i class="fa fa-serveware"></i>
                                    Serveware
                                </a>
                            </li>
                            <li ng-class="tab == 3 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 3" class="font2">
                                    <i class="fa fa-condiments"></i>
                                    Sides
                                </a>
                            </li>
                            <li ng-class="tab == 4 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 4" class="font2">
                                    <i class="fa fa-shopping-basket"></i>
                                    Ingredients
                                </a>
                            </li>
                            <li ng-class="tab == 5 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 5" class="font2">
                                    <i class="fa fa-map-signs"></i>
                                    Directions
                                </a>
                            </li>
                            <li ng-class="tab == 6 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 6" class="font2">
                                    <i class="fa fa-play-circle-o"></i>
                                    Media
                                </a>
                            </li>
                            <li ng-class="tab == 7 ? 'active color000' : 'color777 hovercolor444'">
                                <a ng-click="tab = 7" class="font2">
                                    <i class="fa fa-question-circle"></i>
                                    FAQ
                                </a>
                            </li>
                        </ul>

                        <!-- Tabs -->
                        <div class="tab-content">

                            <!-- Overview Tab -->
                            <div class="tab-pane" ng-class="!tab || tab == 1 ? 'active' : ''" ng-show="!tab || tab == 1">

                                <p>Fill out the fields below to add your Menu Item</p>

                                <!-- Menu Name -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : barForm.name.$invalid && barForm.name.$dirty , 'has-success' : !barForm.name.$invalid && barForm.name.$dirty }">
                                    <label class="hide" for="name">Name:</label>
                                    <input type="text" id="name" name="name" placeholder="Name" ng-model="bar.name" required is-empty />
                                    <i class="fa fa-asterisk color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="barForm.name.$invalid && barForm.name.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!barForm.name.$invalid && barForm.name.$dirty"></span>
                                </div>

                                <!-- Menu Description -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : barForm.description.$invalid && barForm.description.$dirty , 'has-success' : !barForm.description.$invalid && barForm.description.$dirty }">
                                    <label class="hide" for="name">Description:</label>
                                    <textarea id="description" name="description" placeholder="Description" ng-model="bar.description" required is-empty></textarea>
                                    <i class="fa fa-info-circle color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="barForm.description.$invalid && barForm.description.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!barForm.description.$invalid && barForm.description.$dirty"></span>
                                </div>

                                <!-- Menu Type -->                                
                                <div class="form-group" ng-class="{ 'has-success' : !barForm.type.$invalid && barForm.type.$dirty }">
                                    <label class="hide" for="item-type">Type</label>
                                    <div class="select" select-wrapper>
                                        <select id="item-type" name="type" ng-model="bar.type" required>
                                            <option value="" disabled selected hidden>Type</option>
                                            <option value="Cider">Cider</option>
                                            <option value="Coffee Drinks">Coffee Drinks</option>
                                            <option value="Eggnog">Eggnog</option>
                                            <option value="Hot Chocolate">Hot Chocolate</option>
                                            <option value="Juice">Juice</option>
                                            <option value="Lemonade">Lemonade</option>
                                            <option value="Mocktails">Mocktails</option>
                                            <option value="Punch">Punch</option>
                                            <option value="Slushies">Slushies</option>
                                            <option value="Smoothies">Smoothies</option>
                                            <option value="Tea Drinks">Tea Drinks</option>
                                            <option value="Beer Cocktails">Beer Cocktails</option>
                                            <option value="Cocktails">Cocktails</option>
                                            <option value="Liqueurs">Liqueurs</option>
                                            <option value="Mulled Wine">Mulled Wine</option>
                                            <option value="Sangria">Sangria</option>
                                            <option value="Shots">Shots</option>
                                        </select>
                                    </div>
                                    <i class="fa fa-tag color888 fleft"></i>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!barForm.type.$invalid && barForm.type.$dirty"></span>
                                </div>

                                <!-- Menu Status -->                           
                                <div class="form-group" ng-class="{ 'has-success' : !barForm.status.$invalid && barForm.status.$dirty }">
                                    <label class="hide" for="item-type">Status</label>
                                    <div class="select" select-wrapper>
                                        <select id="item-type" name="status" ng-model="bar.status" required>
                                            <option value="" disabled selected hidden>Status</option>
                                            <option value="Active">Active</option>
                                            <option value="Phase In">Phase In</option>
                                            <option value="Phase Out">Phase Out</option>
                                            <option value="Removed">Removed</option>
                                        </select>
                                    </div>
                                    <i class="fa fa-power-off color888 fleft"></i>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!barForm.status.$invalid && barForm.status.$dirty"></span>
                                </div>

                                <!-- Menu Status Date -->
                                <div class="form-group clearfix" is-open="bar.calendar.open" ng-show="[ 'Phase In' , 'Phase Out' , 'Removed' ].indexOf(bar.status) > -1" uib-dropdown uib-dropdown-toggle ng-class="{ 'has-error' : barForm.status_date.$invalid && barForm.status_date.$dirty , 'has-success' : !barForm.status_date.$invalid && barForm.status_date.$dirty }">
                                    <label class="hide" for="status_date">Status Date:</label>
                                    <input type="datetime" id="status_date" name="status_date" ng-model="bar.status_date" placeholder="Status Date" autocomplete="off" />
                                    <i class="fa fa-clock-o color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="barForm.status_date.$invalid && barForm.status_date.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="!barForm.status_date.$invalid && barForm.status_date.$dirty"></span>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn" ng-class="( bar.calendar.open ? 'btn-primary' : 'btn-default' )"><i class="fa fa-calendar"></i></button>
                                    </span>
                                    <div uib-dropdown-menu>
                                        <datetimepicker data-ng-model="bar.status_date" data-datetimepicker-config="{ modelType:'YYYY-MM-DD', minView:'day' }" data-on-set-time="dateSelect( barForm , 'status_date' , 1 )"></datetimepicker>
                                    </div>
                                </div>

                                <!-- Menu Item Calories -->
                                <div class="form-group clearfix" ng-class="{ 'has-error' : barForm.calories.$invalid && barForm.calories.$dirty , 'has-success' : !barForm.calories.$invalid && barForm.calories.$dirty }">
                                    <label class="hide" for="calories">Calories:</label>
                                    <input type="number" id="calories" name="calories" placeholder="Calories" ng-model="bar.calories" />
                                    <i class="fa fa-bar-chart color888 fleft"></i>
                                    <span class="glyphicon glyphicon-remove form-control-feedback push-right" ng-show="barForm.calories.$invalid && barForm.calories.$dirty"></span>
                                    <span class="glyphicon glyphicon-ok form-control-feedback push-right" ng-show="!barForm.calories.$invalid && barForm.calories.$dirty"></span>
                                </div>


                            <!-- /End .tab-pane -->
                            </div>



                            <!-- Serveware Tab -->
                            <div class="tab-pane" ng-class="tab == 2 ? 'active' : ''" ng-show="tab == 2">

                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Serveware</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="bar.serveware.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Serveware
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered" ng-if="bar.serveware.length">
                                        <tbody>



                                            <tr ng-repeat="(index, side) in bar.serveware">

                                                <td>

                                                    <!-- Name -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'serveware' , index , 'name' ) , 'has-success' : hasArraySuccess( 'serveware' , index , 'name' ) }">
                                                        <label class="hide" for="side-name-@{{ index }}">Name</label>
                                                        <input type="text" id="side-name-@{{ index }}" name="serveware[@{{ index }}][name]" placeholder="Serveware" ng-model="bar.serveware[ index ].name" required />
                                                        <i class="fa fa-serveware color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'serveware' , index , 'name' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'serveware' , index , 'name' )"></span>
                                                    </div>

                                                    <!-- Notes -->
                                                    <div class="form-group" ng-class="{ 'has-success' : hasArraySuccess( 'serveware' , index , 'notes' ) }">
                                                        <label class="hide" for="side-notes-@{{ index }}">Notes</label>
                                                        <input type="text" id="side-notes-@{{ index }}" name="serveware[@{{ index }}][notes]" placeholder="Notes" ng-model="bar.serveware[ index ].notes" />
                                                        <i class="fa fa-question-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'serveware' , index , 'notes' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'serveware' , index , 'notes' )"></span>
                                                    </div>

                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="bar.serveware.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>



                            <!-- Sides Tab -->
                            <div class="tab-pane" ng-class="tab == 3 ? 'active' : ''" ng-show="tab == 3">

                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Side</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="bar.sides.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Side
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered" ng-if="bar.sides.length">
                                        <tbody>



                                            <tr ng-repeat="(index, side) in bar.sides">

                                                <td>

                                                    <!-- Name -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'sides' , index , 'name' ) , 'has-success' : hasArraySuccess( 'sides' , index , 'name' ) }">
                                                        <label class="hide" for="side-name-@{{ index }}">Name</label>
                                                        <input type="text" id="side-name-@{{ index }}" name="sides[@{{ index }}][name]" placeholder="Side" ng-model="bar.sides[ index ].name" required />
                                                        <i class="fa fa-condiments color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'sides' , index , 'name' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'sides' , index , 'name' )"></span>
                                                    </div>

                                                    <!-- Notes -->
                                                    <div class="form-group" ng-class="{ 'has-success' : hasArraySuccess( 'sides' , index , 'notes' ) }">
                                                        <label class="hide" for="side-notes-@{{ index }}">Notes</label>
                                                        <input type="text" id="side-notes-@{{ index }}" name="sides[@{{ index }}][notes]" placeholder="Notes" ng-model="bar.sides[ index ].notes" />
                                                        <i class="fa fa-question-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'sides' , index , 'notes' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'sides' , index , 'notes' )"></span>
                                                    </div>

                                                    <!-- Volume -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'sides' , index , 'volume' ) , 'has-success' : hasArraySuccess( 'sides' , index , 'volume' ) }">
                                                        <label class="hide" for="side-volume-@{{ index }}">Volume</label>
                                                        <input type="number" id="side-volume-@{{ index }}" name="sides[@{{ index }}][volume]" placeholder="Volume" ng-model="bar.sides[ index ].volume" step="0.5" min="0" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" required />
                                                        <i class="fa fa-line-chart color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback push-right" ng-show="hasArrayError( 'sides' , index , 'volume' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback push-right" ng-show="hasArraySuccess( 'sides' , index , 'volume' )"></span>
                                                    </div>

                                                    <!-- Unit -->
                                                    <div class="form-group nospace" ng-class="{ 'has-error' : hasArrayError( 'sides' , index , 'unit' ) , 'has-success' : hasArraySuccess( 'sides' , index , 'unit' ) }">
                                                        <label class="hide" for="side-unit-@{{ index }}">Unit</label>
                                                        <div class="select" select-wrapper>
                                                            <select id="side-unit-@{{ index }}" name="sides[@{{ index }}][unit]" ng-model="bar.sides[ index ].unit" required>
                                                                <option value="" disabled selected hidden>Unit</option>
                                                                <option value="floz">floz</option>
                                                                <option value="oz">oz</option>
                                                                <option value="ptn">ptn</option>
                                                                <option value="tsp">tsp</option>
                                                                <option value="ea">ea</option>
                                                            </select>
                                                        </div>
                                                        <i class="fa fa-plus-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'sides' , index , 'unit' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'sides' , index , 'unit' )"></span>
                                                    </div>

                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="bar.sides.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                            <!-- /End -->
                            </div>



                            <!-- Ingredients Tab -->
                            <div class="tab-pane" ng-class="tab == 4 ? 'active' : ''" ng-show="tab == 4">
                                
                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Ingredient</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="bar.ingredients.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Ingredient
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered" ng-if="bar.ingredients.length">

                                        <tbody>

                                            <tr ng-repeat="(index, ingredient) in bar.ingredients">

                                                <td>
                                                    
                                                    <!-- Name -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'ingredients' , index , 'name' ) , 'has-success' : hasArraySuccess( 'ingredients' , index , 'name' ) }">
                                                        <label class="hide" for="ingredient-name-@{{ index }}">Name</label>
                                                        <input type="text" id="ingredient-name-@{{ index }}" name="ingredients[@{{ index }}][name]" placeholder="Ingredient" ng-model="bar.ingredients[ index ].name" required />
                                                        <i class="fa fa-shopping-basket color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'ingredients' , index , 'name' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'ingredients' , index , 'name' )"></span>
                                                    </div>

                                                    <!-- Notes -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'ingredients' , index , 'notes' ) , 'has-success' : hasArraySuccess( 'ingredients' , index , 'notes' ) }">
                                                        <label class="hide" for="ingredient-notes-@{{ index }}">Notes</label>
                                                        <input type="text" id="ingredient-notes-@{{ index }}" name="ingredients[@{{ index }}][notes]" placeholder="Notes" ng-model="bar.ingredients[ index ].notes" />
                                                        <i class="fa fa-question-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'ingredients' , index , 'notes' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'ingredients' , index , 'notes' )"></span>
                                                    </div>

                                                    <!-- Volume -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'ingredients' , index , 'volume' ) , 'has-success' : hasArraySuccess( 'ingredients' , index , 'volume' ) }">
                                                        <label class="hide" for="ingredient-volume-@{{ index }}">Volume</label>
                                                        <input type="number" id="ingredient-volume-@{{ index }}" name="ingredients[@{{ index }}][volume]" placeholder="Volume" ng-model="bar.ingredients[ index ].volume" step="0.5" min="0" ng-pattern="/^[0-9]+(\.[0-9]{1,2})?$/" required />
                                                        <i class="fa fa-line-chart color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback push-right" ng-show="hasArrayError( 'ingredients' , index , 'volume' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback push-right" ng-show="hasArraySuccess( 'ingredients' , index , 'volume' )"></span>
                                                    </div>

                                                    <!-- Unit -->
                                                    <div class="form-group nospace" ng-class="{ 'has-error' : hasArrayError( 'ingredients' , index , 'unit' ) , 'has-success' : hasArraySuccess( 'ingredients' , index , 'unit' ) }">
                                                        <label class="hide" for="ingredient-unit-@{{ index }}">Unit</label>
                                                        <div class="select">
                                                            <select id="ingredient-unit-@{{ index }}" name="ingredients[@{{ index }}][unit]" ng-model="bar.ingredients[ index ].unit" required>
                                                                <option value="" disabled selected hidden>Unit</option>
                                                                <option value="floz">floz</option>
                                                                <option value="oz">oz</option>
                                                                <option value="ptn">ptn</option>
                                                                <option value="tsp">tsp</option>
                                                                <option value="ea">ea</option>
                                                            </select>
                                                        </div>
                                                        <i class="fa fa-plus-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'ingredients' , index , 'unit' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'ingredients' , index , 'unit' )"></span>
                                                    </div>

                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="bar.ingredients.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            <!-- /End .tab-pane -->
                            </div>



                            <!-- Directions Tab -->
                            <div class="tab-pane" ng-class="tab == 5 ? 'active' : ''" ng-show="tab == 5">
                                
                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Direction</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="bar.directions.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Step
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered" ng-if="bar.directions.length">

                                        <tbody>

                                            <tr ng-repeat="(index, direction) in bar.directions" ng-init="i = ( index + 1 )">

                                                <!-- Step # -->
                                                <td width="25">@{{ i }}</td>

                                                <!-- Name -->
                                                <td>
                                                    <div class="form-group nospace" ng-class="{ 'has-error' : hasArrayError( 'directions' , index ) , 'has-success' : hasArraySuccess( 'directions' , index ) }">
                                                        <label class="hide" for="direction-name-@{{ index }}">Name</label>
                                                        <textarea id="direction-name-@{{ index }}" name="directions[@{{ index }}]" placeholder="Step @{{ i }}" ng-model="bar.directions[ index ].direction" required></textarea>
                                                        <i class="fa fa-map-signs color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'directions' , index )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'directions' , index )"></span>
                                                    </div>
                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="bar.directions.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            <!-- /End .tab-pane -->
                            </div>



                            <!-- Media Tab -->
                            <div class="tab-pane" ng-class="tab == 6 ? 'active' : ''" ng-show="tab == 6">
                                <p>
                                    Attach a File:
                                    <small><em>Note: Check the "Checkbox" Beside the Media Item to set it as the Primary Media for the Food &amp; Menu Item</em></small>
                                </p>

                                <!-- File Drop Zone -->
                                <div class="form-group">
                                    <div ngf-drop="upload($files)" ngf-select="upload($files)" ngf-drag-over-class="'dragover'" ngf-keep="'distinct'" ngf-accept="'image/*,video/*'" ng-model="bar.files" class="drop-area text-center" ngf-multiple="true">
                                        
                                        <i class="fa fa-paperclip color888"></i>
                                        Drop Attachment / Click Here

                                    </div>
                                </div>

                                <!-- File List -->
                                <ul class="files clearfix">
                                    <li ng-repeat="( index , file ) in bar.files" class="fleft clearfix">
                                        <progress max="100" value="@{{ file.progress }}" ng-class="file.status ? file.status : null"></progress>
                                        <div class="clearfix">                                             
                                            <small class="fleft font2">
                                                <input type="checkbox" name="primary_media" ng-model="media.media_id" value="@{{ file.attachment_id }}" class="fleft" ng-click="select( bar.files , index )" ng-checked="file.checked" />
                                                <input type="text" name="name" ng-model="file.custom_name" value="@{{ file.name }}" class="fleft" ng-init="file.custom_name = file.name" />
                                            </small>
                                            <i class="fa fa-times fright" ng-click="remove( file , index )"></i>
                                        </div>
                                    </li>
                                </ul>

                            <!-- /End .tab-pane -->
                            </div>



                            <!-- FAQ Tab -->
                            <div class="tab-pane" ng-class="tab == 7 ? 'active' : ''" ng-show="tab == 7">
                                
                                <div class="clearfix">

                                    <p class="fleft">Click the <em>Add Question</em> Button and fill out the fields below</p>

                                    <div class="fright">

                                        <button type="button" class="btn-sm btn-primary font2" ng-click="bar.faq.push({})">
                                            <i class="fa fa-plus-circle"></i>
                                            Add Question
                                        </button>

                                    </div>

                                </div>

                                <div class="clearfix">

                                    <table class="table table-bordered">
                                        <tbody>

                                            <tr ng-repeat="(index, faq) in bar.faq">

                                                <!-- Q&A -->
                                                <td>

                                                    <!-- Category -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'category' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'category' ) }">
                                                        <label class="hide" for="side-category-@{{ index }}">Unit</label>
                                                        <div class="select">
                                                            <select id="side-category-@{{ index }}" name="faq[@{{ index }}][category]" ng-model="bar.faq[ index ].category" required>
                                                                <option value="" disabled selected hidden>Category</option>
                                                                <option value="General Questions">General Questions</option>
                                                                <option value="Tips for Mixing">Tips for Mixing</option>
                                                                <option value="Knowledge for Customers">Knowledge for Customers</option>
                                                                <option value="Promotional Information">Promotional Information</option>
                                                                <option value="Health Risks">Health Risks</option>
                                                                <option value="Other Questions">Other Questions</option>
                                                            </select>
                                                        </div>
                                                        <i class="fa fa-bookmark color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'category' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'category' )"></span>
                                                    </div>

                                                    <!-- Question -->
                                                    <div class="form-group" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'question' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'question' ) }">
                                                        <label class="hide" for="faq-question-@{{ index }}">Question</label>
                                                        <input type="text" id="faq-question-@{{ index }}" name="faq[@{{ index }}][question]" placeholder="Question" ng-model="bar.faq[ index ].question" required />
                                                        <i class="fa fa-question-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'question' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'faq' , index , 'question' )"></span>
                                                    </div>

                                                    <!-- Answer -->
                                                    <div class="form-group nospace" ng-class="{ 'has-error' : hasArrayError( 'faq' , index , 'answer' ) , 'has-success' : hasArraySuccess( 'faq' , index , 'answer' ) }">
                                                        <label class="hide" for="faq-answer-@{{ index }}">Answer</label>
                                                        <textarea id="faq-answer-@{{ index }}" name="faq[@{{ index }}][answer]" placeholder="Answer" ng-model="bar.faq[ index ].answer" required></textarea>
                                                        <i class="fa fa-exclamation-circle color888 fleft"></i>
                                                        <span class="glyphicon glyphicon-remove form-control-feedback" ng-show="hasArrayError( 'faq' , index , 'answer' )"></span>
                                                        <span class="glyphicon glyphicon-ok form-control-feedback" ng-show="hasArraySuccess( 'faq' , index , 'answer' )"></span>
                                                    </div>

                                                </td>

                                                <!-- Actions -->
                                                <td width="75" class="text-center">
                                                    <button type="button" ng-click="bar.faq.splice(index,1)" class="btn-sm btn-danger font2">Delete</button>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                            <!-- /End .tab-pane -->
                            </div>

                        <!-- /End .tab-content -->
                        </div>

                    <!-- /End .bar-panel -->
                    </div>

                <!-- /End .panel-body -->
                </div>

            </main>

        </form>

