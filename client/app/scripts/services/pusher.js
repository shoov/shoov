'use strict';

/**
 * @ngdoc service
 * @name clientApp.account
 * @description
 * # account
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('channelManager', function ($q, $http, $timeout, $pusher, $log, Config, $rootScope, Auth) {

    var channels = {};

    // A private cache key.
    var cache = {};

    this.pusher = null;

    // Update event broadcast name.
    var broadcastUpdateEventName = 'ShoovNewPusherObject';

    /**
     * Get all pusher channels.
     *
     * @returns {{}}
     *  Return list of existing channels.
     */
    this.getChannels =  function () {
      return channels;
    };

    /**
     * Get repository channel.
     *
     * @param repoId
     *  Repository id.
     *
     * @returns {*}
     *  Return repository channel.
     */
    this.getChannel =  function (repoId) {
      return (repoId in channels) ? channels[repoId] : null;
    };

    /**
     * Add new repository chanel.
     *
     * @param repoId
     *  Repository id.
     *
     * @returns {*}
     *  Return new repository channel.
     */
    this.addChannel = function (repoId) {
      if (!!channels[repoId]) {
        // Already subscribed to channel.
        return;
      }
      var pusher = $pusher(this.getClient());
      channels[repoId] = pusher.subscribe('private-repo-' + repoId);
      return channels[repoId];
    };

    /**
     * Add new user's chanel.
     *
     * @param userId
     *  User id.
     *
     * @returns {*}
     *  Return new user's channel.
     */
    this.addUserChannel = function (userId) {
      if (!!channels['uid' + userId]) {
        // Already subscribed to channel.
        return;
      }
      var pusher = $pusher(this.getClient());
      channels['uid' + userId] = pusher.subscribe('private-uid-' + userId);
      return channels['uid' + userId];
    };

    this.getClient = function() {
      return pusher ? pusher : createNewPusher();
    };

    function createNewPusher() {
      var pusherConf = {
        authEndpoint: Config.backend + '/api/v1.0/pusher_auth',
        auth: {
          headers: {
            "access-token": Auth.getAccessToken()
          }
        }
      };
      this.pusher = new Pusher(Config.pusherKey, pusherConf);
      return this.pusher;
    };

  });
