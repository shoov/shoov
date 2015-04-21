'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Builds', function ($q, $http, $timeout, Config, $rootScope, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovBuildChange';


    /**
     * Return the promise with the events list, from cache or the server.
     *
     * @param int companyId
     *   The company ID.
     *
     * @returns {*}
     */
    this.get = function(buildId) {
      if (cache && cache[buildId]) {
        return $q.when(cache[buildId].data);
      }

      return getDataFromBackend(buildId);
    };

    this.enable = function(repo) {

      var url = Config.backend + '/api/ci-builds';
      var params = {};

      if (!repo.build) {
        // We need to create a build.
        params = {
          label: repo.label,
          branch: repo.branch
        };

        // We need to create a repo.
        if (!repo.shoov_id) {
          params.repository = {
            label: repo.label
          };

          return $http({
            method: 'POST',
            url: url,
            params: params
          });
        }
      }

      // We just need to enable the build and set the branch.
      params = {enabled: false};

      return $http({
        method: 'PATCH',
        url: url,
        params: params
      });
    };

    this.disable = function(repo) {
      if (!repo.build || repo.build.enabled) {
        // Build dosen't exist, or is already disabled.
        return;
      }

      var url = Config.backend + '/api/ci-builds';
      var params = {enabled: false};

      return $http({
        method: 'PATCH',
        url: url,
        params: params
      });
    };


    /**
     * Return builds array from the server.
     *
     * @param int buildId
     *   The build ID.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(buildId) {
      var deferred = $q.defer();
      var url = Config.backend + '/api/builds';

      if (buildId) {
        url += '/' + buildId;
      }

      var params = {
        sort: '-id'
      };

      $http({
        method: 'GET',
        url: url,
        params: params
      }).success(function(response) {
        setCache(buildId, response.data);
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
      }, 5);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
