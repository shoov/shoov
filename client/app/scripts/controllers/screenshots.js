'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ScreenshotsCtrl', function ($scope, screenshots, build, Auth, filterFilter, Zip, Github, Screenshots, $log) {

    // Initialize values.
    $scope.showDiff = false;
    $scope.screenshots = screenshots;
    $scope.accessToken = Auth.getAccessToken();

    // @todo: Change repo name to be taken from build.
    $scope.repoName = screenshots[0].repository.label;
    $scope.gitBranch = build[0].git_branch;
    $scope.gitCommit = build[0].git_commit.substring(0, 6);

    // Pull request name.
    $scope.prName = 'shoov-' + $scope.gitBranch;
    $scope.prUrl = build[0].pull_request;

    angular.forEach($scope.screenshots, function(value, key) {
      $scope.screenshots[key].selected = false;
      // Find the highest images.
      $scope.screenshots[key].maxHeight = value.regression.height > value.baseline.height ? value.regression.height : value.baseline.height;
    });

    // Selected screenshots.
    $scope.selection = [];

    // Helper method to get selected screenshots.
    $scope.selectedScreenshots = function selectedScreenshots() {
      return filterFilter($scope.screenshots, { selected: true });
    };

    // Watch screenshots for changes.
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

    /**
     * Create a zip file.
     */
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


    $scope.pullRequest = function() {
      var selectedScreenshots = $scope.selectedScreenshots();
      if (!selectedScreenshots) {
        // No selection.
        return;
      }

      // Indicate PR is in progress.
      $scope.prProgress = true;
      $scope.prUrl = null;

      Github
        .createPullRequest(build[0].id, selectedScreenshots, $scope.prName)
        .then(function(data) {
          $scope.prUrl = data.data[0].pull_request;
          $scope.prProgress = false;
        });
    };
  });
