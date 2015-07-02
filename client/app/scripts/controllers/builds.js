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

    /**
     * Listen to new build events and add new builds to the list.
     *
     * @param channels
     *  All channels that are listened.
     */
    $scope.addNewBuilds = function(channels) {
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
    };

    // Get list of chanels.
    var channels = channelManager.getChannels();

    angular.forEach(channels, function(channel) {

      // Listen for new repository was created event.
      channel.bind('new_repo', function(data) {
        // Add new chanel.
        channelManager.addChannel(data.nid);
        channels = channelManager.getChannels();
        // Listen to new build event also on the new channel.
        $scope.addNewBuilds(channels);

        // Get new builds. Using timeout 'cause after calling mocha builds are
        // created next second after new repository - it's too early.
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
              build.new = true;
              $scope.builds.unshift(build);
            });
          });
        } ,1000);
        // After adding new builds to the list - remove highlighting.
        $timeout(function() {
          angular.forEach($scope.builds, function(value, key) {
            $scope.builds[key].new = false;
          });
        }, 2000);

      });
    });

    // Add new builds to the list
    $scope.addNewBuilds(channels);

  });
