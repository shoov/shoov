'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('BuildsCtrl', function ($scope, builds, Auth, Config, Builds, channelManager) {

    $scope.builds = builds;

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var channels = channelManager.getChannels();

    angular.forEach(channels, function(channel) {
      channel.bind('new_ui_build', function(data) {
        // Put the new UI build item in the beginning of the list.
        $scope.builds.unshift(data[0]);
      });
    });
  });
