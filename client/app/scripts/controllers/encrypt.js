'use strict';

/**
 * @ngdoc function
 * @name clientApp.controller:EncryptCtr
 * @description
 * # EncryptCtrl
 * Controller of the clientApp
 */
angular.module('clientApp')
  .controller('EncryptCtrl', function ($scope, build, $location, $http) {
    $scope.build = build[0];

    $scope.getKey = function(data) {
      console.log(data);
      var url =  "http://localhost/shoov/www/api/v1.0/encrypt/" + $scope.build.id ;
      var value = {
        "key": data.keyName,
        "value": data.keyValue
      };
      $http({
        method: 'POST',
        url: url,
        data: value
      }).success(function (data, status) {
        $scope.encryptedKey = data.data[0].encrypt;
      });
    }
  });
