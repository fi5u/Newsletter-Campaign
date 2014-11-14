<?php

/**
 * Sends the selected campaign
 */

class Newsletter_campaign_send_campaign {

    private $post_id;
    private $preview = true; // if true, on send outputs email to browser

    public function __construct() {
        add_action( 'admin_init', array($this, 'send_campaign'), 30 );
        add_action( 'admin_head', array($this, 'check_mail_sent') );
        add_action( 'admin_head', array($this, 'nc_set_post_id') );
        add_action( 'current_screen', array($this, 'check_screen') );
        add_action( 'wp_ajax_nc_save', array( $this, 'nc_save_callback' ) );
    }


    public function nc_set_post_id() {
        global $post;
        $this->post_id = $post->ID;
    }


    public function nc_save_callback() {
        global $wpdb;
        /*$whatever = intval( $_POST['whatever'] );
        $whatever += 10;
            //echo $whatever;
            //echo 'superdooper';
            //Newsletter_campaign_meta_box_generator::nc_save_meta_box( $post_id, $post_type, $field, $meta_name = '');
            echo $this->post_id;
        die();*/
    }


    /*
     * Check that we are on the correct screen
     */

    public function check_screen() {
        // If not in campaign screen, exit
        $screen = get_current_screen();

        if ( 'campaign' === $screen->post_type ) {
            return true;
        } else {
            return false;
        }
    }


    /*
     * Get all the posts for current campaign
     * Return array
     */

    private function get_posts($campaign_id) {
        $posts = get_post_meta($campaign_id, '_campaign_builder', true);
        return apply_filters( 'nc_get_posts', $posts );
    }


    /**
     * From the previous iteration through array, calculate the current iteration
     * @param  arr $template_exploded The array to check iteration against
     * @param  int $prev_iteration    The previous iteration
     * @return int
     */
    private function get_template_layout_part($template_exploded, $prev_iteration) {
        $count = count($template_exploded);

        if ($prev_iteration + 1 >= $count) {
            $cur_iteration = 0;
        } else {
            $cur_iteration = $prev_iteration + 1;
        }

        return $cur_iteration;
    }


    /**
     * Used by build_email() to feed content into the template
     * @param  arr $posts_arr   An array from get_posts()
     * @param  str $template    The template to feed content into
     * @return str              The compiled template
     */
    private function content_to_template($posts_arr, $template) {
        // Set up an array to add posts content to
        $post_output_arr = array();

        // Fetch the shortcode divider text
        $options = get_option( 'nc_settings' );
        $divider_text = $options['nc_shortcode_divider'];

        // If template is split, get the parts (i.e multilayout template)
        $template_exploded = explode('[' . $divider_text . ']', $template);

        if ($template_exploded !== array($template)) {
            $multilayout_template = true;
            // An array of template parts has been created
            // Set the template to the first part
            $template = $template_exploded[0];

            $layout_part_count = 0;
        }

        // Set an iterator to check if we're on the first pass of foreach
        $i = 0;
        // Generate the output for each post
        foreach ($posts_arr as $post_item) {

            // Get the post object so we can get the content
            $post_object = get_post($post_item);

            // Perform replacement
            $shortcodes = new Newsletter_campaign_shortcodes($post_object);

            // If mulitlayout is used and we're not on the first iteration
            if ($multilayout_template && $i !== 0) {
                // Get the correct current layout part
                $layout_part_count = $this->get_template_layout_part($template_exploded, $layout_part_count);

                // Set the template to the layout part
                $template = $template_exploded[$layout_part_count];
            }
            $post_output_arr[] = $shortcodes->nc_do_shortcodes($template);
            $i++;
        }

        // If doesn't end on a completed layout, output empty template parts
        if ($multilayout_template && $i % count($template_exploded) !== 0) {
            $required_extra = count($template_exploded) - ($i % count($template_exploded));

            // Loop for each extra need empty template part
            for ($i = 0; $i < $required_extra; $i++) {
                // Get the correct current layout part
                $layout_part_count = $this->get_template_layout_part($template_exploded, $layout_part_count);

                // Set the template to the layout part
                $template = $template_exploded[$layout_part_count];

                // Output the empty template part removing any shortcodes
                $post_output_arr[] = strip_shortcodes($template);
            }

        }

        // Join together all the posts
        $post_output = implode('', $post_output_arr);

        return $post_output;
    }


