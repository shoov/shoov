'use strict';

/**
 * @ngdoc service
 * @name clientApp.account
 * @description
 * # account
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Account', function ($q, $http, $timeout, Config, $rootScope, channelManager, $log) {

    // A private cache key.
    var cache = {};

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovAccountChange';

    /**
     * Return the promise with the events list, from cache or the server.
     *
     * @returns {*}
     */
    this.get = function() {
      return $q.when(cache.data || getDataFromBackend());
    };

    /**
     * Return events array from the server.
     *
     * @returns {$q.promise}
     */
    function getDataFromBackend() {
      var deferred = $q.defer();
      var url = Config.backend + '/api/me/';

      $http({
        method: 'GET',
        url: url
      }).success(function(response) {
        var data = response.data[0];
        setCache(data);
        setUserChannel(data.id);
        // Subscribe user to Pusher channel.
        setChannels(data.repository);
        deferred.resolve(data);
      });

      return deferred.promise;
    }

    /**
     * Cache the account data.
     *
     * @param data
     *   The data to cache.
     */
    var setCache = function(data) {
      // Cache data.
      cache = {
        data: data,
        timestamp: new Date()
      };

      // Clear cache in 60 seconds.
      $timeout(function() {
        cache = {};
      }, 60000);

      // Broadcast a change event.
      $rootScope.$broadcast(broadcastUpdateEventName);
    };

    /**
     * Subscribe user to Pusher channels.
     *
     * @param array repositories
     *   The user repositories.
     */
    var setChannels = function(repositories) {
      if(!repositories) {
        // User doesn't have repositories yet.
        return;
      }

      repositories.forEach(function(repoId) {
        if (!repoId) {
          // repoId is null.
          return;
        }
        channelManager.addChannel(repoId);
      });
    };

    /**
     * Subscribe user to user's Pusher channel.
     *
     * @param int userId
     *   The user id.
     */
    var setUserChannel = function(userId) {
      channelManager.addUserChannel(userId);
    };

    $rootScope.$on('clearCache', function() {
      cache = {};
    });

  });
