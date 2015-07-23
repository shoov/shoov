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

    var channel = channelManager.getChannel($scope.build.repository)
    channel.bind('ci_incident_new', function(data) {
      // Put new item in the begginning of the list.
      $scope.incidents.unshift(data[0]);
    });

    channel.bind('ci_build_new', function(data) {
      // Put new item in the begginning of the list.
      $scope.ciBuildItems.unshift(data[0]);
    });

    channel.bind('ci_build_update', function(data) {
      var id = parseInt(data[0].id);
      var updated = false;
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
