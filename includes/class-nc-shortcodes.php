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
        add_shortcode( 'nc_doctype', array($this, 'set_doctype') );
        add_shortcode( 'nc_html', array($this, 'set_html') );
        add_shortcode( 'nc_posts', array($this, 'set_posts') );
        add_shortcode( 'nc_post_title', array($this, 'set_post_title') );
        add_shortcode( 'nc_post_body', array($this, 'set_post_body') );
        add_shortcode( 'nc_feat_image', array($this, 'set_feat_img') );
    }


    public function add_per_message_shortcodes() {
        add_shortcode( 'nc_unsubscribe_link', array($this, 'set_unsubscribe') );
        add_shortcode( 'nc_browser_link', array($this, 'set_browser_link') );
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


    /**
     * HTML
     */


    /**
     * Put the general HTML attributes into a format that shortcode attributes can use
     * @param  arr $general_attrs The array of general attributes
     * @return arr
     */
    private function prepare_general_attrs($general_attrs) {
        $shortcode_general_attrs = [];

        foreach ($general_attrs as $attr) {
            $shortcode_general_attrs[$attr['arg']] = '';
        }

        return $shortcode_general_attrs;
    }


    /**
     * Process attributes for html shortcodes
     * @param arr $atts      Shortcode attributes
     * @param str $content   Content between shortcodes
     * @param str $tag_name  Name of the tag
     * @param arr $tag_attrs Attributes to add to the HTML output
     */
    private function set_html_tag($atts, $content, $tag_name, $tag_attrs = array(), $enclosing = true) {
        // Prepare general html attributes
        $general_attributes = $this->prepare_general_attrs(nc_general_html_attributes());

        $a = shortcode_atts( array_merge($general_attributes, $tag_attrs), $atts );

        $tag = '<' . $tag_name;

        foreach ($a as $key => $value) {
             $tag .= $a[$key] !== '' ? ' ' . $key . '="' . $value . '"' : '';
        }

        $tag .= '>';

        $output = $content === null ? $tag . '</' . $tag_name . '>' : $tag . $content . '</' . $tag_name . '>';
        return $output;
    }

    public function set_doctype($atts) {
        $a = shortcode_atts( array(
            'doctype' => 'html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"'
        ), $atts );

        switch ($a['doctype']) {
            case 'html5':
                $doctype = 'html';
                break;

            case 'html-4-01-strict':
                $doctype = 'HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"';
                break;

            case 'html-4-01-transitional':
                $doctype = 'HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"';
                break;

            case 'xhtml-1-transitional':
                $doctype = 'html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"';
                break;

            default:
                // Defaults to xhtml-1-strict
                $doctype = 'html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"';
                break;
        }

        $output = '<!DOCTYPE ' . $doctype . '>';
        return $output;
    }

    public function set_html($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'html', array('xmlns' => ''));
        return $output;
    }


    /**
     * EMAIL FUNCTIONALITY
     */

    public function set_posts() {
        if (!empty($this->posts_content)) {
            return $this->posts_content;
        }
    }


    public function set_unsubscribe($atts, $content = null) {
        $a = shortcode_atts( array(
            'list' => ''
        ), $atts );
        // Get unsubscribe url
        $recipient = $this->recipient;
        $unsubscribe_url = get_home_url() . '/?unsubscribe=' . $recipient['email'];
        $unsubscribe_url .= $a['list'] !== '' ? '&list=' . $a['list'] : '';
        $unsubscribe_url .= '&hash=' . $recipient['hash'];
        $output =   $content = null ? '<a href="' . $unsubscribe_url . '">' . __('Unsubscribe', 'newsletter-campaign') . '</a>' :
                    '<a href="' . $unsubscribe_url . '">' . $content . '</a>';
        return $output;
    }


    public function set_browser_link($atts, $content = null) {
        // Get ´view in browser´ url
        $recipient = $this->recipient;
        $browser_view_url = get_home_url() . '/?viewinbrowser=' . $recipient['id'];
        $browser_view_url .= '&hash=' . $recipient['message_hash'];
        $output =   $content = null ? '<a href="' . $browser_view_url . '">' . __('View in browser', 'newsletter-campaign') . '</a>' :
                    '<a href="' . $browser_view_url . '">' . $content . '</a>';
        return $output;
    }


    /**
     * PERSONAL FIELDS
     */

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


    /**
     * POST
     */

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
}

new Newsletter_campaign_shortcodes();