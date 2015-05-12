'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('CiBuildItemsCtrl', function ($scope, ciBuildItems) {

    $scope.ciBuildItems = ciBuildItems;
  });
