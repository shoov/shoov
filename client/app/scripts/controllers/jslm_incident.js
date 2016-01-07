'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:JslmIncidentCtrl
 * @description
 * # JslmIncidentCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('JslmIncidentCtrl', function ($scope, jslmIncident, Jslm) {
    $scope.incident = jslmIncident[0];
    Jslm.get($scope.incident.build).then(function(result) {
      $scope.build = result[0];
    });
  });
