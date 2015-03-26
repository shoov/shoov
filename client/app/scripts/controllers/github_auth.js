'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:GithubAuthCtrl
 * @description
 * # GithubAuthCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('GithubAuthCtrl', function ($scope, Auth, $window) {
    // @todo: Find a better way to get the code, which is returned by GitHub with
    // the query string before the hash (#) sign.
    var code = $window.location.search.replace('?code=', '');

    Auth.authByGithubCode(code)
      .then(function(data) {
        // Login was ok.
        $state.go('homepage');
      })
      .catch(function(data) {
        // @todo: Add error message.
        console.log(data);
      });


  });
