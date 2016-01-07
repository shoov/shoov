'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:JslmCtrl
 * @description
 * # JslmCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('JslmCtrl', function ($scope, jslmBuilds) {

    $scope.builds = jslmBuilds;
  });
