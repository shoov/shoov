'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:AccountCtrl
 * @description
 * # AccountCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('AccountCtrl', function ($scope, account, Auth, Config, channelManager) {
    $scope.account = account;
    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    channelManager.set(1);
  });
