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
        if ( ! isset( $_POST['newsletter_campaign_' . $post_type . '_' . $field .'_box_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['newsletter_campaign_' . $post_type . '_' . $field .'_box_nonce'];

        if ( ! wp_verify_nonce( $nonce, 'newsletter_campaign_' . $post_type . '_' . $field . '_box' ) ) {
            return $post_id;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        if ( $post_type == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        // Sanitize the user input.
        $data = sanitize_text_field( $_POST['newsletter_campaign_' . $post_type . '_' . $field] );

        // Update the meta field.
        update_post_meta( $post_id, '_' . $post_type . '_' . $field, $data );
    }

    public function nc_render_meta_box( $post, $metabox ) {
        $post_type = $metabox['args']['post_type'];
        $field = $metabox['args']['field'];
        $title = $metabox['args']['title'];

        wp_nonce_field( 'newsletter_campaign_' . $post_type . '_' . $field . '_box', 'newsletter_campaign_' . $post_type . '_' . $field .'_box_nonce' );

        $value = get_post_meta( $post->ID, '_' . $post_type . '_' . $field, true );

        echo '<label for="newsletter_campaign_' . $post_type . '_' . $field .'">';
        _e( $title, 'newsletter-campaign' );
        echo '</label> ';
        echo '<input type="text" id="newsletter_campaign_' . $post_type . '_' . $field .'" name="newsletter_campaign_' . $post_type . '_' . $field .'"';
                echo ' value="' . esc_attr( $value ) . '">';
    }
}