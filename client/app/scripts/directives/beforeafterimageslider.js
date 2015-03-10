'use strict';

/**
 * @ngdoc directive
 * @name clientApp.directive:beforeAfterImageSlider
 * @description
 * # beforeAfterImageSlider
 */
angular.module('clientApp')
  .directive('beforeAfterImageSlider', function () {
    return {
      scope: {
        first: '@',
        second: '@',
        width: '@',
        height: '@'
      },
      template:
        '<div class="before-after-slider">' +
            '<div class="first-wrapper">' +
              '<img ng-src="{{ first }}" width="{{ width }}" height="{{ height }}" alt="first" />' +
            '</div>' +
          '<div class="second-wrapper">' +
            '<img ng-src="{{ second }}" width="{{ width }}" height="{{ height }}"  alt="second" />' +
          '</div>' +
        '</div>',
      link: function postLink(scope, element, attrs) {
        var $second_wrapper = element.find('.second-wrapper'),
          img_width = element.find('.second-wrapper img').width(),
          init_split = Math.round(img_width/2);

        $second_wrapper.width(init_split);

        element.find('.before-after-slider').mousemove(function(e){
          var offX  = (e.offsetX || e.clientX - $second_wrapper.offset().left);
          $second_wrapper.width(offX);
        });
      }
    };
  });
