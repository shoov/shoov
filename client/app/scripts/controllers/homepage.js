'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:HomepageCtrl
 * @description
 * # HomepageCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('HomepageCtrl', function ($scope, $state, account, $log) {
    if (!account) {
      // Redirect to login.
      $state.go('login');
    }
    $state.go('dashboard.homepage');
  });
