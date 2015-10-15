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
     * User's demo url.
     */
    $scope.url = {'text': account.demo_request_url};

    /**
     * Submit button value.
     */
    $scope.submitValue = 'Save';

    /**
     * Change Submit button value when URL has been changed.
     */
    $scope.changeSubmitValue = function() {
      $scope.submitValue = 'Save';
    };

    /**
     * Set the entered demo url for the current user.
     *
     * @param url
     *  the inserted URL.
     */
    $scope.saveAccountDemoUrl = function(url) {
      var data = {};
      data['demo_request_url'] = url;
      Account.set(data).success(function() {
        // Change submit button value after saving.
        $scope.submitValue = 'Saved';
      });

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
