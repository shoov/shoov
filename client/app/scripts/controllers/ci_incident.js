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
    var incidentEnd = $scope.incident.fixed_build ? $scope.incident.updated : (new Date().getTime())/1000;

    var seconds = incidentEnd-incidentStart;
    var minutes = seconds/60;
    var hours = minutes/60;
    var days = hours/24;

    if (days >= 1) {
      $scope.downTime = days.toFixed(1) + ' Days';
    }
    else if (hours >= 1) {
      $scope.downTime = hours.toFixed(1) + ' Hours';
    }
    else if (minutes >= 1) {
      $scope.downTime = minutes.toFixed(0) + ' Minutes';
    }
    else {
      $scope.downTime = seconds.toFixed(0) + ' Seconds';
    }
  });
