(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.navigation = {
    attach: function (context, settings) {
      BigNoise.initializeNavigation(context);
    }
  };
})(jQuery, Drupal);
