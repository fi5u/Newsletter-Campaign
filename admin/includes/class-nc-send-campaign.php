<?php

/**
 * Sends the selected campaign
 */

class Newsletter_campaign_send_campaign {
    private $placeholder_posts = '%POSTS%';
    private $placeholder_post = '%POST%';
    private $placeholder_title = '%TITLE%';
    private $placeholder_body = '%BODY%';

    public function __construct() {
        add_action( 'wp_ajax_my_action', array( $this, 'my_action_callback' ) );
        add_action('init', array($this, 'send_campaign'), 30 );
        add_action( 'admin_head', array($this,'check_mail_sent') );

        add_action( 'current_screen', array($this, 'check_screen') );
    }


    // TEST FOR AJAX CALLBACK REMOVE WHEN DEF NOT NEED AJAX HERE
    public function my_action_callback() {
        global $wpdb;
        $whatever = intval( $_POST['whatever'] );
        $whatever += 10;
            echo $whatever;
        die();
    }


    /*
     * Check that we are on the correct screen
     */

    public function check_screen() {
        // If not in campaign screen, exit
        $screen = get_current_screen();
        if ( 'campaign' !== $screen->post_type ) {
            return false;
        } else {
            add_filter('post_updated_messages', array($this,'set_messages'));
            return true;
        }
    }


    /*
     * Get all the posts for current campaign
     * Return array
     */

    private function get_posts($campaign_id) {
        $posts = get_post_meta($campaign_id, '_campaign_builder', true);
        return $posts;
    }


    /*
     * Fill in the supplied template with post content and returns the primary subject completed
     * Return string
     */

    private function content_to_template($options) {
        // Set up an array to add posts content to
        $subject_arr = array();

        // Generate the output for each post
        foreach ($options['posts_arr'] as $post_item) {
            // Get the post object so we can get the content
            $post_object = get_post($post_item);

            // Set an array to store replacements
            $replacements_arr = array();
            $replacements = $options['replace'];
            $i = 0;
            foreach ($replacements as $replacement) {

                switch ($replacement) {
                    case 'title':
                        $replacements_arr[$i] = get_the_title($post_item);
                        break;

                    case 'body':
                        $replacements_arr[$i] = $post_object->post_content;
                        break;

                    default:
                        // Show the excerpt as a default
                        $replacements_arr[$i] = $post_object->post_excerpt;
                        break;
                }
                $i++;
            }

            // Perform replacement
            $subject_arr[] = str_replace($options['search'], $replacements_arr, $options['subject']);
        }

        // Join together all the posts
        $primary_replace = implode('', $subject_arr);

        // Set the main email content
        $primary_content = str_replace($options['primary_search'], $primary_replace, $options['primary_subject']);

        return $primary_content;
    }


    /*
     * Build the email from the template and the campaign data
     * Return string
     */