    /**
     * Processes the templates and adds in the content
     * @param  int  $campaign_id    The post id
     * @param  arr  $template       An array of selected templates
     * @return str                  The entire email content
     */
    private function build_email($campaign_id, $template) {
        $nc_posts_all = $this->get_posts($campaign_id);

        $nc_reg_posts = $nc_posts_all['newsletter_campaign_builder_post'];

        $reg_posts_output = $this->content_to_template($nc_reg_posts, $template['post']);

        // Loop through nc_posts_all to get the special posts
        foreach ($nc_posts_all as $nc_post_special => $post_ids) {
            // Ignore regular posts
            if($nc_post_special !== 'newsletter_campaign_builder_post') {
                // Fetch the hashed code from the end of the key
                $template_key_exploded = explode('_', $nc_post_special);
                $template_key_code = array_pop($template_key_exploded);

                foreach ($template['special'] as $special_template) {
                    // Make sure the template is correct
                    if($special_template['newsletter_campaign_template_hidden'] === $template_key_code) {
                        $special_shortcode = $special_template['newsletter_campaign_template_special-code'];
                        $special_posts_output[$special_shortcode] = $this->content_to_template($post_ids, $special_template['newsletter_campaign_template_special-body']);

                        // Add shortcode for these special posts
                        $email_special_shortcode = new Newsletter_campaign_shortcodes(null, $special_template['newsletter_campaign_template_special-body']);
                        $email_special_shortcode->add_shortcode($special_shortcode, $special_posts_output[$special_shortcode]);
                    }
                }
            }
        }

        // Place the regular posts in the main template
        $reg_posts_shortcode = new Newsletter_campaign_shortcodes(null, $reg_posts_output);
        $email_output = $reg_posts_shortcode->nc_do_shortcodes($template['base']);

        return $email_output;
    }


    /*
     * Get the selected template
     */

    private function get_template($id) {
        // Set up an array for the return values
        $template_return = array();

        // Fetch the template ID from the meta data
        $template_id = get_post_meta( $id, '_campaign_template-select', true );

        // Fetch the base html
        $base_html_meta = get_post_meta( $template_id, '_template_base-html', true);
        if (!empty($base_html_meta)) {
            $template_return['base'] = html_entity_decode( $base_html_meta );
        }

        // Fetch the post html
        $post_html_meta = get_post_meta( $template_id, '_template_post-html', true);
        if (!empty($post_html_meta)) {
            $template_return['post'] = html_entity_decode( $post_html_meta );
        }

        // Fetch any special htmls
        $special_html = get_post_meta( $template_id, '_template_repeater', true);

        // Only send the special html back if there is at least name saved
        if (isset($special_html[0]['newsletter_campaign_template_special-name'])) {
            // One or more special posts saved
            $special_templates = get_post_meta( $template_id, '_template_repeater', true);
            // Decode the html entities
            $i = 0;
            foreach ($special_templates as $template_item) {
                $special_templates[$i]['newsletter_campaign_template_special-body'] = html_entity_decode($template_item['newsletter_campaign_template_special-body']);
                $i++;
            }
            $template_return['special'] = $special_templates;
        }

        return apply_filters('nc_get_template', $template_return);
    }


    /**
     * Process the list of email address in the test email box and
     * return an array of email addresses
     * @param  int  $id     The ID of the current post
     * @return arr          Containing valid and/or invalid email addresses
     */
    private function get_test_addresses($id) {
        $test_addresses_raw_arr = get_post_meta( $id, '_campaign_test-send', true );
        if (empty($test_addresses_raw_arr)) {
            // Return an empty array
            return array();
        }

        // Decode the test address data
        $test_addresses_raw = html_entity_decode($test_addresses_raw_arr[0]['newsletter_campaign_campaign_test-send-addresses']);

        // Delimiters:  comma newline, space newline, comma space, comma, space, newline,
        $test_addresses_arr = preg_split( "/(,\\n| \\n|, |,| |\\n)/", $test_addresses_raw );

        // Create an array to store just the email addresses
        $subcriber_emails_return = array();

        foreach ($test_addresses_arr as $test_address) {
            if (is_email($test_address)) {
                $subcriber_emails_return['valid'][] = $test_address;
            } else {
                $subcriber_emails_return['invalid'][] = $test_address;
            }
        }
        return $subcriber_emails_return;
    }


