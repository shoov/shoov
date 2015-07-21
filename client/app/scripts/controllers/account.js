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
     * Saves Selenium's providers data to the user's account.
     *
     * Sets username and key of Selenium's providers to the "Account" service,
     * Receives the type of providers that needs to be saved/updated.
     *
     * @param type
     *  The type of the provider credentials to save.
     */
    $scope.saveSeleniumProvidersData = function(type) {
      var username = type + '_username';
      var access_key = type == 'sauce' ? type + '_access_key' : type + '_key';

      var data = {};
      data[username] = $scope.account[username];
      data[access_key] = $scope.account[access_key];

      Account.set(data);
    };
  });
