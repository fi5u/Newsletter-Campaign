<?php

/**
 * Sends the selected campaign
 */

class Newsletter_campaign_send_campaign {
    public function __construct() {
        add_action( 'wp_ajax_my_action', array( $this, 'my_action_callback' ) );

        // If confirm send mail
        if (isset($_POST['nc-campaign__confirmation-true'])) {
            $this->send_campaign();
        }
    }

    public function my_action_callback() {
        global $wpdb;
        $whatever = intval( $_POST['whatever'] );
        $whatever += 10;
            echo $whatever;
        die();
    }

    /*
     * Get the email addresses
     * Returns an array of email address
     */
    private function get_addresses() {

    }


    /*
     * The functionality of the send
     */

    private function send_campaign() {
        echo 'Sending mails';
    }
}

new Newsletter_campaign_send_campaign;