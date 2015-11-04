'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ReposCtrl', function ($scope, repos, orgs, Orgs, Builds, Repos, Account, $filter, $log) {
    $scope.repos = repos.data;

    $scope.orgs = orgs.data;

    // Set default filter value to 'me' - show all user's repositories.
    $scope.organization ='me';

    // Mark User as user, not as organization.
    Account.get().then(function(response) {
      $scope.username = response.label;

      angular.forEach($scope.orgs, function(organization, key) {
        if (organization.label != $scope.username) {
          // This is not the current user.
          return;
        }
        $scope.orgs[key]['user'] = true;
      })
    });

    // Number of items per page.
    $scope.itemsPerPage = repos.count;

    // Set total count of item according to number of pages.
    setUpPager(repos);


    // Allow parseInt an expression.
    // @todo: Service should be responsible of this.
    $scope.parseInt = parseInt;

    var getKeyByRepo = function(repo) {
      var scopeKey = 0;
      angular.forEach($scope.repos, function(value, key) {
        if (repo.id == value.id) {
          scopeKey = key;
        }
      });

      return scopeKey;

    };

    /**
     * Enable a repo and create a build.
     *
     * @param id
     *   The screenshot ID.
     */
    $scope.toggleRepo = function(repo) {
      var key = getKeyByRepo(repo);
      $scope.repos[key]._inProgress = true;

      if (!repo.build || !repo.build.enabled) {
        // Create repo on shoov, and auto enable build.
        Builds
          .enable(repo)
          .then(function(response) {
            // Add build info to the repo info.q
            var data = response.data.data[0];
            $scope.repos[key].build = {
              enabled: data.enabled,
              id: data.id,
              disable_reason: data.disable_reason
            };

            $scope.repos[key].shoov_id = data.repository;
            $scope.repos[key]._inProgress = false;
          })
      }
      else {
        // Disable build.
        Builds
          .disable(repo)
          .then(function(response) {
            // Update build info to the repo info.
            $scope.repos[key].build.enabled = false;
            $scope.repos[key].build.disable_reason = 'none';
            $scope.repos[key]._inProgress = false;
          });
      }
    };



    $scope.$watch('organization', function() {
      // Organization changed.
      Repos.get($scope.organization.toLowerCase()).then(function(result) {
        // Get repos from backend.
        $scope.repos = result.data;
        setUpPager(result);
      });
    });

    $scope.$watch('currentPage', function() {
      // Current page number changed.
      Repos.get($scope.organization.toLowerCase(), null, $scope.currentPage).then(function(result) {
        $scope.repos = result.data;
      });
    });


    /**
     * Get query parameter value.
     * @see http://stackoverflow.com/questions/2090551/parse-query-string-in-javascript#answer-8219439
     *
     * @param url
     *  String with GET parameters.
     *  e.g. 'param1=1&param2=2'
     * @param varName
     *  Name of the parameter to return.
     *
     * @returns {boolean}
     *  Return value of the parameter or false if doesn't exist.
     */
    function getQueryVar(url, varName){
      // Append an '&' to keep the RegExp simple.
      var queryStr = url + '&';

      // Dynamic replacement RegExp
      var regex = new RegExp('.*?[&\\?]' + varName + '=(.*?)&.*');

      // Apply RegExp to the query string
      var val = queryStr.replace(regex, "$1");

      // If the string is the same, we didn't find a match - return false
      return val == queryStr ? false : val;
    }

    /**
     * Set total count of items according to number of pages.
     * Set current page to 1.
     * Determine if pager is needed.
     *
     * @param data
     *  Object with repositories data.
     */
    function setUpPager(data) {
      // Set total count of item according to number of pages.
      if (data.last) {
        var params = data.last.href.substr(data.last.href.indexOf("?") + 1);
        $scope.totalReposCount = getQueryVar(params, 'page') * data.count;
      }
      else {
        $scope.totalReposCount = data.count;
      }
      // Set current page to 1.
      $scope.currentPage = 1;
      // Determine if pager is still needed.
      $scope.pager = !!data.next;

      $scope.itemsPerPage = data.count;
    }
  });
