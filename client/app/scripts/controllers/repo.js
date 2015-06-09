'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:RepoCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('RepoCtrl', function ($scope, build, ciBuildItems) {
    $scope.build = build[0];
    $scope.ciBuildItems = ciBuildItems;
  });
