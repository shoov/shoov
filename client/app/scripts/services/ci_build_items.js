'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('CiBuildItems', function ($q, $http, $timeout, Config, $rootScope, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovCiBuildItemsChange';

    /**
     * Return the promise with the events list, from cache or the server.
     *
     * @param int id
     *   The repository or CI build item ID.
     * @param string type
     *   The type of the ID Allowed values are "ci_build" and "ci_build_item".
     *   Defaults to "ci_build_item".
     *
     * @returns {*}
     */
    this.get = function(id, type) {
      type = type || 'ci_build_item';
      var identifier = id + ':' + type;

      if (cache && cache[identifier]) {
        return $q.when(cache[identifier].data);
      }

      return getDataFromBackend(id, type);
    };


    /**
     * Return builds array from the server.
     *
     * @param int id
     *   The repository or CI build item ID.
     * @param string type
     *   The type of the ID. Defaults to "ci_build_item".
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(id, type) {
      var deferred = $q.defer();
      var url = Config.backend + '/api/ci-build-items';

      var params = {};

      if (type == 'ci_build_item') {
        if (id) {
          url += '/' + id;
        }
      }
      else {
        // The ID is the CI build.
        params = {'filter[build]': id};
      }
      // Sort desc.
      params.sort = '-id';

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        var identifier = id + ':' + type;
        setCache(identifier, response.data);
        deferred.resolve(response.data);
      });

      return deferred.promise;
    };

    /**
     * Save cache, and broadcast en event to inform that the data changed.
     *
     * @param int companyId
     *   The company ID.
     * @param data
     *   Object with the data to cache.
     */
    var setCache = function(cacheId, data) {
      // Cache data by company ID.
      cache[cacheId] = {
        data: data,
        timestamp: new Date()
      };

      // Clear cache in 5 seconds.
      $timeout(function() {
        if (cache.data && cache.data[cacheId]) {
          cache.data[cacheId] = null;
        }
      }, 5000);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
