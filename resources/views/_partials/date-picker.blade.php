﻿<ul class="dropdown-menu dropdown-menu-left datetime-picker-dropdown" ng-if="isOpen && showPicker == 'date'" ng-style="dropdownStyle" style="left:inherit" ng-keydown="keydown($event)" ng-click="$event.stopPropagation()">
    <li style="padding:0 5px 5px 5px" class="date-picker-menu">
        <div ng-transclude></div>
    </li>
    <li style="padding:5px" ng-if="buttonBar.show">
        <span class="btn-group pull-left" style="margin-right:10px" ng-if="doShow('today') || doShow('clear')">
            <button type="button" class="btn btn-sm btn-info" ng-if="doShow('today')" ng-click="select('today')" ng-disabled="isDisabled('today')">@{{ getText('today') }}</button>
            <button type="button" class="btn btn-sm btn-danger" ng-if="doShow('clear')" ng-click="select('clear')">@{{ getText('clear') }}</button>
        </span>
        <span class="btn-group pull-right" ng-if="(doShow('time') && enableTime) || doShow('close')" >
            <button type="button" class="btn btn-sm btn-default" ng-if="doShow('time') && enableTime" ng-click="changePicker($event, 'time')">@{{ getText('time')}}</button>
            <button type="button" class="btn btn-sm btn-success" ng-if="doShow('close')" ng-click="close(true)">@{{ getText('close') }}</button>
        </span>
    </li>
</ul>