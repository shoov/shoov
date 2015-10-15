'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:DashboardCtrl
 * @description
 * # DashboardCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('DashboardCtrl', function ($scope, account, selectedCompany, Auth, $state, Account,  $log) {

    /**
     * Determine if user doesn't have any repositories monitoring yet.
     *
     * @type {boolean}
     */
    $scope.userHasNoData = !account.repository;

    /**
     * Set the entered demo url for the current user.
     *
     * @param url
     *  the inserted URL.
     */
    $scope.saveAccountDemoUrl = function(url) {
      var data = {};
      data['demo_request_url'] = url;
      Account.set(data);
      $scope.userHasNoData = false;
    };

    /**
     * Logout current user.
     *
     * Do whatever cleaning up is required and change state to 'login'.
     */
    $scope.logout = function() {
      Auth.logout();
      $state.go('login');
    };
  });
