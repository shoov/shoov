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
    $scope.ciBuildItems = [];
    $scope.incidents = incidents;

    // Separate the queue item from the list.
    angular.forEach(ciBuildItems, function(item) {
      if (item.status != 'queue') {
        $scope.ciBuildItems.push(item);
      }
      else {
        $scope.ciBuildQueueItem = item;
      }
    });

    $scope.parseInt = parseInt;

    var channel = channelManager.getChannel($scope.build.repository);
    channel.bind('ci_incident_new', function(data) {
      // Put new item in the beginning of the list.
      $scope.incidents.unshift(data[0]);
    });

    channel.bind('ci_build_new', function(data) {
      // Put the current queue item in the beginning of the list because it
      // needs it to be updated in the "ci_build_update" bind.
      $scope.ciBuildItems.unshift($scope.ciBuildQueueItem);
      // Set the new item as the queue item.
      $scope.ciBuildQueueItem = data[0];
    });

    channel.bind('ci_build_update', function(data) {
      var id = parseInt(data[0].id);
      // Update the existing item.
      angular.forEach($scope.ciBuildItems, function(item, itemId) {
        if (item.id != id) {
          // This is not the item that should be updated.
          return;
        }
        $scope.ciBuildItems[itemId] = data[0];
      });
    });
  });
