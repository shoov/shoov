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
      return $http.post(Config.backend + '/api/ci-builds', params);
    };

    /**
     * Due to the way Shoov handles access (Using Drupal's Organic groups
     * module), we must create a repository before being able to create a CI
     * build.
     *
     * @param githubRepo
     *
     * @returns {*}
     */
    this.enable = function(githubRepo) {
      var params = {};
      var self = this;

      if (!parseInt(githubRepo.shoov_id)) {
        return Repos.create(githubRepo.label)
          .then(function(response) {
            var repo = response.data.data[0];

            params = {
              label: repo.label,
              branch: githubRepo.branch,
              repository: repo.id
            };

            return self.create(params);
          });
      }
      else if (githubRepo.build && !githubRepo.build.id) {
        // Existing repo, but no existing build.
        params = {
          label: githubRepo.label,
          branch: githubRepo.branch,
          repository: githubRepo.shoov_id
        };

        return this.create(params);
      }

      // We just need to enable the build and set the branch.
      params = {
        enabled: true,
        branch: githubRepo.branch
      };

      return $http.patch(Config.backend + '/api/ci-builds/' + githubRepo.build.id, params);
    };

    this.disable = function(githubRepo) {
      $log.log(githubRepo);
      if (!githubRepo.build || !githubRepo.build.enabled) {
        // Build doesn't exist, or is already disabled.
        return;
      }

      return $http.patch(Config.backend + '/api/ci-builds/' + githubRepo.build.id, {enabled: false});
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
