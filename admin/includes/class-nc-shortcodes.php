<?php

class Newsletter_campaign_shortcodes {
    private $post_object;
    private $posts_content;
    private $custom_output;
    private $recipient;


    public function __construct($post_object = null, $posts_content = null, $recipient = false) {
        if ($post_object) {
            $this->post_object = $post_object;
        }

        if ($posts_content) {
            $this->posts_content = $posts_content;
        }

        if (!$recipient) {
            $this->add_shortcodes();
        } else {
            $this->recipient = $recipient;
            $this->add_per_message_shortcodes();
        }
    }


    public function nc_do_shortcodes($template) {
        return do_shortcode($template);
    }


    public function add_shortcodes() {
        add_shortcode( 'nc_posts', array($this, 'set_posts') );
        add_shortcode( 'nc_post_title', array($this, 'set_post_title') );
        add_shortcode( 'nc_post_body', array($this, 'set_post_body') );
        add_shortcode( 'nc_feat_image', array($this, 'set_feat_img') );
    }


    public function add_per_message_shortcodes() {
        add_shortcode( 'nc_unsubscribe_link', array($this, 'set_unsubscribe') );
        add_shortcode( 'nc_name', array($this, 'set_name') );
        add_shortcode( 'nc_email', array($this, 'set_email') );
        add_shortcode( 'nc_extra', array($this, 'set_extra') );
    }


    public function add_shortcode($shortcode, $output) {
        $this->custom_output = $output;
        add_shortcode( $shortcode, array($this, 'custom_shortcode') );
    }


    public function custom_shortcode() {
        return $this->custom_output;
    }


    public function set_posts() {
        if (!empty($this->posts_content)) {
            return $this->posts_content;
        }
    }


    public function set_post_title() {
        return get_the_title($this->post_object->ID);
    }


    public function set_post_body() {
        return $this->post_object->post_content;
    }


    public function set_feat_img($atts) {
        if (has_post_thumbnail($this->post_object->ID)) {
            // If width or height passed, takes priority over size
            if (isset($atts['width']) || isset($atts['height'])) {
                // If only a width or height given then set both to the same
                $size_arr[] = isset($atts['width']) ? $atts['width'] : $atts['height'];
                $size_arr[] = isset($atts['height']) ? $atts['height'] : $atts['width'];

                return get_the_post_thumbnail($this->post_object->ID, $size_arr);
            } else {
                if (isset($atts['size'])) {
                    // Use the thumbnail size by name
                    return get_the_post_thumbnail($this->post_object->ID, $atts['size']);
                } else {
                    // Use the thumbnail size by default
                    return get_the_post_thumbnail($this->post_object->ID, 'thumbnail');
                }
            }
        }
    }


    public function set_unsubscribe($atts, $content = null) {
        // Get unsubscribe url
        $unsubscribe_url = get_home_url() . '/&unsubscribe=';
        $output =   $content = null ? '<a href="' . $unsubscribe_url . '">' . __('Unsubscribe', 'newsletter-campaign') . '</a>' :
                    '<a href="' . $unsubscribe_url . '">' . $content . '</a>';
        return $output;
    }


    public function set_name($atts) {
        $a = shortcode_atts( array(
            'before'    => '',
            'after'     => '',
            'noval'     => ''
        ), $atts );
        $recipient = $this->recipient;

        $output = $a['before'] !== '' ? $a['before'] : '';
        $output .= $recipient['name'] ? $recipient['name'] : $a['noval'];
        $output .= $a['after'] !== '' ? $a['after'] : '';

        return $output;
    }


    public function set_email($atts) {
        $a = shortcode_atts( array(
            'before'    => '',
            'after'     => '',
            'noval'     => ''
        ), $atts );
        $recipient = $this->recipient;

        $output = $a['before'] !== '' ? $a['before'] : '';
        $output .= $recipient['email'] ? $recipient['email'] : $a['noval'];
        $output .= $a['after'] !== '' ? $a['after'] : '';

        return $output;
    }


    public function set_extra($atts) {
        $a = shortcode_atts( array(
            'before'    => '',
            'after'     => '',
            'noval'     => ''
        ), $atts );
        $recipient = $this->recipient;

        $output = $a['before'] !== '' ? $a['before'] : '';
        $output .= $recipient['extra'] ? $recipient['extra'] : $a['noval'];
        $output .= $a['after'] !== '' ? $a['after'] : '';

        return $output;
    }
}

new Newsletter_campaign_shortcodes();