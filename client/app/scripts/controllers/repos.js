'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ReposCtrl', function ($scope, repos) {

    $scope.repos = repos;


    angular.forEach($scope.repos, function(value, key) {
      $scope.repos[key].selected = false;
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

    /**
     * Enable a repo and create a build.
     *
     * @param id
     *   The screenshot ID.
     */
    $scope.enableBuild = function(id) {
      // We don't wait for the delete too actually happen on the server side,
      // but immediately hide the image.
      // @todo: Use RamdaJs
      angular.forEach($scope.repos, function(value, key) {
        if (value.id == id) {
          $scope.repos.splice(key, 1);
        }
      });
    };

  });
