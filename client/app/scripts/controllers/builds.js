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

    // Add new builds to the list
    $scope.addNewBuilds(channels);

    angular.forEach(channels, function(channel) {

      // Listen for new repository was created event.
      channel.bind('new_repo', function(data) {
        // Add new chanel.
        channelManager.addChannel(data[0].id);
        var newChannel = channelManager.getChannel(data[0].id);

        $scope.addNewBuilds([newChannel]);

        // Get new builds. Cause after creating new repo build are created too
        // fast - setting timeout to get new build.
        $timeout(function() {
          // Get new builds and add them to the list.
          Builds.getByRepo(null, 'ui_build', data[0].id).then(function(val) {
            angular.forEach(val, function(build) {

              $scope.builds.unshift(build);
            });
          });
        } ,2000);
      });
    });
  });
