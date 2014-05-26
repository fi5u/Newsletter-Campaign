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


/*?><table>
<?php

    foreach ($_POST as $key => $value) {
        echo "<tr>";
        echo "<td>";
        echo $key;
        echo "</td>";
        echo "<td>";
        if(is_array($value)) {
            print_r($value);
        } else {
            echo $value;
        }
        echo "</td>";
        echo "</tr>";
    }

?>
</table><?php*/

        if (is_array($field)) {
            // Subfields have been passed
            foreach ($field as $field_item) {
                if ( ! isset( $_POST['newsletter_campaign_' . $post_type . '_multi_box_nonce'] ) ) {
                    return $post_id;
                }

                $nonce = $_POST['newsletter_campaign_' . $post_type . '_multi_box_nonce'];

                if ( ! wp_verify_nonce( $nonce, 'newsletter_campaign_' . $post_type . '_multi_box' ) ) {
                    return $post_id;
                }

                // Sanitize the user input.
                //$data = sanitize_text_field( $_POST['newsletter_campaign_' . $post_type . '_' . $field_item] );
                $data = $_POST['newsletter_campaign_' . $post_type . '_' . $field_item];
                // Update the meta field.
                update_post_meta( $post_id, '_' . $post_type . '_' . $field_item, $data );
            }
        } else {
            // A single field has been passed to save
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

        if (isset($metabox['args']['type'])) {
            $type = $metabox['args']['type'];
        } else {
            $type = 'text';
        }

        if ($type !== 'multi') {
            wp_nonce_field( 'newsletter_campaign_' . $post_type . '_' . $field . '_box', 'newsletter_campaign_' . $post_type . '_' . $field . '_box_nonce' );
            $value = get_post_meta( $post->ID, '_' . $post_type . '_' . $field, true );
        } else {
            wp_nonce_field( 'newsletter_campaign_' . $post_type . '_multi_box', 'newsletter_campaign_' . $post_type . '_multi_box_nonce' );
        }

        if ($type === 'textarea') {
            echo '<textarea id="newsletter_campaign_' . $post_type . '_' . $field .'" name="newsletter_campaign_' . $post_type . '_' . $field . '" placeholder="' . esc_attr( $title ) . '">';
            echo esc_attr( $value );
            echo '</textarea>';
        } else if ($type === 'multi') {
            // temp: needs to be in the format of: '_' . $post_type . '_' . $field_item

            // Store all the multiple values
            $i = 0;
            $value = [];

            $subfields = $metabox['args']['subfields'];
            foreach ($subfields as $subfield) {
                if (get_post_meta( $post->ID, '_' . $post_type . '_' . $subfield['field'], true )) {
                    $value[$i] = get_post_meta( $post->ID, '_' . $post_type . '_' . $subfield['field'], true );
                    $i++;
                }
            }

            if($value) {
                print_r($value);
                // Set up an incrementor to loop through repeatable items
                $repeatable_i = 0;

                foreach ($value as $repeatable_item) {

                    // Set up an incrementor so we know when to add a line break
                    $i = 0;
                    foreach ($subfields as $subfield) {
                        // If not the first iteration, add a line break
                        if ($i !== 0) {
                            echo '<br>';
                        }
                        if ($subfield['type'] === 'textarea') {
                            echo '<textarea id="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]" placeholder="' . esc_attr( $subfield['title'] ) . '">';
                            echo esc_attr( $repeatable_item[$repeatable_i] );
                            echo '</textarea>';
                        } else {
                            echo '<input type="text" id="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]"';
                            echo ' value="' . esc_attr( $repeatable_item[$repeatable_i] ) . '" placeholder="' . esc_attr( $subfield['title'] ) . '">';
                        }
                        $i++;
                    }
                    $repeatable_i++;
                }
            } else {

                // Nothing yet saved in multi
                // Set up an incrementor so we know when to add a line break
                $i = 0;

                $subfields = $metabox['args']['subfields'];
                foreach ($subfields as $subfield) {
                    // If not the first iteration, add a line break
                    if ($i !== 0) {
                        echo '<br>';
                    }
                    if ($subfield['type'] === 'textarea') {
                        echo '<textarea id="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]" placeholder="' . esc_attr( $subfield['title'] ) . '">';
                        echo '</textarea>';
                    } else {
                        echo '<input type="text" id="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '" name="newsletter_campaign_' . $post_type . '_' . $subfield['field'] . '[]"';
                        echo ' placeholder="' . esc_attr( $subfield['title'] ) . '">';
                    }
                    $i++;
                }
            }

        } else {
            echo '<input type="text" id="newsletter_campaign_' . $post_type . '_' . $field .'" name="newsletter_campaign_' . $post_type . '_' . $field . '"';
            echo ' value="' . esc_attr( $value ) . '" placeholder="'. esc_attr( $title ) . '">';
        }
    }
}