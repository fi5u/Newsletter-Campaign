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