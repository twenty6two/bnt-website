(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.sidebar = {
    attach: function (context, settings) {
      BigNoise.initializeSidebars(context);
    }
  };
})(jQuery, Drupal);
