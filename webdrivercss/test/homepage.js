'use strict';

var Promise = require('bluebird');
var WebdriverIO = require('webdriverio');
var WebdriverCSS = require('webdrivercss');

var shoovWebdrivercss = require('shoov-webdrivercss');

var url = 'http://localhost:9000';
var testName = 'chrome';

describe('Homepage tests', function() {

  this.timeout(99999999);
  var client = {};

  before(function(done){
    client = shoovWebdrivercss.before(done);
  });

  it('should show the homepage',function(done) {
    client
      .url(url + '/#/login')
      .webdrivercss(testName, {
        name: 'homepage'
      }, shoovWebdrivercss.processResults)
      .moveToObject('.btn-github')
      .webdrivercss(testName, {
        name: 'homepage-hover'
      }, shoovWebdrivercss.processResults).
      call(done);

  });

  after(function(done) {
    shoovWebdrivercss.after(done);

  });
});

