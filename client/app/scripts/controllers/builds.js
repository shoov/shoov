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

    /**
     * Listen to new build events and add new builds to the list.
     *
     * @param channels
     *  All channels that are listened.
     */
    $scope.addNewBuilds = function(channels) {
      angular.forEach(channels, function(channel) {
        channel.bind('new_ui_build', function(data) {
          // Put new item in the begginning of the list.
          $scope.builds.unshift(data[0]);
        });
      });
    };

    // Get list of chanels.
    var channels = channelManager.getChannels();

    angular.forEach(channels, function(channel) {

      // Listen for new repository was created event.
      channel.bind('new_repo', function(data) {
        // Add new chanel.
        channelManager.addChannel(data.id);
        channels = channelManager.getChannels();
        // Listen to new build event also on the new channel.
        $scope.addNewBuilds(channels);

        // Get new builds. Cause after creating new repo build are created too
        // fast - setting timeout to get all build in order to find new ones
        // if exist and set listener for future created builds.
        $timeout(function() {
          // Get all builds.
          Builds.get(null, 'ui_build').then(function(newBuilds) {
            // Add only new builds to the list.
            angular.forEach(newBuilds, function(build) {
              var inScope = false;
              angular.forEach($scope.builds, function(value) {
                if (value.id == build.id) {
                  inScope = true;
                }
              });
              if (inScope) {
                return;
              }
              $scope.builds.unshift(build);
            });
          });
        } ,2000);
      });
    });

    // Add new builds to the list
    $scope.addNewBuilds(channels);

  });
