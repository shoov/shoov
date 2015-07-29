'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('CiIncidentCtrl', function ($scope, incident, Builds) {

    $scope.incident = incident[0];

    Builds.get($scope.incident.ci_build, 'ci_build').then(function(data) {
      $scope.build = data[0];
    });

    var incidentStart = $scope.incident.created;
    var incidentEnd = $scope.incident.updated ? $scope.incident.updated : new Date();

    var seconds = incidentEnd-incidentStart;
    var minutes = seconds/60;
    var hours = minutes/60;
    var days = hours/24;

    if (minutes >= 1) {
      $scope.downTime = Math.floor(minutes) + ' Minutes';
    }
    else if (hours >= 1) {
      $scope.downTime = Math.floor(hours) + ' Hours';
    }
    else if (days >= 1) {
      $scope.downTime = Math.floor(days) + ' Days';
    }
  });
