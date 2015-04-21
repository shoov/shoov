'use strict';

/**
 * @ngdoc service
 * @name clientApp.events
 * @description
 * # events
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Builds', function ($q, $http, $timeout, Config, Repos, $rootScope, $log) {

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

    /**
     * Create a build.
     *
     * @param params
     *   Object with the build data.
     */
    this.create = function(params) {
      var url = Config.backend + '/api/ci-builds';
      return $http({
        method: 'POST',
        url: url,
        params: params
      });

    };

    /**
     * Due to the way Shoov handles access (Using Drupal's Organic groups
     * module), we must create a repository before being able to create a CI
     * build.
     *
     * @param repo
     *
     * @returns {*}
     */
    this.enable = function(repo) {
      $log.log(repo);

      var params = {};

      if (!repo.shoov_id) {
        Repos.create(repo.label)
          .then(function(response) {
            $log.log(response);
          });
      }
      else if (!repo.build) {
        // Existing repo, but no existing build.
        params = {
          label: repo.label,
          branch: repo.branch,
          repository: repo.shoov_id
        };

        return this.create(params);
      }

      // We just need to enable the build and set the branch.
      params = {
        enabled: false,
        branch: repo.branch
      };

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
