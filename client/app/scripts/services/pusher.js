'use strict';

/**
 * @ngdoc service
 * @name clientApp.account
 * @description
 * # account
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('channelManager', function ($q, $http, $timeout, $pusher, $log, Config, Auth) {

    var channels = {};

    this.getChannels =  function () {
      return channels;
    };

    this.getChannel =  function (repoId) {
      return (repoId in channels) ? channels[repoId] : null;
    };

    this.addChannel = function (repoId) {
      if (!!channels[repoId]) {
        // Already subscribed to channel.
        return;
      }
      var pusher = $pusher(this.getClient());
      channels[repoId] = pusher.subscribe('private-repo-' + repoId);
      return channels[repoId];
    };

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
      var pusherConf = {
        authEndpoint: Config.backend + '/api/v1.0/pusher_auth',
        auth: {
          headers: {
            "access-token": Auth.getAccessToken()
          }
        }
      };
      return new Pusher(Config.pusherKey, pusherConf);
    }
  });
