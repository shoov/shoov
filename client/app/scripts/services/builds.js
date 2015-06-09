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
     * @param int id
     *   The repository or CI build item ID.
     * @param string type
     *   The type of the ID Allowed values are "ci_build" and "ui_build".
     *   Defaults to "ui_build".
     *
     * @returns {*}
     */
    this.get = function(id, type) {
      type = type || 'ui_build';
      var identifier = id + ':' + type;

      if (cache && cache[identifier]) {
        return $q.when(cache[identifier].data);
      }

      return getDataFromBackend(id, type);
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
      if (!githubRepo.build || !githubRepo.build.enabled) {
        // Build doesn't exist, or is already disabled.
        return;
      }

      return $http.patch(Config.backend + '/api/ci-builds/' + githubRepo.build.id, {enabled: false});
    };


    /**
     * Return builds array from the server.
     *
     * @param int id
     *   The repository or CI build item ID.
     * @param string type
     *   The type of the ID Allowed values are "ci_build" and "ui_build".
     *   Defaults to "ui_build".
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend(id, type) {
      var deferred = $q.defer();
      var resource = type == 'ui_build' ? 'builds' : 'ci-builds';

      var url = Config.backend + '/api/' + resource;

      if (id) {
        url += '/' + id;
      }

      var params = {};
      if (type == 'ui_build') {
        params.sort = '-id';
      }

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
        cache[cacheId] = null;
      }, 5000);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
