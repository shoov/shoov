'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:GithubAuthCtrl
 * @description
 * # GithubAuthCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('GithubAuthCtrl', function ($scope, Auth, $state, $window, $location, $log, $stateParams) {
    // $log.log($location.search('code'));
    // $log.log($location.search());
    // @todo: Find a better way to get the code, which is returned by GitHub with
    // the query string before the hash (#) sign.
    $log.log($window.location);

    var code = $window.location.search.replace('?code=', '');

  });
