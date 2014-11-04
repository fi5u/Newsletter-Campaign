<?php

class Newsletter_campaign_shortcode_btns {

    public function __construct() {
        $plugin = NewsletterCampaign::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action( 'admin_enqueue_scripts', array( $this, 'define_shortcode_btns' ), 99 );
    }


    public function define_shortcode_btns() {

        // Ensure that we're on the template screen
        $screen = get_current_screen();
        if ( 'template' !== $screen->post_type ) {
            return;
        }

        $options = get_option( 'nc_settings' );

        $subscriber_list_cat_args = apply_filters( 'newsletter_campaign_subscriber_list_cat_args', array(
                'taxonomy'  => 'subscriber_list'
            )
        );

        // Fetch the array of subscriber lists, prepending with 'all lists' option
        $subscriber_list_cats = array_merge(array(array('name' => __('All lists'), 'slug' => 'nc_all')), get_categories($subscriber_list_cat_args));

        wp_localize_script( $this->plugin_slug . '-template-script', 'buttons', nc_get_html_tags());
    }
}

new Newsletter_campaign_shortcode_btns;