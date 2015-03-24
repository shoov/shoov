'use strict';

/**
 * @ngdoc service
 * @name clientApp.Zip
 * @description
 * # Zip
 * Service in the clientApp.
 */
angular.module('clientApp')
  .service('Zip', function zip($q) {

    /**
     * Fetch the content, add it to the JSZip object
     * and use a jQuery deferred to hold the result.
     * @param {String} url the url of the content to fetch.
     * @param {String} filename the filename to use in the JSZip object.
     * @param {JSZip} zip the JSZip instance.
     * @return {jQuery.Deferred} the deferred containing the data.
     */
    function deferredAddZip(url, filename, zip) {
      var deferred = $q.defer();
      JSZipUtils.getBinaryContent(url, function (err, data) {
        if(err) {
          deferred.reject(err);
        }
        else {
          zip.file(filename, data, {binary:true});
          deferred.resolve(data);
        }
      });
      return deferred.promise;
    };

    this.createZip = function createZip(data) {
      var zip = new JSZip();
      var deferreds = [];

      data.forEach(function (obj) {
        deferreds.push(deferredAddZip(obj.url, obj.filename, zip));
      });

      $q.all(deferreds).then(function () {
        var blob = zip.generate({type:"blob"});
        saveAs(blob, 'shoov.zip');
      });
    };

  });
