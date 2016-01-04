/**
 * Execute the tests, and if something fails, send it to the server.
 *
 * (currently it is just logged to the console)
 */
main = function() {
  runTests();
}

runTests = function() {
  var errors = [];
  var buildId = 2;
  loadJS('http://localhost/shoov/www/js_lm/' + buildId, function() {
    result = customTests();
    result.forEach(function(row) {
      if (!!row.result()) {
        return;
      }

      errors.push(row.id);
    });

    var request = new XMLHttpRequest();

    var data = {
      build: buildId,
      // url: '/',
      errors: errors.join("\r\n")
    };

    var serializeObject = function(obj) {
        var pairs = [];
        for (var prop in obj) {
            if (!obj.hasOwnProperty(prop)) {
                continue;
            }
            pairs.push(prop + '=' + obj[prop]);
        }
        return pairs.join('&');
    }


    html2canvas(document.body, {
      onrendered: function(canvas) {
        data.image = canvas.toDataURL("image/png");

        var image = document.createElement('img');
        image.src = canvas.toDataURL("image/png");
        document.body.appendChild(image);

        console.log(image.src);


        request.open('POST', 'http://localhost/shoov/www/api/v1.0/js-lm-incidents', true);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');

        request.onload = function() {
            if (request.status === 200) {
                console.log('ok');
            }
            else if (request.status !== 200) {
                console.log('Request failed.  Returned status of ' + request.status);
            }
        };

        request.send(encodeURI(serializeObject(data)));
      }
    });
  });
}

function loadJS(src, callback) {
    var s = document.createElement('script');
    s.src = src;
    s.async = true;
    s.onreadystatechange = s.onload = function() {
        var state = s.readyState;
        if (!callback.done && (!state || /loaded|complete/.test(state))) {
            callback.done = true;
            callback();
        }
    };
    document.getElementsByTagName('head')[0].appendChild(s);
}
