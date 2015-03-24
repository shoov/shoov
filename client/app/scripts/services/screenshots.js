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
    this.get = function(companyId, testId) {
      var cacheId = companyId + ':' + testId;
      if (cache && cache[cacheId]) {
        return $q.when(cache[cacheId].data);
      }

      return getDataFromBackend(companyId, testId);
    };

    /**
     * Return a promise authors list.
     *
     * @param int companyId
     *   The company ID.
     *
     * @returns {*}
     */
    this.getAuthors = function(companyId) {
      var deferred = $q.defer();
      var authors = {};
      this.get(companyId).then(function(events) {
        angular.forEach(events, function(event) {
          authors[event.user.id] = {
            id: parseInt(event.user.id),
            name: event.user.label,
            count: authors[event.user.id] ? ++authors[event.user.id].count : 1
          };
        });
        deferred.resolve(authors);
      });
      return deferred.promise;
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
        url: Config.backend + '/api/screenshots/' + id,
      });
    };


    /**
     * Return events array from the server.
     *
     * @param int companyId
     *   The company ID.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(companyId, testId) {
      var cacheId = companyId + ':' + testId;
      var deferred = $q.defer();
      var url = Config.backend + '/api/screenshots';
      var params = {
        sort: '-updated',
        'filter[git_commit]': testId
      };

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        setCache(cacheId, response);
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
      }, 60000);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
