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
    $scope.currentRepos = $scope.repos;

      $scope.orgs = orgs.data;
    // Mark User as user, not as organization.
    Account.get().then(function(response) {
      $scope.username = response.label;

      angular.forEach($scope.orgs, function(organization, key) {
        if (organization.login != $scope.username) {
          // This is not user.
          return;
        }
        $scope.orgs[key]['user'] = true;
        // Set default filter value to 'me' - show all user's repositories.
        $scope.search = {'organization': 'me'};
      })
    });

    // Number of items per page.
    $scope.itemsPerPage = repos.count;

    // Set total count of item according to number of pages.
    if (repos.last) {
      var params = repos.last.href.substr(repos.last.href.indexOf("?")+1);
      $scope.totalReposCount = getQueryVar(params, 'page') * repos.count;
    }
    else {
      $scope.totalReposCount = repos.count;
    }

    // Determine if pager is needed.
    $scope.pager = typeof repos.next !== 'undefined';


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
            // Add build info to the repo info.
            var data = response.data.data[0];
            $scope.repos[key].build = {
              enabled: true,
              id: data.id
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
            $scope.repos[key]._inProgress = false;
          });
      }
    };



    $scope.$watch('search.organization', function() {
      // Organization changed.
      Repos.get(null, $scope.search.organization.toLowerCase()).then(function(result) {
        // Get repos from backend.
        $scope.currentRepos = result.data;

        // Set total count of item according to number of pages.
        if (result.last) {
          var params = result.last.href.substr(result.last.href.indexOf("?")+1);
          $scope.totalReposCount = getQueryVar(params, 'page') * result.count;
        }
        else {
          $scope.totalReposCount = result.count;
        }
        // Set current page to 1.
        $scope.currentPage = 1;
        // Determine if pager is still needed.
        $scope.pager = typeof result.next !== 'undefined';
      });
    });

    $scope.$watch('currentPage', function() {
      // Current page number changed.
      Repos.get(null, $scope.search.organization.toLowerCase(), $scope.currentPage).then(function(result) {
        $scope.currentRepos = result.data;
      });
    });


    /**
     * Get guery parameter value.
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
  });
