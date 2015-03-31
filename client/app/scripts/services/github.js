'use strict';

/**
 * @ngdoc service
 * @name clientApp.Github
 * @description
 * # Github
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Github', function ($http, Config) {

    /**
     * Create a Pull request.
     *
     * This sets the values in the Build in Shoov.
     *
     * @param buildId
     *   The Build ID.
     */
    this.createPullRequest = function(buildId, screenshotIds, newBranch) {
      var ids = [];
      screenshotIds.forEach(function (obj) {
        ids.push(obj.id);
      });

      return $http({
        method: 'PATCH',
        url: Config.backend + '/api/builds/' + buildId,
        data: {
          pull_request_screenshot_ids: ids.join(','),
          pull_request_branch_name: newBranch,
          // @todo: Be smarter about this, and auto set this.
          pull_request_status: 'requested'
        }
      });
    };
  });
