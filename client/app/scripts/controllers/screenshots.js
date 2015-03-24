'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ScreenshotsCtrl', function ($scope, screenshots, Auth, filterFilter, Zip, $state, $stateParams, $log) {

    // Initialize values.
    $scope.showDiff = false;
    $scope.screenshots = screenshots.data;
    $scope.accessToken = Auth.getAccessToken();

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
