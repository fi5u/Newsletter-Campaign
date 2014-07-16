<?php

class Newsletter_campaign_shortcodes {
    private $post_object;
    private $posts_content;
    private $custom_output;


    public function __construct($post_object = null, $posts_content = null) {
        if ($post_object) {
            $this->post_object = $post_object;
        }

        if ($posts_content) {
            $this->posts_content = $posts_content;
        }

        $this->add_shortcodes();
    }


    public function nc_do_shortcodes($template) {
        return do_shortcode($template);
    }


    public function add_shortcodes() {
        add_shortcode( 'nc_posts', array($this, 'set_posts') );
        add_shortcode( 'nc_title', array($this, 'set_title') );
        add_shortcode( 'nc_body', array($this, 'set_body') );
        add_shortcode( 'nc_feat_image', array($this, 'set_feat_img') );
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


    public function set_title() {
        return get_the_title($this->post_object->ID);
    }


    public function set_body() {
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
}

new Newsletter_campaign_shortcodes();