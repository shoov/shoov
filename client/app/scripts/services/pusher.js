'use strict';

/**
 * @ngdoc service
 * @name clientApp.account
 * @description
 * # account
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('channelManager', function ($q, $http, $timeout, Config, $pusher, $log) {

    var channel, cNum;

    this.get =  function () {
      return channel;
    };

    this.set = function (channelNum, username) {
      cNum = channelNum;
      var client = new Pusher('b2ac1e614d90c85ec13b', { auth: { params: { username: username}} });
      var pusher = $pusher(client);
      channel = pusher.subscribe('presence-collaborate-' + channelNum);
      return channel;
    };

    this.getChannelNum = function () {
      return cNum;
    };
  });
