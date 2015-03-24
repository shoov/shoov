'use strict';

var Promise = require('bluebird');
var WebdriverIO = require('webdriverio');
var WebdriverCSS = require('webdrivercss');

// @todo: Move to an NPM module.
var wdcssSetup = require('../wdcss_setup');

var url = 'http://localhost:9000';
var testName = 'chrome';

describe('Homepage tests', function() {

  this.timeout(99999999);
  var client = {};

  before(function(done){
    client = wdcssSetup.before(done);
  });

  it('should show the homepage',function(done) {
    client
      .url(url + '/#/login')
      .webdrivercss(testName, {
        name: 'homepage'
      }, wdcssSetup.processResults)
      .moveToObject('#submit')
      .webdrivercss(testName, {
        name: 'homepage-hover'
      }, wdcssSetup.processResults).
      call(done);

  });

  after(function(done) {
    wdcssSetup.after(done);

  });
});