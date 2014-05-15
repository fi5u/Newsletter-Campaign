<?php

/**
 * Filter admin output
 */
class Newsletter_campaign_admin_filters {

    /**
     * Constructor
     */
    public function __construct() {
        add_filter( 'enter_title_here', array(__CLASS__, 'filter_title_text'), 10 );

    }

    /**
     * Add Newsletter Campaign custom meta boxes
     */
    public static function filter_title_text($input) {
        global $post_type;

        if ( is_admin() && 'subscriber' == $post_type ) {
            return __( 'Enter Email Address', 'newsletter-campaign' );
        }

        return $input;

    }

}

new Newsletter_campaign_admin_filters;