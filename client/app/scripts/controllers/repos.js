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

      if (!repo.build || !repo.build.id) {
        $log.log('ctrl enable');
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
        $log.log('ctrl disable');
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

  });
