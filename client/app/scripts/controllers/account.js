'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:AccountCtrl
 * @description
 * # AccountCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('AccountCtrl', function ($scope, account, Auth) {
    $scope.account = account;
    $scope.account.accessToken = Auth.getAccessToken();
  });
