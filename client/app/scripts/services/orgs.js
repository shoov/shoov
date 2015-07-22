'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Orgs', function ($q, $http, $timeout, Config, $rootScope, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovOrgsChange';

    this.create = function(label) {
      return $http.post(Config.backend + '/api/orgs', {label: label});
    };


    /**
     * Return the promise with the events list, from cache or the server.
     *
     * @param int orgId
     *   The organization ID.
     *
     * @returns {*}
     */
    this.get = function(orgId) {
      if (cache && cache[orgId]) {
        return $q.when(cache[orgId].data);
      }

      return getDataFromBackend(orgId);
    };


    /**
     * Return builds array from the server.
     *
     * @param int buildId
     *   The build ID.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(orgId) {
      var deferred = $q.defer();
      var url = Config.backend + '/api/github_orgs';

      if (orgId) {
        url += '/' + orgId;
      }

      var params = {
        sort: '-id'
      };

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        setCache(orgId, response.data);
        deferred.resolve(response.data);
      });

      return deferred.promise;
    };

    /**
     * Save cache, and broadcast en event to inform that the data changed.
     *
     * @param int cacheId
     *   The cache ID.
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
