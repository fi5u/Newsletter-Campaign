<?php

function is_array_empty($input) {
    $result = true;

    if (is_array($input) && count($input) > 0) {
        foreach ($input as $value) {
            $result = $result && is_array_empty($value);
        }
    } else {
      $result = empty($input);
    }

    return $result;
}


/**
 * Return true if the current screen is the desired screen
 * @param  str  $post_type  The name of the desired screen
 * @return bool             True: is on desired page, False: not
 */
function nc_check_screen($post_type) {
    // If not in campaign screen, exit
    $screen = get_current_screen();
    if ( $post_type !== $screen->post_type ) {
        return false;
    } else {
        return true;
    }
}


function nc_general_html_attributes() {
    $general_attributes = apply_filters('newsletter_campaign_general_html_attributes', array(
        array(
            'name'  => 'nc-shortcode-arg-accesskey',
            'arg'   => 'accesskey',
            'title' => 'Accesskey'
        ),
        array(
            'name'  => 'nc-shortcode-arg-class',
            'arg'   => 'class',
            'title' => 'Class'
        ),
        array(
            'name'  => 'nc-shortcode-arg-contenteditable',
            'arg'   => 'contenteditable',
            'title' => 'Contenteditable'
        ),
        array(
            'name'  => 'nc-shortcode-arg-dir',
            'arg'   => 'dir',
            'title' => 'Dir'
        ),
        array(
            'name'  => 'nc-shortcode-arg-hidden',
            'arg'   => 'hidden',
            'title' => 'Hidden'
        ),
        array(
            'name'  => 'nc-shortcode-arg-id',
            'arg'   => 'id',
            'title' => 'ID'
        ),
        array(
            'name'  => 'nc-shortcode-arg-lang',
            'arg'   => 'lang',
            'title' => 'Lang'
        ),
        array(
            'name'  => 'nc-shortcode-arg-style',
            'arg'   => 'style',
            'title' => 'Style'
        ),
        array(
            'name'  => 'nc-shortcode-arg-tabindex',
            'arg'   => 'tabindex',
            'title' => 'Tabindex'
        ),
        array(
            'name'  => 'nc-shortcode-arg-title',
            'arg'   => 'title',
            'title' => 'Title'
        )
    ));

    return $general_attributes;
}