(function ( $ ) {
    /**
     * File is called only when on edit tags under subscriber post type
     */

    var $topLevel = $('.toplevel_page_newsletter-campaign');
    $topLevel.removeClass('wp-not-current-submenu')
        .addClass('wp-has-current-submenu wp-menu-open')
        .find('li').has('a[href*="edit-tags.php"]')
        .addClass('current');


}(jQuery));