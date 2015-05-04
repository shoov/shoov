'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('BuildsCtrl', function ($scope, builds, Auth, Config) {

    $scope.builds = builds;

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;
  });
