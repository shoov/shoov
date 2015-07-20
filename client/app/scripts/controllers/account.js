'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:AccountCtrl
 * @description
 * # AccountCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('AccountCtrl', function ($scope, account, Auth, Config, Account) {
    $scope.account = account;
    $scope.accessToken = Auth.getAccessToken();
    $scope.backend = Config.backend;
    $scope.showForm = false;

    Account.get().then(function(val) {
      $scope.account.browserstack_username = val.browserstack_username;
      $scope.account.browserstack_key = val.browserstack_key;
      $scope.account.sauce_username = val.sauce_username;
      $scope.account.sauce_access_key = val.sauce_access_key;
    });

    /**
     * Saves additional data to the account.
     *
     * Sets username and key of two external services, BrowserStack and Sauce
     * Labs to the "Account" service.
     */
    $scope.saveAccountData = function() {
      var data = {
        'browserstack_username': $scope.account.browserstack_username,
        'browserstack_key': $scope.account.browserstack_key,
        'sauce_username': $scope.account.sauce_username,
        'sauce_access_key': $scope.account.sauce_access_key
      };

      Account.set(data);
    };
  });
