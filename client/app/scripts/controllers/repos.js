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
    $scope.repos = repos;

    $scope.orgs = orgs;
    // Mark User as user, not as organization.
    Account.get().then(function(response) {
      $scope.username = response.label;

      angular.forEach($scope.orgs, function(organization, key) {
        if (organization.login != $scope.username) {
          // This is not user.
          return;
        }
        $scope.orgs[key]['user'] = true;
      })
    });
    // Add value 'All'.
    $scope.orgs.unshift({'login': 'All', 'id': ''});
    // Set default filter value to 'All'.
    $scope.search = {'organization': ''};

    $scope.itemsPerPage = 20;

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
      // Filter repositories by selected organization.
      $scope.filteredRepos = $filter('filter')($scope.repos, $scope.search);
      // Set total number of repositories (filtered).
      $scope.totalReposCount = $scope.filteredRepos.length;
      $scope.currentPage = 1;
      // Determine if pager is needed.
      $scope.pager = $scope.totalReposCount > $scope.itemsPerPage;
      // Set new piece of repositories for 1st page.
      $scope.currentRepos = $scope.filteredRepos.slice($scope.itemsPerPage*($scope.currentPage - 1), $scope.itemsPerPage * $scope.currentPage);
    });

    $scope.$watch('currentPage', function() {
      // Set new piece of repositories for the current page.
      $scope.currentRepos = $scope.filteredRepos.slice($scope.itemsPerPage*($scope.currentPage - 1), $scope.itemsPerPage * $scope.currentPage);
    });
  });
