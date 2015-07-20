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
      $scope.account.saucelabs_username = val.saucelabs_username;
      $scope.account.saucelabs_key = val.saucelabs_key;
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
        'saucelabs_username': $scope.account.saucelabs_username,
        'saucelabs_key': $scope.account.saucelabs_key
      };

      Account.set(data);
    };
  });
