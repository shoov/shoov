'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('CiBuildItemCtrl', function ($scope, ciBuildItem, Builds) {

    $scope.ciBuildItem = ciBuildItem[0];

    // Get parent build info for the breadcrumbs.
    Builds.get($scope.ciBuildItem.build, 'ci_build').then(function(data) {
      $scope.build = data[0];
    });
  });
