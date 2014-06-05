<?php

/**
 * Add, generate and save meta boxes
 */
class Newsletter_campaign_meta_box_generator {
    public function nc_add_meta_box( $id, $title, $cb, $post_type, $context, $priority, $callback_args ) {
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

    public function nc_save_meta_box( $post_id, $post_type, $field ) {
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

        if (is_array($field)) {
            // Subfields have been passed

            // Check the nonce for the repeatable fields
            if ( ! isset( $_POST['newsletter_campaign_' . $post_type . '_multi_box_nonce'] ) ) {
                return $post_id;
            }

            $nonce = $_POST['newsletter_campaign_' . $post_type . '_multi_box_nonce'];

            if ( ! wp_verify_nonce( $nonce, 'newsletter_campaign_' . $post_type . '_multi_box' ) ) {
                return $post_id;
            }

            // Set an array to hold the repeatable data
            $repeatable_arr = array();

            foreach ($field as $field_item) {

                $count = count($_POST['newsletter_campaign_' . $post_type . '_' . $field_item]);

                // Loop through each of the repeatable items, adding its data to the array
                for ( $i = 0; $i < $count; $i++ ) {

                    // Don't save if empty
                    if ( $_POST['newsletter_campaign_' . $post_type . '_' . $field_item][$i] != '' ) {
                        // Sanitize the user input.
                        $data = sanitize_text_field( $_POST['newsletter_campaign_' . $post_type . '_' . $field_item][$i] );
                        $repeatable_arr[$i]['newsletter_campaign_' . $post_type . '_' . $field_item] = $data;
                    }
                }
            }

            // Update the meta field
            update_post_meta( $post_id, '_' . $post_type . '_multi', $repeatable_arr );

        } else {
            // A single field has been passed to save

            // Check the nonce field
            if ( ! isset( $_POST['newsletter_campaign_' . $post_type . '_' . $field .'_box_nonce'] ) ) {
                return $post_id;
            }

            $nonce = $_POST['newsletter_campaign_' . $post_type . '_' . $field .'_box_nonce'];

            if ( ! wp_verify_nonce( $nonce, 'newsletter_campaign_' . $post_type . '_' . $field . '_box' ) ) {
                return $post_id;
            }

            // Sanitize the user input.
            $data = sanitize_text_field( $_POST['newsletter_campaign_' . $post_type . '_' . $field] );

            // Update the meta field.
            update_post_meta( $post_id, '_' . $post_type . '_' . $field, $data );
        }
    }

    public function nc_render_meta_box( $post, $metabox ) {
        $post_type = $metabox['args']['post_type'];
        $field = $metabox['args']['field'];
        $title = $metabox['args']['title'];
        $type = isset($metabox['args']['type']) ? $metabox['args']['type'] : 'text'; // Defaults to text

        if ($type !== 'multi' && $type !== 'custom') {
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
        if ($subfield['type'] === 'textarea') {
            echo '<textarea name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]" placeholder="' . esc_attr( $subfield['title'] ) . '">';
            if($meta_val) {
                echo esc_attr( $meta_val["newsletter_campaign_" . $post_type . "_" . $subfield['field']] );
            }
            echo '</textarea>';
        } else {
            echo '<input type="text" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]"';
            if($meta_val) {
                echo ' value="' . esc_attr( $meta_val["newsletter_campaign_" . $post_type . "_" . $subfield['field']] ) . '"';
            }
            echo ' placeholder="' . esc_attr( $subfield['title'] ) . '">';
        }
    }


    /*
     * Outputs the delete button html
     */

    private function output_delete_button() {

        echo '<button type="button" class="button nc-repeater__droparea-delete">' . __('delete', 'newsletter-campaign') . '</button>';

    }
}