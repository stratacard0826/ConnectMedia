
    <!-- Search -->
    <div id="search" ng-controller="SearchFormController as search">
        <form name="searchForm" ng-submit="submit(searchForm)">
            <input type="text" name="query" ng-model="search.query" placeholder="Search ..." autocomplete="off" />
            <button name="submit" class="fa fa-search"></button>
        </form>
    </div>