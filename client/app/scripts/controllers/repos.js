'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ReposCtrl', function ($scope, repos, Builds, Repos, $log) {

    $scope.repos = repos;


    angular.forEach($scope.repos, function(value, key) {
      $scope.repos[key].selected = !!$scope.repos[key].build && !!$scope.repos[key].build.enabled;
    });

    // Selected repos.
    $scope.selection = [];

    // Helper method to get selected repos.
    $scope.selectedRepos = function selectedRepos() {
      return filterFilter($scope.repos, { selected: true });
    };

    // Watch repos for changes.
    $scope.$watch('repos|filter:{selected:true}', function(repos) {
      $scope.selection = repos.map(function(repo, key) {
        return key;
      });
    }, true);

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
      var scopeKey = getKeyByRepo(repo);
      if (repo.selected) {
        // Create repo on shoov, and auto enable build.

        Builds
          .enable(repo)
          .then(function(response) {
            // Add build info to the repo info.
            $log.log(response);
          })
      }
      else {
        // Disable build.
        Builds
          .disable(repo.build.id)
          .then(function(response) {
            // Update build info to the repo info.
            $log.log(response);
          });


      }
    };

  });
