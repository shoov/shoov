'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('CiBuildItemCtrl', function ($scope, ciBuildItem) {

    $scope.ciBuildItem = ciBuildItem[0];
  });
