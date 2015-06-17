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
        '<div><span>baseline</span><span>regression</span></div>' +
        '<div class="before-after-slider" style="height: {{ height }}px; width: {{ width }}px">' +
          '<div class="first-wrapper baseline">' +
            '<img ng-src="{{ first }}" alt="first" />' +
          '</div>' +
          '<div class="second-wrapper regression">' +
            '<img ng-src="{{ second }}" alt="second" />' +
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
