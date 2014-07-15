<?php

/**
 * Add, generate and save meta boxes
 */

class Newsletter_campaign_meta_box_generator {

    public function __construct() {
        add_action( 'admin_head', array($this, 'nc_remove_subscriber_tax') );
        add_action( 'admin_head', array($this, 'nc_add_subscriber_tax') );
    }


    /**
     * Remove the subscriber taxonomy ui box in subscriber edit page
     * to be added back in by nc_add_subscriber_tax below submit box
     * called from __construct()
     */
    public function nc_remove_subscriber_tax() {
        remove_meta_box('subscriber_listdiv', 'subscriber', 'side');
    }


    /**
     * Add the subscriber taxonomy ui box in subscriber edit page below submit box
     * called from __construct()
     */
    public function nc_add_subscriber_tax() {
        add_meta_box('subscriber_listdiv', __( 'Subscriber Lists', 'newsletter-campaign' ), array($this, 'nc_subscriber_tax_ui'), 'subscriber', 'side', 'low');
    }


    /**
     * The output for the subscriber tax ui
     * Used to add back in to the edit below the submit box
     * called from: nc_add_subscriber_tax()
     */
    public function nc_subscriber_tax_ui() {
        global $post;
        $defaults = array('taxonomy' => 'subscriber_list');
        if ( !isset($box['args']) || !is_array($box['args']) )
            $args = array();
        else
            $args = $box['args'];
        extract( wp_parse_args($args, $defaults), EXTR_SKIP );
        $tax = get_taxonomy($taxonomy);

        ?>
        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
            <ul id="<?php echo $taxonomy; ?>-tabs" class="category-tabs">
                <li class="tabs"><a href="#<?php echo $taxonomy; ?>-all"><?php echo $tax->labels->all_items; ?></a></li>
                <li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop"><?php _e( 'Most Used' ); ?></a></li>
            </ul>

            <div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
                <ul id="<?php echo $taxonomy; ?>checklist-pop" class="categorychecklist form-no-clear" >
                    <?php $popular_ids = wp_popular_terms_checklist($taxonomy); ?>
                </ul>
            </div>

            <div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
                <?php
                $name = ( $taxonomy == 'category' ) ? 'post_category' : 'tax_input[' . $taxonomy . ']';
                echo "<input type='hidden' name='{$name}[]' value='0' />"; // Allows for an empty term set to be sent. 0 is an invalid Term ID and will be ignored by empty() checks.
                ?>
                <ul id="<?php echo $taxonomy; ?>checklist" data-wp-lists="list:<?php echo $taxonomy?>" class="categorychecklist form-no-clear">
                    <?php wp_terms_checklist($post->ID, array( 'taxonomy' => $taxonomy, 'popular_cats' => $popular_ids ) ) ?>
                </ul>
            </div>
        <?php if ( current_user_can($tax->cap->edit_terms) ) : ?>
                <div id="<?php echo $taxonomy; ?>-adder" class="wp-hidden-children">
                    <h4>
                        <a id="<?php echo $taxonomy; ?>-add-toggle" href="#<?php echo $taxonomy; ?>-add" class="hide-if-no-js">
                            <?php
                                /* translators: %s: add new taxonomy label */
                                printf( __( '+ %s' ), $tax->labels->add_new_item );
                            ?>
                        </a>
                    </h4>
                    <p id="<?php echo $taxonomy; ?>-add" class="category-add wp-hidden-child">
                        <label class="screen-reader-text" for="new<?php echo $taxonomy; ?>"><?php echo $tax->labels->add_new_item; ?></label>
                        <input type="text" name="new<?php echo $taxonomy; ?>" id="new<?php echo $taxonomy; ?>" class="form-required form-input-tip" value="<?php echo esc_attr( $tax->labels->new_item_name ); ?>" aria-required="true"/>
                        <label class="screen-reader-text" for="new<?php echo $taxonomy; ?>_parent">
                            <?php echo $tax->labels->parent_item_colon; ?>
                        </label>
                        <?php wp_dropdown_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => 'new'.$taxonomy.'_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => '&mdash; ' . $tax->labels->parent_item . ' &mdash;' ) ); ?>
                        <input type="button" id="<?php echo $taxonomy; ?>-add-submit" data-wp-lists="add:<?php echo $taxonomy ?>checklist:<?php echo $taxonomy ?>-add" class="button category-add-submit" value="<?php echo esc_attr( $tax->labels->add_new_item ); ?>" />
                        <?php wp_nonce_field( 'add-'.$taxonomy, '_ajax_nonce-add-'.$taxonomy, false ); ?>
                        <span id="<?php echo $taxonomy; ?>-ajax-response"></span>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }


