'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:AccountCtrl
 * @description
 * # AccountCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('AccountCtrl', function ($scope, account, Auth, Config, channelManager, $log) {
    $scope.account = account;
    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    $log.log(account);

    angular.forEach(account.repository, function(repoId) {
      channelManager.set(repoId);
    });

//    channel.bind_all(function(eventName, data) {
//      $log.log(eventName, data);
//    })
  });
