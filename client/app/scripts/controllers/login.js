'use strict';


angular.module('clientApp')
  .controller('ModalInstanceCtrl', function ($scope, $modalInstance) {

  $scope.ok = function () {
    $modalInstance.close();
  }
});



/**
 * @ngdoc function
 * @name clientApp.controller:LoginCtrl
 * @description
 * # LoginCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('LoginCtrl', function ($scope, Auth, $state, Config) {

    // Will be FALSE during login GET period - will cause the login button to be
    // disabled.
    $scope.loginButtonEnabled = true;

    // Will be TRUE after failed login attempt.
    $scope.loginFailed = false;

    /**
     * Login a given user.
     *
     * If everything goes well, change state to 'main'.
     *
     * @param user
     *   Object with the properties "username" and "password".
     */
    $scope.login = function(user) {
      $scope.loginButtonEnabled = false;
      Auth.login(user)
        .then(function() {
          $state.go('dashboard.builds');
        }, function() {
          $scope.loginButtonEnabled = true;
          $scope.loginFailed = true;
        });
    };

    $scope.githubClientId = Config.githubClientId;

    $scope.githubPrivateAccess = false;

  });



