'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Screenshots', function ($q, $http, $timeout, Config, $rootScope, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovScreenshotsChange';


    /**
     * Return the promise with the events list, from cache or the server.
     *
     * @param int companyId
     *   The company ID.
     *
     * @returns {*}
     */
    this.get = function(buildId, page) {
      page = typeof page !== 'undefined' ? page : 1;
      if (cache && cache[buildId + '-' + page]) {
        console.log(cache[buildId + '-' + page]);
        return $q.when(cache[buildId + '-' + page].data);
      }
      
      return getDataFromBackend(buildId, page);
    };

    /**
     * Delete a screenshot.
     *
     * @param id
     *   The screenshot ID.
     */
    this.delete = function(id) {
      return $http({
        method: 'DELETE',
        url: Config.backend + '/api/screenshots/' + id
      });
    };


    /**
     * Return screenshots array from the server.
     *
     * @param int buildId
     *   The build ID.
     * @param int page
     *   Page number.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(buildId, page) {
      var deferred = $q.defer();
      var url = Config.backend + '/api/screenshots';

      var params = {
        'filter[build]': buildId,
        // Sort desc.
        sort: '-id',
        page: page
      };

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        setCache(buildId + '-' + page, response);
        deferred.resolve(response);
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

      // Clear cache in 60 seconds.
      $timeout(function() {
        if (cache.data && cache.data[cacheId]) {
          cache.data[cacheId] = null;
        }
      }, 60);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
