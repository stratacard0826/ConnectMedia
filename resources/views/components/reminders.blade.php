<!-- Reminders -->
<table class="table table-bordered table-striped list reminders-container">
    <thead>
        <tr>
            <th colspan="5" valign="middle">
                <i class="fa fa-clock-o"></i>
                Reminders
                <button type="button" ng-click="config.specifications.push({});" class="btn-sm btn-primary fright">
                    <i class="fa fa-calendar"></i>
                    Add Reminder
                </button>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="(index, data) in config.specifications">
            <td width="23%">

                <!-- Name -->
                <div class="form-group" >
                    <label class="hide" for="reminder-specifications-@{{ index }}">Length</label>
                    <input type="text" number id="reminder-specifications-@{{ index }}" name="specifications[@{{ index }}][timecount]" ng-model="config.specifications[ index ].timecount" title="Length" placeholder="Length" required />
                    <i class="fa fa-clock-o color888 fleft"></i>
                </div>
            </td>
            <td width="23%">
                <!-- Period -->
                <div class="form-group " >
                    <div class="select">
                        <select class="" name="specifications[@{{ index }}][period]" ng-model="config.specifications[ index ].period" required title="Period">
                            <option value="">Period:</option>
                            <option value="day">Day(s) Before Event</option>
                            <option value="week">Week(s) Before Event</option>
                            <option value="month">Month(s) Before Event</option>
                        </select>
                    </div>
                    
                    <i class="fa fa-calendar color888 fleft"></i>
                    
                </div>

            </td>
            <td colspan="@{{config.specifications[ index ].type != 'role' ? 2 : 1}}">
                <!-- type -->
                <div class="form-group " >
                    <div class="select">
                        <select class="" name="specifications[@{{ index }}][type]" ng-model="config.specifications[ index ].type" required title="Type">
                            <option value="">Type:</option>
                            <option value="role">Remind Role</option>
                            <option value="me">Remind me</option>
                            <option value="all">Remind all</option>
                        </select>
                    </div>
                    
                    <i class="fa fa-users color888 fleft"></i>
                </div>
            </td>
            
            <td ng-if="config.specifications[ index ].type == 'role'">
                <!-- roles -->
                <div class="form-group " >
                    <div class="select">
                        <select ng-init="somethingHere = options[0]"  ng-disabled="config.specifications[ index ].type != 'role'" class="" name="specifications[@{{ index }}][role_id]" ng-model="config.specifications[ index ].role_id" ng-required="config.specifications[ index ].type == 'role'" title="Role" 
                        ng-options="role.id as role.name for role in roles.data"
                        >
                            <option value="">Role:</option>
                        </select>
                        <span ng-if="config.specifications[ index ].type == 'role' && !config.specifications[ index ].role_id" class="required"></span>
                    </div>
                    
                    <i class="fa fa-lock color888 fleft"></i>
                </div>
            </td>
            <td width="50" align="center">

                <!-- Delete Product -->
                <button type="button" ng-click="config.specifications.splice(index,1);" class="btn-sm btn-danger font2">Delete</button>

            </td>
        </tr>
    </tbody>
</table>
