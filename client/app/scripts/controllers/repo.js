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

    // List of build statuses.
    $scope.buildStatuses = {
      'queue': 'Queue',
      'in_progress': 'In progress',
      'error': 'Error',
      'done': 'Done'
    };

    // List of build statuses.
    $scope.intervals = {
      180: '3 Min',
      3600: '1 Hour',
      86400: '1 Day'
    };

    // Separate the queue or in progress item from the history list.
    angular.forEach(ciBuildItems, function(item) {
      if (item.status != 'queue' && item.status != 'in_progress') {
        $scope.ciBuildItems.push(item);
      }
      else {
        $scope.ciBuildItemQueueOrInProgress = item;
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
      $scope.ciBuildItems.unshift($scope.ciBuildItemQueueOrInProgress);
      // Set the new item as the queue item.
      $scope.ciBuildItemQueueOrInProgress = data[0];
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

      // Check if the updated item is the Queue Or InProgress item.
      if ($scope.ciBuildItemQueueOrInProgress.id == id) {
        $scope.ciBuildItemQueueOrInProgress = data[0];
      }
    });
  });
