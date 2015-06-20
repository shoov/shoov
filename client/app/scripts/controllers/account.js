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

    channelManager.getClient().bind_all(function(eventName, data) {
      $log.log(eventName, data);
    })

    var channels = channelManager.getChannels();
    channels['29'].bind('foo', function(data) {
      $log.log(data);
    })
  });
