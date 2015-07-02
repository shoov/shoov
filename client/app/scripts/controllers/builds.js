'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('BuildsCtrl', function ($scope, builds, Auth, Config, Builds, channelManager, $timeout) {

    $scope.builds = builds;

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var channels = channelManager.getChannels();

    angular.forEach(channels, function(channel) {
      channel.bind('new_ui_build', function(data) {
        // Put new item in the begginning of the list.
        $scope.builds.unshift(data[0]);
      });
    });
  });
