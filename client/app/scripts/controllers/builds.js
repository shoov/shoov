'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:BuildsCtrl
 * @description
 * # BuildsCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('BuildsCtrl', function ($scope, builds, Auth, Config, Builds) {

    $scope.builds = builds;

    angular.forEach($scope.builds, function(value, key) {
      $scope.builds[key].new = false;
    });

    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;

    var pusher = new Pusher('b2ac1e614d90c85ec13b');
    var channel = pusher.subscribe('builds');

    channel.bind('new_build', function(data) {
      var promise = Builds.get(parseInt(data.build.nid), data.build.type);
      promise.then(function(val) {
        val[0].new = true;
        $scope.builds.unshift(val[0]);
      });
    });
  });
