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

    $scope.filteredScreenshots = [];
    $scope.currentPage = 1;
    $scope.itemsPerPage = 50;


    $scope.imageStyles = {'self': 'Original'};

    // Image styles for select list.
    angular.forEach(screenshots[0].regression.styles, function(style, key) {
      $scope.imageStyles[key] = style.label;
    });
    $scope.imageStyle = 'self';

    $scope.$watch('currentPage + numPerPage', function() {
      var begin = (($scope.currentPage - 1) * $scope.itemsPerPage);
      var end = begin + $scope.itemsPerPage;

      $scope.filteredScreenshots = $scope.screenshots.slice(begin, end);
    });

    // @todo: Change repo name to be taken from build.
    $scope.repoName = screenshots[0].repository.label;
    $scope.gitBranch = build[0].git_branch;
    $scope.gitCommit = build[0].git_commit.substring(0, 6);

    // Pull request name.
    $scope.prName = 'shoov-' + $scope.gitBranch;
    $scope.prUrl = build[0].pull_request;

    angular.forEach($scope.screenshots, function(value, key) {
      $scope.screenshots[key].selected = false;

      // Set max values for image width and height for original images size.
      $scope.screenshots[key].maxHeight = {'self' : value.regression.height > value.baseline.height ? parseInt(value.regression.height) : parseInt(value.baseline.height)};
      $scope.screenshots[key].maxWidth = {'self': value.regression.width > value.baseline.width ? parseInt(value.regression.width) : parseInt(value.baseline.width)};
      // Set max values for image width and height for different image styles.
      angular.forEach($scope.imageStyles, function(style, styleKey){
        if (styleKey == 'self') {
          return;
        }
        else {
          $scope.screenshots[key].maxWidth[styleKey] = parseInt(value.regression['styles'][styleKey].width);
          $scope.screenshots[key].maxHeight[styleKey] = $scope.screenshots[key].maxWidth[styleKey] * $scope.screenshots[key].maxHeight['self'] / $scope.screenshots[key].maxWidth['self'];
        }
      });
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
     * Select/deselect all screenshots
     */
    $scope.selectAll = function() {
      angular.forEach($scope.screenshots, function(screenshot) {
        screenshot.selected = !$scope.allSelected;
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
