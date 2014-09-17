<?php

/**
 * Unsubscibe using the url params like:
 * http://dev.wordpress/?unsubscribe=jeff@123.com&list=newbies&hash=77649117 (for a specific list)
 * http://dev.wordpress/?unsubscribe=jeff@123.com&hash=77649117 (to remove from all lists)
 */
class Newsletter_campaign_unsubscribe {

    public function __construct() {
        // Hook add_query_vars function into query_vars
        add_filter( 'query_vars', array($this, 'add_query_vars_filter') );
        add_action( 'init', array($this, 'process_unsubscription'), 35 );
    }


    /**
     * Goes through each record deleting it
     * @param  arr $records The array of posts to delete
     * @return arr          An array of bools - the success or failure of each deletion
     */
    private function delete_user($records) {
        // Set up an array to store any failures
        $failure = array();

        foreach ($records as $record) {
            $deleted_user = wp_delete_post($record->ID);

            // Record whether deletion failed or not
            if ($deleted_user) {
                $failure[] = 'no';
            } else {
                $failure[] = 'yes';
            }
        }

        return $failure;
    }


    /**
     * Ensure that the hash sent through with the link matches that which is held on the server
     * @param  arr $records The array of posts
     * @param  str $hash    Hash to check against db
     * @return arr          Array of posts - any that don't match the stored hash will be removed
     */
    private function check_user_hash($records, $hash) {
        // If more than one record is being checked, only one hash has to match
        $match = false;

        foreach ($records as $record) {
            $stored_hash = get_post_meta($record->ID, '_subscriber_hash', true);
            if ($stored_hash === $hash) {
                $match = true;
            }
        }

        if ($match === true) {
            return $records;
        } else {
            return false;
        }

    }


    /**
     * Retrieve and return the unsubscribe details from GET
     * @return arr
     */
    private function get_raw_unsubscribe_details() {
        $unsubscribe_details = array();
        $unsubscribe_details['address'] = trim($_GET['unsubscribe']);
        $unsubscribe_details['list'] = isset($_GET['list']) && $_GET['list'] !== '' ? trim($_GET['list']) : 'nc_all';
        $unsubscribe_details['hash'] = trim($_GET['hash']);

        // Return false unless they're all set
        if (!empty($unsubscribe_details['address']) && !empty($unsubscribe_details['list']) && !empty($unsubscribe_details['hash'])) {
            return $unsubscribe_details;
        } else {
            return false;
        }
    }


    /**
     * Retrieve posts for supplied user
     * @param  str $email_address   Email address to search for
     * @return arr                  An array of post objects
     */
    private function get_user_records($email_address) {
        $subscriber_args = array(
            'posts_per_page'    => -1, // Get all occurances in case the user is register more than once
            'post_type'         => 'subscriber'
        );

        $user_records = get_posts($subscriber_args);

        // Remove records that do not have the title $email_address
        $i = 0;
        foreach ($user_records as $user_record) {

            if ($user_record->post_title != $email_address) {
                unset($user_records[$i]);
            }

            $i++;
        }

        // Normalize the indicies after removal
        $user_records = array_values($user_records);

        return $user_records;
    }


    /**
     * If a specific list has been passed, only get the user from that list
     * @param  arr $user_records    All the records for that email
     * @param  str $subscriber_list The name of the subscriber list
     * @return arr
     */
    private function check_list($user_records, $subscriber_list) {
        if ($subscriber_list !== 'nc_all') {
            for ($i = 0; $i < count($user_records); $i++) {
                if (!has_term($subscriber_list, 'subscriber_list', $user_records[$i]->ID)) {
                    unset($user_records[$i]);
                }
            }
        }

        return $user_records;
    }


    /**
     * Processes the unsubscription
     */
    public function process_unsubscription() {
        $raw_unsubscribe_details = $this->get_raw_unsubscribe_details();

        if (!$raw_unsubscribe_details) {
            // Don't do anything if any of address, list or hash are not set
            return false;
        }

        $address = $raw_unsubscribe_details['address'];
        $list = $raw_unsubscribe_details['list'];
        $hash = $raw_unsubscribe_details['hash'];

        // Get the user records
        $records = $this->get_user_records($address);
        if (empty($records)) {
            // Don't go any further if no matches have been found
            return false;
        }

        $verified_records = $this->check_user_hash($records, $hash);

        if (!$verified_records) {
            // Don't go any further if no hashes were matched
            return false;
        }

        // If a specific list is chosen then remove anything not in that list
        $list_checked_records = $this->check_list($verified_records, $list);

        // Go ahead and delete the user
        $deletion_results = $this->delete_user($list_checked_records);

        // Fetch the saved unsubscribe page
        $unsubscribe_page = get_option('nc_settings')['nc_unsubscribe'];

        // If anything was successfully deleted proceed to send user to the unsubscribed page
        if (in_array('no', $deletion_results)) {
           wp_redirect(get_permalink($unsubscribe_page));
           exit();
        }
    }


    /**
     * Allow the url to accept the parameter to unsubscribe, list and a hash
     * @param arr $vars
     */
    public function add_query_vars_filter($vars) {
        $vars[] = 'unsubscribe';
        $vars[] = 'list';
        $vars[] = 'hash';
        return $vars;
    }
}

new Newsletter_campaign_unsubscribe;