'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .filter('buildsFilter', function () {
    return function(builds, repositoryFilter) {
      if (!repositoryFilter) {
        return builds;
      }
    }
  });