    /*
     * Get the email addresses
     * Returns an array of email address
     */

    private function get_addresses($id) {
        // Get the ids of all selected subscriber lists
        $subscriber_lists_meta = get_post_meta( $id, '_campaign_subscriber-list-check', true );
        $subscriber_lists_ids = array();
        // Flatten the array to create an array of the values
        array_walk_recursive($subscriber_lists_meta, function ($current) use (&$subscriber_lists_ids) {
            $subscriber_lists_ids[] = $current;
        });

        // Fetch all the posts that belong to the selected subscriber list(s)
        $send_campaign_subscriber_posts_args = apply_filters( 'newsletter_campaign_send_campaign_subscriber_posts_args',
            array(
                'posts_per_page'    =>  -1,
                'orderby'           => 'title',
                'post_type'         => 'subscriber',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'subscriber_list',
                        'terms' => $subscriber_lists_ids
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

        $i = 0;

        // Loop through the posts to generate an array of emails
        foreach ($subscriber_posts as $subscriber) {

            // Perform a check to make sure the address has not already been added
            if (!in_array($subscriber->post_title, $subscriber_emails_valid)) {

                // Check that it is a valid email address
                if (is_email($subscriber->post_title)) {
                    // Everything is fine with address, add it to array along with other data to be possibly put into individual messages
                    $subscriber_emails_valid[$i]['id'] = $subscriber->ID;
                    $subscriber_emails_valid[$i]['email'] = $subscriber->post_title;
                    $subscriber_emails_valid[$i]['name'] = get_post_meta( $subscriber->ID, '_subscriber_name', true );
                    $subscriber_emails_valid[$i]['extra'] = get_post_meta( $subscriber->ID, '_subscriber_extra', true );
                    $subscriber_emails_valid[$i]['hash'] = get_post_meta( $subscriber->ID, '_subscriber_hash', true );

                } else { // Not valid
                    $subscriber_emails_invalid[] = array($subscriber->ID => $subscriber->post_title);
                }

            } else { // A duplicate
                $subscriber_emails_duplicate[] = array($subscriber->ID => $subscriber->post_title);
            }

            $i++;
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

        return apply_filters('nc_get_addresses', $subcriber_emails_return);
    }


    /*
     * Show admin message
     */
    public function show_admin_notice() {
        global $post;

        $mail_sent = get_post_meta( $post->ID, 'mail_sent', true );
        $is_mail_sent = $mail_sent[0];
        $messages = $mail_sent[1];

        if (!empty($messages)) {
            foreach ($messages as $message) {
                ?>
                <div class="<?php echo ($is_mail_sent === 'yes' ? 'updated' : 'error'); ?>">
                    <p><?php echo $message; ?></p>
                </div>
                <?php
            }
        }
        delete_post_meta($post->ID, 'mail_sent');
    }


    /*
     * Checks if the mail has been sent, if so, displays the admin notice
     */

    public function check_mail_sent() {
        // If not in campaign screen, exit
        if ( $this->check_screen() !== true ) {
            return;
        }
        global $post;

        if (isset($post->ID)) {
            $mail_sent = get_post_meta( $post->ID, 'mail_sent', true );

            if(!empty($mail_sent)) {
                add_action('admin_notices', array($this, 'show_admin_notice') );
            }
        }
    }


    /*
     * Set email content type to html
     */

    public function set_html_content_type() {
        return 'text/html';
    }



    /*
     * Get the email headers and subject
     */

    private function get_headers($campaign_id, $type) {
        $return_str = get_post_meta( $campaign_id, '_campaign_message-' . $type, true );
        if ($type === 'from') {
            $return_str = 'From:' . html_entity_decode($return_str);
        }
        return apply_filters( 'nc_get_headers', $return_str );
    }



    /*
     * Send email
     * $addresses: array of email addresses
     */

    private function send_email($addresses, $subject, $from, $message) {

        // Set up an array to store whether it was successful
        $mail_success = array();
        $mail_success[0] = 'yes';
        // Store the successful send count
        $mail_success[2] = 0;

        // Set email content type to html
        add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );

        // Add the hash of the message to be passed through - for ´view in browser´ functionality
        $message_hash = wp_hash($message);

        $i = 0;
        // Send mail individually
        foreach ($addresses as $address) {

            // Add the message hash to the $address array
            $address['message_hash'] = $message_hash;

            // Insert per-email shortcodes
            // Get the sender details and send the object to the shortcodes class
            $per_email_shortcodes = new Newsletter_campaign_shortcodes(null, null, $address);
            $converted_message = $per_email_shortcodes->nc_do_shortcodes($message);

            // The email has content - send the email
            if ($this->preview) {
                // Just show one message as the preview
                if ($i === 2) {
                    echo $converted_message;
                    $mail_success[2]++;
                }
            } else {
                $mail_sent = wp_mail( $address['email'], $subject = $subject, $converted_message = $converted_message, $headers = $from . "\r\n" );

                if ($mail_sent) {
                    // Increment the successful send counter
                    $mail_success[2]++;
                } else {
                    // Add any failed sends to the mail_failed array
                    $mail_success[0] = 'no';
                    $mail_success[1][] = $address;
                }
            }

            $i++;
        }

        // Save to archive for the view in browser functionality
        if ($mail_success[2] > 0) {
            // If any mail has been successfully sent, save the campaign

            // Get the current view in browser options
            $html_archive = get_option('nc_html_archive');
            foreach ($html_archive as $html_template) {
                // Only save a new archive item if the same hash hasn't been used before
                if (in_array($message_hash, $html_template)) {
                    $duplicate = true;
                    break;
                }
            }

            // If not been saved before save now
            if (!$duplicate) {
                $html_archive[] = array('hash' => $message_hash, 'content' => $message);
                update_option('nc_html_archive', $html_archive);
            }

        }

        // Remove html email type
        remove_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );

        return $mail_success;
    }


    /**
     * Get a comma separated list of subscriber addresses
     * @param  array    $addresses      In the form of: Array([0]=>Array('id'=>'address'), [1]=>Array('id'=>'address'))
     * @param  string   $type
     * @param  boolean  $delete
     * @return string
     */
    public function get_subscriber_list_text($addresses, $type, $delete = false) {
        // Build the list of links to invalid addresses
        $return_string = '';
        $i = 0;
        $count = count($addresses[$type]);
        foreach ($addresses[$type] as $addressess) {
            foreach ($addressess as $id => $address) {
                $return_string .= '<a href="' . get_edit_post_link($id) . '" target="_blank">' . $address . '</a>';
                if ($delete === true) {
                    $return_string .= ' <a href="' . get_delete_post_link( $id ) . '" target="_blank">[' . __('delete', 'newsletter-campaign') . ']</a>';
                }
                if ($count - 2 === $i) {
                    $return_string .= ' ' . __('and', 'newsletter-campaign') . ' ';
                } else if ($count - 1 !== $i) {
                    $return_string .= ', ';
                }
            }
            $i++;
        }

        return $return_string;
    }


    /*
     * The functionality of the send
     */

    public function send_campaign() {
        if (!isset($_POST['nc-campaign__confirmation-true']) && !isset($_POST['newsletter_campaign_campaign_test-send-btn'])) {
            return;
        }

        $send_test = isset($_POST['newsletter_campaign_campaign_test-send-btn']) ? true : false;

        // Get the current campagin id
        $campaign_id = $_POST['post_ID'];

        // Set up an array to hold return messages
        $campaign_message = array();

        // Get the list of email addresses
        if ($send_test) {
            $send_type = __('test email', 'newsletter-campaign');
            // Get the test email addresses
            $addresses = $this->get_test_addresses($campaign_id);
        } else {
            $send_type = __('campaign', 'newsletter-campaign');
            // Get the campaign email addresses
            $addresses = $this->get_addresses($campaign_id);
        }

        if (empty($addresses) || empty($addresses['valid'])) {
            $campaign_message[] = sprintf( __( 'Couldn\'t find any valid addresses, %s not sent.', 'newsletter-campaign' ), $send_type );
        }

        // Get the data from the template
        $template = $this->get_template($campaign_id);
        if (empty($template['base']) || empty($template['post'])) {
            $campaign_message[] = sprintf( __( 'Couldn\'t find valid data in the selected template, %s not sent.', 'newsletter-campaign' ), $send_type );
        }

        // Proceed only if no errors have been logged
        if(empty($campaign_message)) {
            // Build email
            $email_subject = $this->get_headers($campaign_id, 'subject');
            $email_from = $this->get_headers($campaign_id, 'from');
            $email_content = $this->build_email($campaign_id, $template);

            if (empty($email_content)) {
                $campaign_message[] = sprintf( __( 'Something went wrong in using your template, %s not sent.', 'newsletter-campaign' ), $send_type );
                update_post_meta($campaign_id, 'mail_sent', array('no', $campaign_message));
            } else {
                $mail_success = $this->send_email($addresses['valid'], $email_subject, $email_from, $email_content);

                // If there were duplicate or invalid addresses display messages
                if (!empty($addresses['invalid'])) {
                    // Get a formatted list of links to invalid addresses
                    if (!$send_test) {
                        $invalid_addresses = $this->get_subscriber_list_text($addresses, 'invalid', true);
                    } else {
                        // Wrap each address in <strong> tag
                        foreach($addresses['invalid'] as $key => $invalid_address){
                            $addresses['invalid'][$key] = '<strong>' . $invalid_address . '</strong>';
                        }
                        $invalid_addresses = implode(', ', $addresses['invalid']);
                    }
                    $campaign_message[] = __('Some email addresses were invalid and could not be sent: ') . $invalid_addresses . '.';
                }
                if (!empty($addresses['duplicate'])) {
                    // Get a formatted list of links to duplicate addresses
                    $duplicate_addresses = $this->get_subscriber_list_text($addresses, 'duplicate');
                    $campaign_message[] = __('Some email addresses were duplicates and were not sent: ') . $duplicate_addresses . '.';
                }

                if ($mail_success[0] === 'yes') { // all messages sent successfully
                    $campaign_message[] = sprintf( _n('%s has been successfully sent to %d address.', '%s has been successfully sent to %d addresses.', $mail_success[2], 'newsletter-campaign'), ucfirst($send_type), $mail_success[2] );
                    // Set the mail_sent meta
                    update_post_meta($campaign_id, 'mail_sent', array('yes', $campaign_message));

                } else {
                    $count_tried = count($addresses['valid']['email']);
                    $count_failed = count($mail_success[1]);
                    $count_success = $count_tried - $count_failed;

                    if($count_tried === $count_failed) {
                        $campaign_message[] = __('Campaign sending failed - all of the messages failed to send.', 'newsletter-campaign');
                        update_post_meta($campaign_id, 'mail_sent', array('no', $campaign_message));
                    } else { // Some messages failed to send
                        $campaign_message[] = sprintf( _n('Out of %d message', 'Out of %d messages', $count_tried, 'newsletter-campaign'), $count_tried) . ', ' . sprintf( _n('%d message was sent successfully', '%d messages were sent successfully', $count_success, 'newsletter-campaign'), $count_success) . ' ' . sprintf( _n('and %d message failed to send.', '%d messages failed to send.', $count_failed, 'newsletter-campaign'), $count_failed);
                        update_post_meta($campaign_id, 'mail_sent', array('yes', $campaign_message));
                    }
                }
            }
        } else {
            update_post_meta($campaign_id, 'mail_sent', array('no', $campaign_message));
        }
    }

}

new Newsletter_campaign_send_campaign;