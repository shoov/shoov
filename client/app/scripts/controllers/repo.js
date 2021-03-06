'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:RepoCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('RepoCtrl', function ($scope, $timeout, build, ciBuildItems, Builds, CiBuildItems, incidents, channelManager, Config, $location) {
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

    // List of build possible intervals.
    $scope.intervals = {
      180: '3 Min',
      3600: '1 Hour',
      86400: '1 Day'
    };

    $scope.buildBadgeImageLink = Config.backend + '/api/ci-build-status/' + $scope.build.id + '?status_token=' + $scope.build.status_token;
    $scope.buildBadgeLink = '[![Build Status](' + $scope.buildBadgeImageLink + ')](' + $location.absUrl().split('?')[0] + ')';

    // Show the success icon when there's a response from the backend.
    $scope.responseStatus = false;

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

    /**
     * Updates the build interval.
     *
     * Sends a request to the backend through the Builds service to update the
     * build interval of the CI-Build entity, Updates the responseClass which is
     * responsible for toggling the success icon next to the input.
     */
    $scope.updateInterval = function() {
      var params = {
        'interval': $scope.build.interval
      };

      Builds
        .update($scope.build.id, 'ci_build', params)
        .then(function() {
          // If the latest Build item has status 'queue' - update Build item's
          // start time.
          if ($scope.ciBuildItemQueueOrInProgress.status == "queue") {
            var timestamp = parseInt(Date.now() / 1000) + parseInt($scope.build.interval);

            CiBuildItems
              .update($scope.ciBuildItemQueueOrInProgress.id, {'start_timestamp': timestamp})
              .then(showSuccessMark);
          }
          else {
            // The latest Build item is already in progress.
            showSuccessMark();
          }

        });
    };

    /**
     * Show and then hide the success icon after 3 seconds of receiving the
     * response.
     */
    var showSuccessMark = function() {
      $scope.responseStatus = true;

      // Hide the success icon after 3 seconds of receiving the response.
      $timeout(function() {
        $scope.responseStatus = false;
      }, 3000);
    };

    var channel = channelManager.getChannel($scope.build.repository);

    if (channel == undefined) {
      // User is not assigned to this channel yet.
      channel = channelManager.addChannel($scope.build.repository);
    }

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
