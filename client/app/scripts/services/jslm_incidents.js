'use strict';

/**
 * @ngdoc service
 * @name clientApp.JslmIncidents
 * @description
 * # JslmIncidents
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('JslmIncidents', function ($q, $http, $timeout, Config, $rootScope, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovJslmIncidentsChange';

    /**
     * Return the promise with the JSLM incidents list, from cache or the server.
     *
     * @param int id
     *   The JSLM incident
     * @param string type
     *   The type of the ID. Allowed values:
     *   "js_lm_build" - to get incidents by Build ID
     *   "js_lm_incident" - to get certain incident (Default value)
     * @param string token
     *   The JSLM Build token.
     *
     * @returns {*}
     */
    this.get = function(id, type, token) {
      type = type || 'js_lm_incident';
      var identifier = id + ':' + type;

      if (cache && cache[identifier]) {
        return $q.when(cache[identifier].data);
      }

      return getDataFromBackend(id, type, token);
    };

    /**
     * Return JSLM incidents array from the server.
     *
     * @param int id
     *   The build ID.
     * @param string type
     *   The type of the ID. Allowed values:
     *   "js_lm_build" - to get incidents by Build ID
     *   "js_lm_incident" - to get certain incident (Default value)
     * @param string token
     *   The JSLM Build token.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(id, type, token) {
      var deferred = $q.defer();
      var url = Config.backend + '/api/js-lm-incidents';

      var params = {};

      if (type == 'js_lm_incident') {
        if (id) {
          url += '/' + id;
        }
      }
      else {
        // The ID is the JSLM build.
        params = {'filter[build]': id};
      }
      // Add build token to the request.
      url += '?token=' + token;
      // Sort desc.
      params.sort = '-id';

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        var identifier = id + ':js_lm_incident';
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
