'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:JslmIncidentCtrl
 * @description
 * # JslmIncidentCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('JslmIncidentCtrl', function ($scope, jslmIncident, jslmBuild) {
    $scope.incident = jslmIncident[0];
    $scope.build = jslmBuild[0];
  });
