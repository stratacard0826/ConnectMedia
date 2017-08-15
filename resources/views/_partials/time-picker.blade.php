﻿<ul class="dropdown-menu dropdown-menu-left datetime-picker-dropdown" ng-if="isOpen && showPicker == 'time'" ng-style="dropdownStyle" style="left:inherit" ng-keydown="keydown($event)" ng-click="$event.stopPropagation()">
    <li style="padding:0 5px 5px 5px" class="time-picker-menu">
        <div ng-transclude></div>
    </li>
    <li style="padding:5px" ng-if="buttonBar.show">
        <span class="btn-group pull-left" style="margin-right:10px" ng-if="doShow('now') || doShow('clear')">
            <button type="button" class="btn btn-sm btn-info" ng-if="doShow('now')" ng-click="select('now')" ng-disabled="isDisabled('now')">@{{ getText('now') }}</button>
            <button type="button" class="btn btn-sm btn-danger" ng-if="doShow('clear')" ng-click="select('clear')">@{{ getText('clear') }}</button>
        </span>
        <span class="btn-group pull-right" ng-if="(doShow('date') && enableDate) || doShow('close')" >
            <button type="button" class="btn btn-sm btn-default" ng-if="doShow('date') && enableDate" ng-click="changePicker($event, 'date')">@{{ getText('date')}}</button>
            <button type="button" class="btn btn-sm btn-success" ng-if="doShow('close')" ng-click="close(true)">@{{ getText('close') }}</button>
        </span>
    </li>
</ul>