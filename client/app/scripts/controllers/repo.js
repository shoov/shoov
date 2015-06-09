'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('RepoCtrl', function ($scope, repo, ciBuildItems) {
    $scope.repo = repo;
    $scope.ciBuildItems = ciBuildItems;
  });
