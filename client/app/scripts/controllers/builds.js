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

    angular.forEach($scope.builds, function(value, key) {
      $scope.builds[key].new = false;
    });

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var channels = channelManager.getChannels();
    console.log(channels);

    angular.forEach(channels, function(channel) {
      channel.bind('new_ui_build', function(data) {
        Builds.get(parseInt(data.nid), data.type)
          .then(function(val) {
            // Set property 'new' to true for a new item.
            //val[0].new = true;
            // Put new item in the begginning of the list.
            $scope.builds.unshift(val[0]);
          });
      });
    });
  });
