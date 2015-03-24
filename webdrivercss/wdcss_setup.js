'use strict';

var Promise = require('bluebird');

var assert = require('assert');
var exec   = require('child_process').exec;
var fs = Promise.promisifyAll(require('fs'));
var git = require('git-rev');
var nconf = require('nconf');
var open = require('open');
var path = require('path');
var R = require('ramda');
var request = require('request-promise');
var WebdriverCSS = require('webdrivercss');
var WebdriverIO = require('webdriverio');


var uploads = [];

var client = {};

var gitCommit;
var gitBranch;

git.long(function (str) {
  gitCommit = str;
});

git.branch(function (str) {
  gitBranch = str;
});

// Get the directory to to the root level. We don't use any nodejs wrapper, as
// we haven't found a good one that does git rev-parse.

var gitPrefix = new Promise(function(resolve, reject) {
  exec('git rev-parse --show-prefix', function(err, stdout) {
    if(err) {
      reject(err);
    }
    else {
      resolve(stdout.replace('\n', ''));
    }
  });
});

var getRepoName = new Promise(function(resolve, reject) {
  exec('git config --get remote.origin.url', function(err, stdout) {
    if(err) {
      reject(err);
    }
    else {
      // @todo: Be more careful with the assumptions about the URL
      var output = stdout
        .replace('\n', '')
        .replace('git@github.com:', '')
        .replace('https://github.com/', '')
        .replace('.git', '');

      resolve(output);
    }
  });
});

/**
 * Get config from file or environment.
 *
 * JSON file is in ~/.shoov.json
 *
 * @param str
 *   The config name.
 * @param defaultValue
 *   The default value.
 *
 * @returns {*}
 */
var getConfig = function(str, defaultValue) {
  // Set config hierarchy.
  var configFile = process.env[(process.platform == 'win32') ? 'USERPROFILE' : 'HOME'] + '/.shoov.json';
  nconf
    .env()
    .file(configFile);

  var upperCase = 'SHOOV_' + str.toUpperCase();
  var confValue = nconf.get(str) || nconf.get(upperCase);
  return confValue || defaultValue;
};

/**
 * Upload the image.
 *
 * @param obj
 */
var uploadFailedImage = function(obj) {
  var accessToken = getConfig('access_token');
  if (!accessToken) {
    throw new Error('The Shoov access token is not defined, visit your account page.');
  }

  var backendUrl = getConfig('backend_url', 'https://dev-shoov.pantheon.io');
  var options = {
    url: backendUrl + '/api/screenshots-upload',
    headers: {
      'access-token': accessToken
    }
  };

  var gitData = [gitPrefix, getRepoName];

  Promise
    .all(gitData)
    .then(function(data) {

      var dirPrefix = data[0];
      var repoName = data[1];

      var req = request.post(options);
      req
        .on('error', function (err) {
          throw new Error(err);
        })
        .on('data', function(data) {
          if (getConfig('debug')) {
            // Show response.
            data = JSON.parse(data);
            console.log(data.data);
          }
        })
        .on('response', function(response) {
          if (response.statusCode >= 500) {
            throw new Error('Backend error');
          }
          else if (response.statusCode !== 200) {
            throw new Error('Access token is incorrect or no longer valid, visit your account page');
          }
        });


      var form = req.form();

      var label = path.basename(obj.baselinePath, '.baseline.png').replace('.', ' ');
      form.append('label', label);

      form.append('baseline', fs.createReadStream(obj.baselinePath));
      form.append('regression', fs.createReadStream(obj.regressionPath));
      form.append('diff', fs.createReadStream(obj.diffPath));

      form.append('baseline_name', obj.baselinePath);
      form.append('git_commit', gitCommit);
      form.append('git_branch', gitBranch);

      form.append('directory_prefix', dirPrefix);
      form.append('repository', repoName);

      uploads.push(req);
   });

  throw new Error('Found regression in test');
};

var isNotWithinMisMatchTolerance = R.filter(R.where({isWithinMisMatchTolerance: false}));
var uploadImages = R.mapObj(R.forEach(uploadFailedImage));
var checkImages = R.compose(uploadImages, R.mapObj(isNotWithinMisMatchTolerance));


var wdcssSetup = {

  /**
   * Init the client.
   */
  before: function(done, capsSetup) {
    client = this.getClient(done, capsSetup);
    WebdriverCSS.init(client);

    return client;
  },

  after: function(done) {
    Promise
      .all(uploads)
      .then(function() {
        if (uploads.length) {
          var clientUrl = getConfig('client_url', 'http://shoov.gizra.com');
          var regressionUrl = clientUrl + '/#/screenshots/' + gitCommit;
          console.log('See regressions in: ' + regressionUrl);

          if (getConfig('open_link')) {
            open(regressionUrl)
          }
        }

        client.end(done);
      });
  },

  processResults: function(err, res) {
    if (err) {
      console.error(err);
    }
    checkImages(res);
  },

  getUploadedRequests: function() {
    return uploads;
  },

  /**
   * Get client.
   */
  getClient : function (done, capsSetup) {
    var caps = {};

    // Determines if the view port handling should be done by the client.
    var setViewPort = false;

    if (process.env.SAUCE_USERNAME) {
      caps['browserName'] = 'chrome';
      caps['platform'] = 'Linux';
      caps['version'] = '41.0';
      caps['screenResolution'] = '1024x768';

      client = WebdriverIO.remote({
        desiredCapabilities: caps,
        host: 'ondemand.saucelabs.com',
        port: 80,
        user: process.env.SAUCE_USERNAME,
        key: process.env.SAUCE_ACCESS_KEY
      });
    }
    else if (process.env.BROWSERSTACK_USERNAME) {
      caps['browser'] = 'Chrome';
      caps['browser_version'] = '39.0';
      caps['os'] = 'OS X';
      caps['os_version'] = 'Yosemite';
      caps['resolution'] = '1024x768';
      caps['project'] = 'Shenkar';

      caps['browserstack.user'] = process.env.BROWSERSTACK_USERNAME;
      caps['browserstack.key'] = process.env.BROWSERSTACK_KEY;
      caps['browserstack.debug'] = 'true';

      client = WebdriverIO.remote({
        desiredCapabilities: caps,
        host: 'hub.browserstack.com',
        port: 80
      });
    }
    else {
      client = WebdriverIO.remote({ desiredCapabilities: {browserName: 'phantomjs'} });
      setViewPort = true;
    }

    // Init the client.
    client.init(done);
    if (setViewPort) {
      client.setViewportSize({
        width: 1024,
        height: 768
      });
    }

    return client;
  }
};

module.exports = wdcssSetup;