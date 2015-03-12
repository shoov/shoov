var WebdriverIO = require('webdriverio');
var WebdriverCSS = require('webdrivercss');
var R = require('ramda');
var request = require('request');
var fs = require('fs');
var path = require('path');
var git = require('git-rev');

var testsFail = 0;

var gitCommit;
var gitBranch;

git.long(function (str) {
  gitCommit = str;
});

git.branch(function (str) {
   gitBranch = str;
});

/**
 * Upload the image.
 *
 * @param obj
 */
var uploadFailedImage = function(obj) {
  ++testsFail;

  var options = {
    url: process.env.BOOM_BACKEND_URL + '/api/screenshots-upload',
    headers: {
      'access-token': process.env.BOOM_ACCESS_TOKEN
    }
  };

  var req = request.post(options, function (err, res, body) {
    if (err) {
      console.log('Error!');
    }
    else {
      var data = JSON.parse(body);
      console.log('-- Regression images uploaded to ' + data.data[0].self);
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
};

var isNotWithinMisMatchTolerance = R.filter(R.where({isWithinMisMatchTolerance: false}));
var uploadImages = R.mapObj(R.forEach(uploadFailedImage));
var checkImages = R.compose(uploadImages, R.mapObj(isNotWithinMisMatchTolerance));

var processResults = function(err, res) {
  checkImages(res);
};

describe('UI regression tests', function() {

  this.timeout(99999999);
  var client = {};

  var caps;
  caps = {
    'browser' : 'Chrome',
    'browser_version' : '39.0',
    'os' : 'OS X',
    'os_version' : 'Yosemite',
    'project' : 'Boom',
    'resolution': '1024x768'
  };

  before(function(done){

    if (process.env.BROWSERSTACK_USERNAME) {
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
    }

    client
      .init(done)
      .setViewportSize({
        width: 1024,
        height: 768
      });
    WebdriverCSS.init(client);
  });

  it('Google test',function(done) {
    client
      .url('https://www.google.com/?gfe_rd=cr&ei=tMH8VONqy4fxB5rygZgD&gws_rd=ssl,cr&fg=1')
      .webdrivercss('chrome', {name: 'google-homepage'}, processResults)
      .call(done);
  });

  it('Personal site test',function(done) {
    client
      .url('http://amitaibu.com')
      .webdrivercss('chrome', {name: 'amitaibu-homepage'}, processResults)
      .call(done);
  });

  after(function(done) {

    // @todo: Replace this ugly hack with a proper promise, that will be invoked
    // when all requests have finished.
    setTimeout(function() {

        if (testsFail) {
          client.end(done,function() {
            console.log(process.env.BOOM_CLIENT_URL + '/#/screenshots/' + gitCommit);
            throw new Error(testsFail + ' test(s) failed.');
          });
        }
        else {
          client.end(done);
        }
      }

      , 5000);
  });
});
