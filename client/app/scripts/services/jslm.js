'use strict';

/**
 * @ngdoc service
 * @name clientApp.Jslm
 * @description
 * # Jslm
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Jslm', function ($q, $http, $timeout, Config, Repos, $rootScope, channelManager, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovJslmBuildChange';

    /**
     * Return JSLM builds array, from cache or the server.
     *
     * @param int id
     *   Optional. The JSLM Build item ID.
     *
     * @returns {*}
     */
    this.get = function(id) {
      // Set default id to 0.
      id = id || 0;
      var identifier = id + ':js_lm_build';

      if (cache && cache[identifier]) {
        return $q.when(cache[identifier].data);
      }

      return getDataFromBackend(id);
    };

    /**
     * Return JSLM Builds array from the server.
     *
     * @param int id
     *   The JSLM Build item ID.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(id) {
      var deferred = $q.defer();

      var url = Config.backend + '/api/js-lm-builds';

      if (id) {
        url += '/' + id;
      }
      else {
        id = 0;
      }

      var params = {};
      params.sort = '-id';

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        var identifier = id + ':js-lm-builds';
        setCache(identifier, response.data);
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
        cache[cacheId] = null;
      }, 5000);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
