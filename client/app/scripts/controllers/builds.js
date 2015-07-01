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

    angular.forEach($scope.builds, function(value, key) {
      $scope.builds[key].new = false;
    });

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var channels = channelManager.getChannels();

    angular.forEach(channels, function(channel) {
      channel.bind('new_ui_build', function(data) {
        Builds.get(parseInt(data.nid), data.type)
          .then(function(val) {
            // Put new item in the begginning of the list.
            val[0].new = true;
            $scope.builds.unshift(val[0]);
            $timeout(function(){
              $scope.builds[0].new = false
            }, 1000);
          });
      });
    });
  });
