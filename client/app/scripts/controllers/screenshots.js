'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('ScreenshotsCtrl', function ($scope, screenshots, $state, $stateParams, $log) {

    // Initialize values.
    $scope.screenshots = screenshots.data;

    $scope.selected = [];

    $scope.select = function(id) {
      $scope.selected.push(id);
    };

    $scope.unselect = function(id) {

    };
  });
