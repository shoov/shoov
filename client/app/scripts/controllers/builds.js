'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('BuildsCtrl', function ($scope, builds, Builds, Auth, Config, channelManager, $log) {

    $scope.builds = builds;

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var channels = channelManager.getChannels();
    channels['21'].bind('new_ui_build', function(data) {
      $log.log(data);
      data.new = true;
      $scope.builds = Builds.addItem(data);
    });
  });
