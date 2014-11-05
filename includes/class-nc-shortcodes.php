<?php

class Newsletter_campaign_shortcodes {
    private $post_object;
    private $posts_content;
    private $custom_output;
    private $recipient;
    private $tags;


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

        $this->tags = nc_get_html_tags();
    }


    public function nc_do_shortcodes($template) {
        return do_shortcode($template);
    }


    private function nested_add_shortcodes() {
        $tags = nc_get_html_tags();
        $nested_items = nc_fetch_array_keys($tags, 'nest', 'title', true);

        foreach ($nested_items as $item => $value) {
            for ($i = 1; $i <= NC_MAX_NEST_DEPTH; $i++) {
                add_shortcode( 'nc_' . $value . '_' . $i, array($this, 'set_' . $value) );
            }
        }
    }


    private function add_general_shortcodes() {
        $tags = nc_get_html_tags();
        $shortcode_tags = nc_fetch_array_keys($tags, 'shortcode', 'shortcode');

        foreach ($shortcode_tags as $item => $value) {
            $shortcode_raw = str_replace('nc_', '', $value);
            add_shortcode( $value, array($this, 'set_' . $shortcode_raw) );
        }
    }


    public function add_shortcodes() {
        $this->add_general_shortcodes();
        $this->nested_add_shortcodes();
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
     * @param  arr $attrs The array of general attributes
     * @return arr
     */
    private function prepare_attrs($attrs) {
        $shortcode_attrs = [];

        foreach ($attrs[0] as $attr) {
            $shortcode_attrs[$attr['arg']] = '';
        }

        return $shortcode_attrs;
    }


    /**
     * Process attributes for html shortcodes
     * @param arr $atts      Shortcode attributes
     * @param str $content   Content between shortcodes
     * @param str $tag_name  Name of the tag
     * @param arr $tag_attrs Attributes to add to the HTML output
     */
    private function set_html_tag($atts, $content, $tag_name, $tag_attrs = array(), $enclosing = true) {
        $a = shortcode_atts( $tag_attrs, $atts );

        $tag = '<' . $tag_name;

        foreach ($a as $key => $value) {
            // Swap out any underscores for hyphens in the key
            $key = str_replace('_', '-', $key);
            $tag .= $a[$key] !== '' ? ' ' . $key . '="' . $value . '"' : '';
        }

        if ($enclosing === false) {
            $tag .= ' />';
            $output = $tag;
        } else {
            $tag .= '>';
            $output = is_null($content) ? $tag . '</' . $tag_name . '>' : $tag . $this->nc_do_shortcodes($content) . '</' . $tag_name . '>';
        }

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
        $output = $this->set_html_tag($atts, $content, 'html', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_html')));
        return $output;
    }


    public function set_head($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'head', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_head')));
        return $output;
    }

    public function set_body($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'body', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_body')));
        return $output;
    }

    public function set_base($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'base', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_base')), false);
        return $output;
    }

    public function set_link($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content = null, 'link', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_link')), false);
        return $output;
    }

    public function set_meta($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content = null, 'meta', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_meta')), false);
        return $output;
    }

    public function set_style($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'style', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_style')));
        return $output;
    }

    public function set_title($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'title');
        return $output;
    }

    public function set_p($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'p', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_p')));
        return $output;
    }

    public function set_h1($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h1', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h1')));
        return $output;
    }

    public function set_h2($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h2', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h2')));
        return $output;
    }

    public function set_h3($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h3', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h3')));
        return $output;
    }

    public function set_h4($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h4', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h4')));
        return $output;
    }

    public function set_h5($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h5', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h5')));
        return $output;
    }

    public function set_h6($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'h6', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_h6')));
        return $output;
    }

    public function set_dl($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'dl', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_dl')));
        return $output;
    }

    public function set_dt($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'dt', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_dt')));
        return $output;
    }

    public function set_dd($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'dd', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_dd')));
        return $output;
    }

    public function set_ol($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'ol', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_ol')));
        return $output;
    }

    public function set_ul($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'ul', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_ul')));
        return $output;
    }

    public function set_li($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'li', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_li')));
        return $output;
    }

    public function set_div($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'div', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_div')));
        return $output;
    }

    public function set_hr($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'hr', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_hr')));
        return $output;
    }

    public function set_a($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'a', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_a')));
        return $output;
    }

    public function set_em($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'em', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_em')));
        return $output;
    }

    public function set_strong($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'strong', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_strong')));
        return $output;
    }

    public function set_span($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'span', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_span')));
        return $output;
    }

    public function set_br($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'br', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_br')));
        return $output;
    }

    public function set_sub($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'sub', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_sub')));
        return $output;
    }

    public function set_sup($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'sup', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_sup')));
        return $output;
    }

    public function set_img($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'img', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_img')));
        return $output;
    }

    public function set_table($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'table', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_table')));
        return $output;
    }

    public function set_tr($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'tr', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_tr')));
        return $output;
    }

    public function set_th($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'th', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_th')));
        return $output;
    }

    public function set_td($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'td', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_td')));
        return $output;
    }

    public function set_colgroup($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'colgroup', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_colgroup')));
        return $output;
    }

    public function set_col($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'col', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_col')));
        return $output;
    }

    public function set_caption($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'caption', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_caption')));
        return $output;
    }

    public function set_thead($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'thead', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_thead')));
        return $output;
    }

    public function set_tbody($atts, $content = null) {
        $output = $this->set_html_tag($atts, $content, 'tbody', $this->prepare_attrs(nc_fetch_array_keys($this->tags, 'shortcode', 'args', 'nc_tbody')));
        return $output;
    }

    public function set_comment($atts, $content = null) {
        return "<!-- " . $content . " -->";
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
        $output =   is_null($content) ? '<a href="' . $unsubscribe_url . '">' . __('Unsubscribe', 'newsletter-campaign') . '</a>' :
                    '<a href="' . $unsubscribe_url . '">' . $content . '</a>';
        return $output;
    }


    public function set_browser_link($atts, $content = null) {
        // Get ´view in browser´ url
        $recipient = $this->recipient;
        $browser_view_url = get_home_url() . '/?viewinbrowser=' . $recipient['id'];
        $browser_view_url .= '&hash=' . $recipient['message_hash'];
        $output =   is_null($content) ? '<a href="' . $browser_view_url . '">' . __('View in browser', 'newsletter-campaign') . '</a>' :
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


    public function set_feat_image($atts) {
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