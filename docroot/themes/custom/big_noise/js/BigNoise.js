(function ($) {
  if (!window.BigNoise) {
    window.BigNoise = {};
  };

  BigNoise.settings = {
    breakpoints: {
      mobile: {
        min: 320,
        max: 767
      },
      tablet: {
        min: 768,
        max: 1023
      },
      desktop: {
        min: 1024,
        max: 1299
      },
      desktopWide: {
        min: 1300,
        max: null
      }
    }
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

  BigNoise.secondaryNavigationToggleClickHandler = function () {
    $('#site--main--secondary-navigation-container').toggleClass('collapsed expanded');
  };

  BigNoise.initializeSidebars = function (context) {
    $('.sidebar-close', context).each(function () {
      $(this).click(BigNoise.sidebarCloseClickHandler);
    });

    // Sidebar Main Navigation Toggle
    $('#header--sidebar--main-navigation--toggle', context).click(BigNoise.sidebarMainNavigationToggleClickHandler);
  };

  BigNoise.initializeNavigation = function (context) {
    $('#site--main--secondary-navigation-toggle', context).click(function () {
      BigNoise.secondaryNavigationToggleClickHandler();
    });

    document.querySelector('style').textContent += '\n' +
      '@media (max-width: ' + this.settings.breakpoints.mobile.max + 'px) {\n' +
        '.site--main--secondary-navigation-container.theme-collapsible-layout-mobile.expanded {\n' +
          'height: ' + ($('#site--main--secondary-navigation-container').height() + $('#navigation--secondary-navigation-main').height()) + 'px;\n' +
      '}\n';
  };
})(jQuery);
