'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ScreenshotsCtrl', function ($scope, screenshots, build, Auth, filterFilter, Zip, Screenshots, $state, $stateParams, $log) {

    // Initialize values.
    $scope.showDiff = false;
    $scope.screenshots = screenshots;
    $scope.accessToken = Auth.getAccessToken();

    // @todo: Change repo name to the name, instead of ID.
    $scope.repoName = build[0].repository;
    $scope.gitBranch = build[0].git_branch;
    $scope.gitCommit = build[0].git_commit.substring(0, 6);

    angular.forEach($scope.screenshots, function(value, key) {
      $scope.screenshots[key].selected = false;
    });

    // selected fruits
    $scope.selection = [];

    // helper method to get selected fruits
    $scope.selectedScreenshots = function selectedScreenshots() {
      return filterFilter($scope.screenshots, { selected: true });
    };

    // watch fruits for changes
    $scope.$watch('screenshots|filter:{selected:true}', function (screenshots) {
      $scope.selection = screenshots.map(function (screenshot, key) {
        return key;
      });
    }, true);

    /**
     * Delete a screenshot.
     *
     * @param id
     *   The screenshot ID.
     */
    $scope.delete = function(id) {
      Screenshots.delete(id);

      // We don't wait for the delete too actually happen on the server side,
      // but immediately hide the image.
      // @todo: Use RamdaJs
      angular.forEach($scope.screenshots, function(value, key) {
        if (value.id == id) {
          $scope.screenshots.splice(key, 1);
        }
      });
    };

    $scope.zip = function() {

      var selectedScreenshots = $scope.selectedScreenshots();
      if (!selectedScreenshots) {
        // No selection.
        return;
      }

      var data = [];
      selectedScreenshots.forEach(function (obj) {
        var imageData = {
          // Use the user token
          url: obj.regression.self + '?access_token=' + Auth.getAccessToken(),
          // @todo: improve.
          filename: obj.baseline_name
        };

        data.push(imageData);
      });
      Zip.createZip(data);
    };
  });
