<?php

/**
 * Handle the ´view in browser´ link:
 * [home_url]?viewinbrowser=15?&hash=863572f1f7a9e4efe72303d8fcc786b4
 */
class Newsletter_campaign_browser_view {

    public function __construct() {
        // Hook add_query_vars function into query_vars
        add_filter( 'query_vars', array($this, 'add_query_vars_filter') );
        add_action( 'init', array($this, 'process_browser_view'), 35 );

    }


    /**
     * Get the details of the user from the params passed in the url
     * @param  int $post_id The post id of the user
     * @return arr
     */
    private function get_subscriber_details($post_id) {
        $subscriber_post = get_post($post_id);
        $subscriber_details['email'] = $subscriber_post->post_title;
        $subscriber_details['name'] = get_post_meta( $post_id, '_subscriber_name', true );
        $subscriber_details['extra'] = get_post_meta( $post_id, '_subscriber_extra', true );
        $subscriber_details['hash'] = get_post_meta( $post_id, '_subscriber_hash', true );

        return $subscriber_details;
    }


    /**
     * Fetch the parameters passed in the url
     * @return arr/bool     An array of values or false
     */
    private function get_raw_browser_view_details() {
        $browser_view_details = array();
        $browser_view_details['uid'] = trim($_GET['viewinbrowser']);
        $browser_view_details['hash'] = trim($_GET['hash']);

        // Return false unless they're all set
        if (!empty($browser_view_details['uid']) && !empty($browser_view_details['hash'])) {
            return $browser_view_details;
        } else {
            return false;
        }
    }


    /**
     * The main functionality that fires when the ´view in browser´ url is used
     */
    public function process_browser_view() {
        // Get the parameters passed in the url
        $browser_view_details = $this->get_raw_browser_view_details();

        // If no uid or hash, don't continue
        if (!$browser_view_details) {
            return false;
        }

        // Get message content
        $html_archive = get_option('nc_html_archive');

        foreach ($html_archive as $html_template) {
            if ($html_template['hash'] === $browser_view_details['hash']) {
                $this_template = $html_template['content'];
                break;
            }
        }

        // If template was not found, don't continue
        if (!$this_template) {
            return false;
        }

        $subscriber_details = $this->get_subscriber_details($browser_view_details['uid']);

        // If no subscriber details, don't continue
        if (!$subscriber_details) {
            return false;
        }

        // Register the shortcodes
        include_once( PLUGIN_DIR . 'includes/class-nc-shortcodes.php' );

        // Insert per-email shortcodes
        // Get the sender details and send the object to the shortcodes class
        $shortcodes = new Newsletter_campaign_shortcodes(null, null, $subscriber_details);
        $converted_message = $shortcodes->nc_do_shortcodes($this_template);

        // Output the message and exit
        echo $converted_message;
        exit;
    }


    /**
     * Allow vars as url params
     * @param arr $vars
     */
    public function add_query_vars_filter($vars) {
        $vars[] = 'viewinbrowser';
        $vars[] = 'hash';

        return $vars;
    }
}

new Newsletter_campaign_browser_view;