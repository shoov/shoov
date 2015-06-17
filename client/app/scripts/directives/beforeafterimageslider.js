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
        baselineImgSrc: '@',
        regressionImgSrc: '@',
        maxWidth: '@',
        maxHeight: '@'
      },
      template:
        '<div class="labels" style="width: {{ maxWidth }}px">' +
          '<div class="baseline text-muted">baseline image</div>' +
          '<div class="regression text-muted">regression image</div>' +
        '</div>' +
        '<div class="before-after-slider" style="height: {{ maxHeight }}px; width: {{ maxWidth }}px">' +
          '<div class="regression-wrapper">' +
            '<img ng-src="{{ regressionImgSrc }}" alt="regression image" />' +
          '</div>' +
          '<div class="baseline-wrapper">' +
            '<img ng-src="{{ baselineImgSrc }}" alt="baseline image" />' +
          '</div>' +
        '</div>',
      link: function postLink(scope, element, attrs) {
        var $baseline_wrapper = element.find('.baseline-wrapper'),
          init_split = Math.round(scope.maxWidth/2);

        $baseline_wrapper.width(init_split);
        
        element.find('.before-after-slider').mousemove(function(e) {
          var offX  = (e.offsetX || e.clientX - $baseline_wrapper.offset().left);
          console.log(offX);
          if (offX <= 0 || offX >= scope.maxWidth) {
            return;
          }
          $baseline_wrapper.width(offX);
        });
      }
    };
  });
