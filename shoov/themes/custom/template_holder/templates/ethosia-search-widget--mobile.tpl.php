<div id="search-menu" class="search-menu collapse clearfix" ng-controller="SearchWidgetCtrl">

  <form role="form" class="search-form" data-ng-submit="submitSearch()">
    <div class="search-header clearfix" ng-show="searchTypeSelected">
      <span class="search-title pull-right">{[{ searchTypeLabel }]}</span>
      <span class="search-head pull-left"><a ng-click="searchTypeSelected = false">חזרה לחיפוש הראשי</a></span>
    </div>

    <div>

      <div id="free-search-box">
        <input class="text-free" data-ng-model="searchParams.freeText" name="freeText" type="text" placeholder="חיפוש חופשי" />
        <button type="submit" class="btn-icon next-arrow">></button>
      </div>

    </div>

    <!-- Main search menu -->
    <ul class="search-options bordered" ng-hide="searchTypeSelected">
      <li>
        <a class="clearfix" ng-click="selectSearchType('job', 'חפש משרה')">
          <span class="text-description pull-right subject-search">חפש משרה</span>
          <span class="pull-left next-arrow">></span>
        </a>
      </li>
      <li>
        <a class="clearfix" href="<?php print url('salary_report'); ?>">
          <span class="text-description pull-right subject-search">חפש סקר שכר</span>
          <span class="pull-left next-arrow">></span>
        </a>
      </li>
      <li>
        <a class="clearfix" ng-click="selectSearchType('company', 'חפש חברה')">
          <span class="text-description pull-right subject-search">חפש חברה</span>
          <span class="pull-left next-arrow">></span>
        </a>
      </li>
      <li>
        <a class="clearfix" ng-click="selectSearchType('adviser', 'חפש יועץ')">
          <span class="text-description pull-right subject-search">חפש יועץ</span>
          <span class="pull-left next-arrow">></span>
        </a>
      </li>
    </ul>

    <!-- Job search options -->
    <ul class="search-options bordered" ng-show="searchTypeSelected && searchType == 'job'">
      <li class="group collapsed clearfix" data-toggle="collapse" href="#search-widget-job-categories">
        <span class="text-description pull-right">תחום</span>
        <label for="select" class="pull-left"><span class="toggle-sign"></span></label>
      </li>
      <li class="clearfix">
        <ul id="search-widget-job-categories" class="collapsed-options collapse col-xs-12">
          <li ng-repeat="term in vocabularies.job_categories" styled-checkbox ng-click="toggleSelection('job_category',term.id)" ng-checked="term.selected" id="filter_job_category_{[{term.id}]}" name="filter_job_category[]" value="term.id" label="{[{term.title}]}"></li>
        </ul>
      </li>

      <li class="group collapsed clearfix" data-toggle="collapse" href="#search-widget-job-role-types">
        <span class="text-description pull-right">סוג משרה</span>
        <label for="select" class="pull-left"><span class="toggle-sign"></span></label>
      </li>
      <li class="clearfix">
        <ul id="search-widget-job-role-types" class="collapsed-options collapse col-xs-12">
          <li ng-repeat="term in vocabularies.job_role_types" styled-checkbox ng-click="toggleSelection('job_role_types',term.id)" ng-checked="term.selected" id="filter_job_role_type_{[{ term.id }]}" name="filter_job_role_type[]" value="term.id" label="{[{ term.title }]}"></li>
        </ul>
      </li>

      <li class="group collapsed clearfix" data-toggle="collapse" href="#search-widget-job-scopes">
        <span class="text-description pull-right">היקף משרה</span>
        <label for="select" class="pull-left"><span class="toggle-sign"></span></label>
      </li>
      <li class="clearfix">
        <ul id="search-widget-job-scopes" class="collapsed-options collapse col-xs-12">
          <li ng-repeat="term in vocabularies.job_scope_types" styled-checkbox ng-click="toggleSelection('job_scope',term.id)" ng-checked="term.selected" id="filter_job_scope_{[{ term.id }]}" name="filter_job_scope[]" value="term.id" label="{[{ term.title }]}"></li>
        </ul>
      </li>

      <li class="group collapsed clearfix" data-toggle="collapse" href="#search-widget-job-locations">
        <span class="text-description pull-right">אזור בארץ</span>
        <label for="select" class="pull-left"><span class="toggle-sign"></span></label>
      </li>
      <li class="clearfix">
        <ul id="search-widget-job-locations" class="collapsed-options collapse col-xs-12">
          <li ng-repeat="term in vocabularies.geo_areas" styled-checkbox ng-click="toggleSelection('geo_area',term.id)" ng-checked="term.selected" id="filter_geo_area_{[{term.id}]}" name="filter_geo_area[]" value="term.id" label="{[{term.title}]}"></li>
        </ul>
      </li>
    </ul>
    <button type="submit" class="btn btn-default btn-md active pull-left search-btn .border-radius"></button>
  </form>
</div>
