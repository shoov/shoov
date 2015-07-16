'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:RepoCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('RepoCtrl', function ($scope, build, ciBuildItems, incidents, channelManager) {
    $scope.build = build[0];
    $scope.ciBuildItems = ciBuildItems;
    $scope.incidents = incidents;

    $scope.parseInt = parseInt;

    var channels = channelManager.getChannels();
    console.log(channels);
    angular.forEach(channels, function(channel) {
      channel.bind('new_incident', function(data) {
        // Put new item in the begginning of the list.
        $scope.incidents.unshift(data[0]);
      });

      channel.bind('new_ci_build', function(data) {
        // Put new item in the begginning of the list.
        $scope.ciBuildItems.unshift(data[0]);
      });

      channel.bind('update_ci_build', function(data) {
        var id = data[0].id;
        // Update the existing item.
        angular.forEach($scope.ciBuildItems, function(item, itemId) {
          if (item.id != id) {
            return;
          }
          $scope.ciBuildItems[itemId] = data[0];
        });
      });
    });
  });
