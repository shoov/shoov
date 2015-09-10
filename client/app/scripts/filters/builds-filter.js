'use strict';

/**
 * @ngdoc filter
 * @name clientApp.buildsFilter
 * @description
 * # Filter repositories,
 * Filter in the clientApp.
 */
angular.module('clientApp')
  .filter('buildsFilter', function () {
    return function(builds, repositoryFilter) {
      if (!parseInt(repositoryFilter)) {
        // Return all items when no filter is applied.
        return builds;
      }
      else {
        var items = [];
        angular.forEach(builds, function(build) {
          if (build.repository.id == repositoryFilter) {
            this.push(build);
          }
        }, items);

        return items;
      }
    }
  });