    private function build_email($campaign_id, $template) {
        $base_template = $template['base'];
        $post_template = $template['post'];

        $special_templates = !empty($template['special']) ? $template['special'] : '';

        $nc_posts_all = $this->get_posts($campaign_id);

        $nc_posts = $nc_posts_all['newsletter_campaign_builder_post'];

        // Fill in the template
        // Set content_to_template args
        // TODO: apply filters
        $content_to_template_args = array(
            'posts_arr' => $nc_posts,
            'search' => array($this->placeholder_title, $this->placeholder_body),
            'replace' => array('title', 'body'),
            'subject' => $post_template,
            'primary_search' => $this->placeholder_posts,
            'primary_subject' => $base_template
        );

        $email_content = $this->content_to_template($content_to_template_args);

        // Loop through nc_posts_all to get the special posts
        foreach ($nc_posts_all as $nc_post_special => $post_ids) {
            // Ignore regular posts
            if($nc_post_special !== 'newsletter_campaign_builder_post') {

                // Fetch the hashed code from the end of the key
                $template_key_exploded = explode('_', $nc_post_special);
                $template_key_code = array_pop($template_key_exploded);

                foreach ($special_templates as $special_template) {
                    // Make sure the template
                    if($special_template['newsletter_campaign_template_hidden'] === $template_key_code) {

                        $content_to_template_args = array(
                            'posts_arr' => $post_ids,
                            'search' => array($this->placeholder_title, $this->placeholder_body),
                            'replace' => array('title', 'body'),
                            'subject' => $special_template['newsletter_campaign_template_special-body'],
                            'primary_search' => $special_template['newsletter_campaign_template_special-code'],
                            'primary_subject' => $email_content
                        );

                        $email_content = $this->content_to_template($content_to_template_args);

                        break;
                    }
                }
            }
        }

        return $email_content;
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
        if (!empty(get_post_meta( $template_id, '_template_base-html', true))) {
            $template_return['base'] = get_post_meta( $template_id, '_template_base-html', true);
        }

        // Fetch the post html
        if (!empty(get_post_meta( $template_id, '_template_post-html', true))) {
            $template_return['post'] = get_post_meta( $template_id, '_template_post-html', true);
        }

        // Fetch any special htmls
        $special_html = get_post_meta( $template_id, '_template_multi', true);

        // Only send the special html back if there is at least name saved
        if (isset($special_html[0]['newsletter_campaign_template_special-name'])) {
            // One or more special posts saved
            $template_return['special'] = get_post_meta( $template_id, '_template_multi', true);
        }

        return $template_return;
    }


    /*
     * Get the email addresses
     * Returns an array of email address
     */

    private function get_addresses($id) {
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
        global $post;

        $mail_sent = get_post_meta( $post->ID, 'mail_sent', true );
        ?>
        <div class="updated">
            <p><?php
                if($mail_sent == 'yes') {
                    _e( 'Campaign sent!', 'newsletter-campaign' );
                } else {
                    _e( 'Messages not sent!', 'newsletter-campaign' );
                } ?>
            </p>
        </div>
        <?php
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

        $mail_sent = get_post_meta( $post->ID, 'mail_sent', true );

        if($mail_sent) {
            add_action('admin_notices', array($this, 'show_admin_notice') );
        }
    }


    /*
     * Set output messages for Campaign
     */

    public function set_messages($messages) {
        // Do not display 'view post' link
        $messages['post'][1] = __('Campaign updated.', 'newsletter-campaign');
        // Remove 'Post saved' message when mail sent
        $messages['post'][4] = '';
        return $messages;
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

        // Get the current campagin id
        $campaign_id = $_POST['post_ID'];

        // Set up an array to hold return messages
        $campaign_message = array();

        // Get the list of email addresses
        $addresses = $this->get_addresses($campaign_id);
        if (empty($addresses) || empty($addresses['valid'])) {
            $campaign_message['error'][] = __('Couldn\'t find any valid addresses to send the campaign to, campaign not sent.', 'newsletter-campaign');
        }

        // Get the data from the template
        $template = $this->get_template($campaign_id);
        if (empty($template['base']) || empty($template['post'])) {
            $campaign_message['error'][] = __('Couldn\'t find valid data in the selected template, campaign not sent.', 'newsletter-campaign');
        }

        if(!empty($campaign_message['error'])) { // there are some error messages to display
            // TODO: dislay admin notice with messages
        } else {
            // Build email
            $email_content = $this->build_email($campaign_id, $template);

            if (!empty($email_content)) {
                // Send the email
                //$mail_sent = $this->send_email($addresses['valid']);
                //echo $email_content;

                // Set the mail_sent meta
                update_post_meta($campaign_id, 'mail_sent', 'yes');
            } else {
                update_post_meta($campaign_id, 'mail_sent', 'no');
                $campaign_message['error'][] = __('Something went wrong in using your template, campaign not sent.', 'newsletter-campaign');
            }
        }
    }

}

new Newsletter_campaign_send_campaign;