'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:JslmBuildCtrl
 * @description
 * # JslmBuildCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('JslmBuildCtrl', function ($scope, jslmBuild, jslmIncidents) {

    $scope.build = jslmBuild[0];
    $scope.incidents = jslmIncidents;
  });
