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
    $scope.repositories = {};

    // Always watch the amount of repositories that we have to determine
    // whether to display the filter select list or not.
    $scope.$watch('repositories', function(repos) {
      $scope.repositoriesLength = Object.keys(repos).length;
    });

    // Get the repositories from the builds.
    angular.forEach($scope.builds, function(build) {
      if ($scope.repositories[build.repository.id]) {
        // Don't add the same repository twice.
        return;
      }
      $scope.repositories[build.repository.id] = build.repository.label;
    });

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;
    $scope.repositoryFilter = "0";

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

        // Get new builds. Because after creating a new repository build are
        // created too fast - setting timeout to get new build.
        $timeout(function() {
          // Get new builds and add them to the list.
          Builds.get(null, 'ui_build', data[0].id).then(function(val) {

            var buildCounter = 0;
            angular.forEach(val, function(build) {
              if (build.repository.id != data[0].id) {
                // Don't add build if it doesn't belong to the requested repo.
                return;
              }

              $scope.builds.unshift(build);
              buildCounter++;
            });

            // Add new repo to filter only if there's new builds.
            if (buildCounter) {
              $scope.repositories[data[0].id] = data[0].label;
              $scope.repositoriesLength++;
            }
          });
        } ,2000);
      });
    });
  });
