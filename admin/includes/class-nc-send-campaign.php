<?php

/**
 * Sends the selected campaign
 */

class Newsletter_campaign_send_campaign {
    public function __construct() {
        add_action( 'wp_ajax_my_action', array( $this, 'my_action_callback' ) );
        add_action('init', array($this, 'send_campaign'), 30 );
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

    private function get_addresses($id) {
        $subscriber_groups = array();

        // Get the ids of all selected subscriber groups
        $subscriber_groups_meta = get_post_meta( $id, '_campaign_subscriber-group-check', true );
        $subscriber_groups_ids = array();
        // Flatten the array to create an array of the values
        array_walk_recursive($subscriber_groups_meta, function ($current) use (&$subscriber_groups_ids) {
            $subscriber_groups_ids[] = $current;
        });

        // Fetch all the posts that belong to the selected subscriber group(s)
        $send_campaign_subscriber_posts_args = apply_filters( 'newsletter_campaign_send_campaign_subscriber_posts_args',
            array(
                'posts_per_page'    =>  -1,
                'orderby'           => 'title',
                'post_type'         => 'subscriber',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'subscriber_list',
                        'terms' => $subscriber_groups_ids
                    )
                )
            )
        );

        $subscriber_posts = get_posts($send_campaign_subscriber_posts_args);

        // Create an array to store just the email addresses
        $subscriber_emails_valid = array();

        // Create an array to store duplicates
        $subscriber_emails_duplicate = array();

        // Create an array to store broken email addresses
        $subscriber_emails_invalid = array();

        // Loop through the posts to generate an array of emails
        foreach ($subscriber_posts as $subscriber) {

            // Perform a check to make sure the address has not already been added
            if (!in_array($subscriber->post_title, $subscriber_emails_valid)) {

                // Check that it is a valid email address
                if (is_email($subscriber->post_title)) {

                    // Everything is fine with address, add it to array
                    $subscriber_emails_valid[] = $subscriber->post_title;

                } else { // Not valid
                    $subscriber_emails_invalid[]['address'] = $subscriber->post_title;
                    $subscriber_emails_invalid[]['id'] = $subscriber->ID;
                }

            } else { // A duplicate
                $subscriber_emails_duplicate[]['address'] = $subscriber->post_title;
                $subscriber_emails_duplicate[]['id'] = $subscriber->ID;
            }
        }

        // Group all email types together into an array to return
        $subcriber_emails_return = array();

        if (isset($subscriber_emails_valid) && !empty($subscriber_emails_valid)) {
            $subcriber_emails_return['valid'] = $subscriber_emails_valid;
        }

        if (isset($subscriber_emails_invalid) && !empty($subscriber_emails_invalid)) {
            $subcriber_emails_return['invalid'] = $subscriber_emails_invalid;
        }

        if (isset($subscriber_emails_duplicate) && !empty($subscriber_emails_duplicate)) {
            $subcriber_emails_return['duplicate'] = $subscriber_emails_duplicate;
        }

        return $subcriber_emails_return;
    }


    /*
     * Show admin message
     */
    public function show_admin_notice() {
        ?>
        <div class="updated">
            <p><?php _e( 'Messages sent!', 'newsletter-campaign' ); ?></p>
        </div>
        <?php
    }


    /*
     * Send email
     * $addresses: array of email addresses
     */

    private function send_email($addresses) {
        // TODO: set to html email then back to text after

        // Set up an array to store any failed sends
        $mail_failed = array();

        // Have to send individually as we don't want the recipients seeing other addresses
        foreach ($addresses as $address) {
            $mail_sent = wp_mail( $address, $subject = 'Test subject', $message = 'Test message', $headers = 'From: My Name <myname@example.com>' . "\r\n" );

            // Add any failed sends to the mail_failed array
            if (!$mail_sent) {
                $mail_failed[] = $address;
            }
        }

        return $mail_failed;
    }


    /*
     * The functionality of the send
     */

    public function send_campaign() {
        if (!isset($_POST['nc-campaign__confirmation-true'])) {
            return;
        }

        $addresses = $this->get_addresses($_POST['post_ID']);

        if (isset($addresses['valid']) && !empty($addresses['valid'])) {
            //$mail_sent = $this->send_email($addresses['valid']);
        }
        add_action('admin_notices', array($this, 'show_admin_notice') );

    }

}

new Newsletter_campaign_send_campaign;