    public function nc_add_meta_box( $id, $title, $cb, $post_type, $context, $priority, $callback_args = null ) {
        add_meta_box(
            $id,
            __($title, 'newsletter-campaign'),
            array($this, $cb),
            $post_type,
            $context,
            $priority,
            $callback_args
        );
    }


    /*
     * $meta_name = string: used for multiple fields
     */

    public function nc_save_meta_box( $post_id, $post_type, $field, $meta_name = '') {

        $meta_root = 'newsletter_campaign_' . $post_type . '_';
        if (is_array($field)) {
            // Provide a default of 'multi' only if meta name hasn't been passed but it HAS multiple fields
            $meta_name = $meta_name == '' ? 'multi' : $meta_name;
            $meta = $meta_root . $meta_name;
            $post_meta = '_' . $post_type . '_' . $meta_name;
        } else {
            $meta = $meta_root . $field;
            $post_meta = '_' . $post_type . '_' . $field;
        }

        $nonce_name = $meta . '_box';
        $nonce = $nonce_name . '_nonce';
        $screen = get_current_screen();

        if ( $post_type !== $screen->post_type ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( !isset($_POST['post_type']) ) {
            return $post_id;
        }

        if ( $post_type !== $_POST['post_type'] ) {
            return $post_id;
        }

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }

        // Check the nonce field
        if ( ! isset( $_POST[$nonce] ) ) {
            return $post_id;
        }

        if ( ! wp_verify_nonce( $_POST[$nonce], $nonce_name ) ) {
            return $post_id;
        }

        if (is_array($field)) {
            // TODO: also add in multival support for passed arrays
            $return_val = array();

            foreach ($field as $field_item) {
                $count = count($_POST[$meta_root . $field_item]);

                // Loop through each of the repeatable items, adding its data to the array
                for ( $i = 0; $i < $count; $i++ ) {

                    // Don't save if empty
                    if ( $_POST[$meta_root . $field_item][$i] != '' ) {
                        // Sanitize the user input.

                        $data = isset($_POST['newsletter_campaign_' . $post_type . '_' . $field_item][$i]) ? $_POST['newsletter_campaign_' . $post_type . '_' . $field_item][$i] : '';
                        // TODO: perform sanitation on $data
                        $return_val[$i][$meta_root . $field_item] = $data;
                    }
                }
            }

            // Update the meta field.
            update_post_meta( $post_id, '_' . $post_type . '_' . $meta_name, $return_val );

        } else {

            // Check all the post and custom post values
            if (isset($_POST[$meta])) {
                $count = count($_POST[$meta]);

                if ($count > 1) {
                    for ( $i = 0; $i < $count; $i++ ) {
                        $return_val[$i][$meta] = isset($_POST[$meta]) ? $_POST[$meta][$i] : '';
                    }
                } else { // only holds a single value
                    // TODO: sanitize!
                    $return_val = isset($_POST[$meta]) ? $_POST[$meta] : '';
                }

            } else {

                foreach($_POST as $key => $value) {
                    if (strpos($key, 'newsletter_campaign_' . $field . '_') === 0) {
                        $count = count($_POST[$key]);

                        // Loop through each of the items, adding its data to the array
                        for ( $i = 0; $i < $count; $i++ ) {
                            $return_val[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
                        }
                    }
                }

                if (!isset($return_val)) {
                    $return_val = 0;
                }
            }

            // Update the meta field.
            if (isset($return_val)) {
                update_post_meta( $post_id, '_' . $post_type . '_' . $field, $return_val );
            }
        }


    }

    public function nc_render_meta_box( $post, $metabox ) {
        $post_type = $metabox['args']['post_type'];
        $field = $metabox['args']['field'];
        $title = $metabox['args']['title'];
        $type = isset($metabox['args']['type']) ? $metabox['args']['type'] : 'text'; // Defaults to text

        if ($type !== 'multi'/* && $type !== 'custom'*/) {
            // Set nonce and value for all types except multi
            wp_nonce_field( 'newsletter_campaign_' . $post_type . '_' . $field . '_box', 'newsletter_campaign_' . $post_type . '_' . $field . '_box_nonce' );
            $value = get_post_meta( $post->ID, '_' . $post_type . '_' . $field, true );
        } else {
            // Multi has a different nonce
            wp_nonce_field( 'newsletter_campaign_' . $post_type . '_multi_box', 'newsletter_campaign_' . $post_type . '_multi_box_nonce' );
        }

        if ($type === 'textarea') {

            echo '<textarea id="newsletter_campaign_' . $post_type . '_' . $field .'" name="newsletter_campaign_' . $post_type . '_' . $field . '" placeholder="' . esc_attr( $title ) . '">';
            echo esc_attr( $value );
            echo '</textarea>';

        } else if ($type === 'select') {

            // Get the array of options available keys should be ID and post_title
            $select_options = $metabox['args']['select_options'];

            // The key or value are not set issue error and do not render select
            if (!isset($metabox['args']['key']) || !isset($metabox['args']['value'])) {
                echo 'Error: key and value not set';
            } else { // Key and value are set

                $select_key = $metabox['args']['key'];
                $select_value = $metabox['args']['value'];

                if ($select_options) {
                    echo '<select name="newsletter_campaign_' . $post_type . '_' . $field . '">';

                    // Add a blank option
                    $title_lower = strtolower($metabox['args']['title']);
                    echo '<option>' . sprintf( __('Select %s', 'newsletter-campaign'), $title_lower ) . '</option>';

                    // Loop through and output options
                    foreach ($select_options as $option) {
                        echo '<option value="' . $option->$select_key . '"';
                        if ($value == $option->$select_key) {
                            echo ' selected';
                        }
                        echo '>' . $option->$select_value . '</option>';
                    }
                    echo '</select>';
                } else { // No options found
                    if ($metabox['args']['not_found']) {
                        echo '<p>';
                        $i = 0;
                        $not_found_qty = count($metabox['args']['not_found']);
                        foreach ($metabox['args']['not_found'] as $not_found_line) {
                            echo $not_found_line;

                            // Add a line break to every line except last line
                            if ($i !== $not_found_qty-1) {
                                echo '<br>';
                            }
                            $i++;
                        }
                        echo '</p>';
                    } else { // If no not found lines passed
                        echo __('Not found', 'newsletter-campaign');
                    }
                }

            }
        } else if ($type === 'checkbox') {
            // Get the array of options available keys should be ID and post_title
            $select_options = $metabox['args']['select_options'];

            // The key or value are not set issue error and do not render select
            if (!isset($metabox['args']['key']) || !isset($metabox['args']['value'])) {
                echo 'Error: key and value not set';
            } else { // Key and value are set

                $select_key = $metabox['args']['key'];
                $select_value = $metabox['args']['value'];

                if ($select_options) {
                    // Loop through and output options
                    $i = 0;
                    foreach ($select_options as $option) {
                        echo '<input type="checkbox" name="newsletter_campaign_' . $post_type . '_' . $field . '[]" value="' . $option->$select_key . '" id="newsletter_campaign_' . $post_type . '_' . $field . '_' . $option->$select_key . '"';

                        if (is_array($value) && $this->in_array_r($option->$select_key, $value)) {
                            echo ' checked';
                        }

                        echo '><label for="newsletter_campaign_' . $post_type . '_' . $field . '_' . $option->$select_key . '">' . $option->$select_value . '</label><br>';
                        ++$i;
                    }

                } else { // No options found
                    if ($metabox['args']['not_found']) {
                        echo '<p>';
                        $i = 0;
                        $not_found_qty = count($metabox['args']['not_found']);
                        foreach ($metabox['args']['not_found'] as $not_found_line) {
                            echo $not_found_line;

                            // Add a line break to every line except last line
                            if ($i !== $not_found_qty-1) {
                                echo '<br>';
                            }
                            $i++;
                        }
                        echo '</p>';
                    } else { // If no not found lines passed
                        echo __('Not found', 'newsletter-campaign');
                    }
                }
            }

        } else if ($type === 'multi') {

            // Build the container div
            echo '<div class="nc-repeater">';

            // Add an empty drop area at the start
            echo $this->get_droparea(true);

            // Fetch the multi field array data from post meta
            $meta_vals = get_post_meta( $post->ID, '_' . $post_type . '_multi', true );

            if ( $meta_vals ) {

                foreach ($meta_vals as $meta_val) {

                    $subfields = $metabox['args']['subfields'];
                    // Set an incrementor to count each subfield we iterate over
                    $subfield_i = 0;

                    // Add the drop area that surrounds each repeater item
                    echo $this->get_droparea(false);

                    // Build the container HTML
                    echo '<div class="nc-repeater__item">';

                    // For each field get the array of values stored for it
                    foreach ($subfields as $subfield) {

                        // Output the repeater item html
                        $this->output_repeater_item($subfield_i, $subfield, $post_type, $meta_val);

                        // Increment the incrementor
                        $subfield_i++;
                    }

                    // Outputs the delete button
                    $this->output_delete_button();

                    // End div.nc-repeater__item
                    echo '</div>';

                    // End div.nc-repeater__droparea
                    echo '</div>';

                    // Add an empty drop area after the repeater
                    echo $this->get_droparea(true);
                }

            } else { // Nothing saved in the repeater field yet

                $subfields = $metabox['args']['subfields'];

                // Set an incrementor to count each subfield we iterate over
                $subfield_i = 0;

                // Add the drop area that surrounds each repeater item
                echo $this->get_droparea(false);

                // Build the container HTML
                echo '<div class="nc-repeater__item">';

                // For each field get the array of values stored for it
                foreach ($subfields as $subfield) {
                    // Output the repeater item html
                    $this->output_repeater_item($subfield_i, $subfield, $post_type);

                    // Increment the incrementor
                    $subfield_i++;
                }

                // Outputs the delete button
                $this->output_delete_button();

                // End div.nc-repeater__item
                echo '</div>';

                // End div.nc-repeater__droparea
                echo '</div>';

                // Add an empty drop area after the repeater
                echo $this->get_droparea(true);

            }

            // End div.nc-repeater
            echo '</div>';

            // Print out the button row
            ?>
            <div class="nc-repeater__btn-row">
                <button type="button" class="button" id="nc_repeater_btn_add" disabled="false"><?php _e('Add row', 'newsletter_campaign'); ?></button>
            </div>
            <?php

        } else if ($type === 'custom') {

            if ($metabox['args']['custom_type'] === 'builder') {
                include_once('custom-meta/custom-meta-builder.php');
            }

        } else { // Use default type of text
            echo '<input type="text" id="newsletter_campaign_' . $post_type . '_' . $field .'" name="newsletter_campaign_' . $post_type . '_' . $field . '"';
            echo ' value="' . esc_attr( $value ) . '" placeholder="'. esc_attr( $title ) . '">';
        }
    }


    /*
     * Returns the drop area html
     * Param    TRUE: return a closed element
     *          FALSE: return an open element
     */

    private function get_droparea($closed) {
        $returnStr = '<div class="nc-repeater__droparea" style="min-height:50px;background:#eee;border:1px solid darkgray;margin:10px 0;">';
        if ($closed === true) {
            $returnStr .= '</div>';
        }
        return $returnStr;
    }


    /*
     * Outputs the html for the repeater item
     * Param:   incrementor(int)
     *          subfield(str)
     *          post_type(str)
     *          meta_val(str)
     */

    private function output_repeater_item($subfield_i, $subfield, $post_type, $meta_val = null) {

        // If not the first iteration, add a line break
        if ($subfield_i !== 0) {
            echo '<br>';
        }

        if ($subfield['type'] === 'hidden') {
            echo '<input type="hidden" class="nc-repeater__hidden-id" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]" value="';
            if (isset($meta_val['newsletter_campaign_' . $post_type . '_' . $subfield['field']])) {
                echo esc_attr( $meta_val["newsletter_campaign_" . $post_type . "_" . $subfield['field']] );
            } else {
                // Generate a new random string
                $num = 4;
                $strong = true;
                $bytes = openssl_random_pseudo_bytes($num, $strong);
                $hex = bin2hex($bytes);
                echo $hex;
            }
            echo '">';
        } else if ($subfield['type'] === 'textarea') {
            echo '<textarea name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]" placeholder="' . esc_attr( $subfield['title'] ) . '">';
            if (isset($meta_val['newsletter_campaign_' . $post_type . '_' . $subfield['field']])) {
                echo esc_attr($meta_val["newsletter_campaign_" . $post_type . "_" . $subfield['field']]);
            }
            echo '</textarea>';
        } else {
            echo '<input type="text" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]"';
            if (isset($meta_val['newsletter_campaign_' . $post_type . '_' . $subfield['field']])) {
                echo ' value="' . esc_attr( $meta_val["newsletter_campaign_" . $post_type . "_" . $subfield['field']] ) . '"';
            }
            echo ' placeholder="' . esc_attr( $subfield['title'] ) . '">';
        }
    }


    /*
     * Output the send campaign meta box
     */

    public function nc_render_campaign_send_campaign() {
        global $post;

        // Get the array of ids for all the subscriber lists
        $subscriber_list_ids = get_post_meta( $post->ID, '_campaign_subscriber-list-check', true );

        // Prepare the subscriber list output (list which subscriber lists are chosen)
        $subscriber_list_output = '';
        $subscriber_lists_count = count($subscriber_list_ids);
        $i = 0;
        $email_count = 0;
        if ($subscriber_list_ids) {
            foreach ($subscriber_list_ids as $subscriber_list_id) {
                // If $subscriber_list_id is an array, then more than one result is found
                if (is_array($subscriber_list_id)) {
                    $subscriber_list = get_term( $subscriber_list_id['newsletter_campaign_campaign_subscriber-list-check'], 'subscriber_list' );
                } else {
                    $subscriber_list = get_term( $subscriber_list_id, 'subscriber_list' );
                }
                $subscriber_list_output .= '<strong>' . $subscriber_list->name . '</strong>';
                if ($subscriber_lists_count - 2 === $i) {
                    // If not the last iteration add a comma and space
                    $subscriber_list_output .= ' and ';
                } else if ($subscriber_lists_count - 1 !== $i) {
                    // If not the last iteration add a comma and space
                    $subscriber_list_output .= ', ';
                }

                $email_count = $email_count + $subscriber_list->count;

                $i++;
            }
        }


        if ($subscriber_list_ids) {
            echo '<div class="nc-campaign__confirmation" style="display:none;">';
            echo '<p>';
            echo 'You are about to send <strong>' . $post->post_title . '</strong> to ' . $subscriber_list_output . ' ' . sprintf( _n('subscriber list', 'subscriber lists', $subscriber_lists_count, 'newsletter-campaign'), $subscriber_lists_count) . ' ' . sprintf( _n('(which contains <strong>%d</strong> email address)', '(which contain <strong>%d</strong> email addresses)', $email_count, 'newsletter-campaign'), $email_count) . '.<br>';
            echo __('Are you sure you want to send it?', 'newsletter-campaign');
            echo '</p>';
            echo '<button type="button" class="button button-secondary" id="nc_campaign_send_campaign_cancel">' . __('Cancel send');
            echo '</div>';
        } else {
            // No subscriber lists selected or saved
            echo '<div class="nc-campaign__confirmation">';
            echo __('No subscriber lists selected, select one or more subscriber lists and save before sending the campaign.');
            echo '</div>';
        }

        // Set the name of the button so that we can check on page save if we want to send campaign
        echo '<p>';
        echo '<button class="button button-primary" id="nc_campaign_send_campaign" name="nc-campaign__confirmation-true" value="send_true"';
        if (!$subscriber_list_ids) {
            // Prevent send campaign from being clickable if no list selected
            echo ' disabled="disabled"';
        }
        echo '>' . __('Send Campaign') . '</button>';
        echo '</p>';
    }


    /*
     * Outputs the delete button html
     */

    private function output_delete_button() {

        echo '<button type="button" class="button nc-repeater__droparea-delete">' . __('delete', 'newsletter-campaign') . '</button>';

    }


    /*
     * Check if value is in a multidimensional array
     */

    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }
}