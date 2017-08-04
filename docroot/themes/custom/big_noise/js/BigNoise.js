(function ($) {
  if (!window.BigNoise) {
    window.BigNoise = {};
  };

  BigNoise.activateSidebar = function (sidebarId) {
    var sidebarElement = $('#' + sidebarId);

    if (!sidebarElement.length) {
      return;
    }

    sidebarElement.addClass('active');
    $('html, body').addClass('sidebar-active');
  };

  BigNoise.dismissSidebar = function (sidebarId) {
    var sidebarElement = $('#' + sidebarId);

    if (!sidebarElement.length) {
      return;
    }

    sidebarElement.removeClass('active');
    $('html, body').removeClass('sidebar-active');
  };

  BigNoise.sidebarCloseClickHandler = function () {
    BigNoise.dismissSidebar($(this).closest('.sidebar').attr('id'));
  };

  BigNoise.sidebarMainNavigationToggleClickHandler = function () {
    BigNoise.activateSidebar('sidebar--main-navigation');
  };

  BigNoise.initializeSidebars = function (context) {
    $('.sidebar-close', context).each(function () {
      $(this).click(BigNoise.sidebarCloseClickHandler);
    });

    // Sidebar Main Navigation Toggle
    $('#header--sidebar--main-navigation--toggle', context).click(BigNoise.sidebarMainNavigationToggleClickHandler);
  };
})(jQuery);
