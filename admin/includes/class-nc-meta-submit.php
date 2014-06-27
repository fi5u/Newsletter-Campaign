<?php

/*
 * Replace standard submit meta with Newsletter Campaign own
 */

class Newsletter_campaign_submit_meta {
    private $post_type;
    private $title;
    private $save_text;

    public function __construct($post_type, $title, $save_text) {
        $this->post_type = $post_type;
        $this->title = $title;
        $this->save_text = $save_text;

        add_action('admin_menu', array($this, 'remove_publish_box'), 99 );
        add_action('add_meta_boxes', array($this, 'add_publish_box') );
    }

    public function remove_publish_box() {
        remove_meta_box( 'submitdiv', $this->post_type, 'side' );
    }

    public function add_publish_box() {
        $title = sprintf( __( '%s' ), $this->title);
        add_meta_box( 'nc_submitdiv', $title, array($this, 'nc_post_submit_meta_box'), null, 'side', 'core' );
    }

    public function nc_post_submit_meta_box($post, $args = array()) {
        global $post;
        global $action;
        $post_type = $post->post_type;
        $post_type_object = get_post_type_object($post_type);
        $can_publish = current_user_can($post_type_object->cap->publish_posts);
        ?>

        <div class="submitbox" id="submitpost">
            <div id="minor-publishing-actions"></div>
            <div id="misc-publishing-actions">
                <?php
                /* translators: Publish box date format, see http://php.net/date */
                $datef = __( 'M j, Y @ G:i' );
                if ( 0 != $post->ID ) {
                    if ( 'future' == $post->post_status ) { // scheduled for publishing at a future date
                        $stamp = __('Scheduled for: <b>%1$s</b>');
                    } else if ( 'publish' == $post->post_status || 'private' == $post->post_status ) { // already published
                        $stamp = __('Created on: <b>%1$s</b>', 'newsletter-campaign');
                    } else if ( '0000-00-00 00:00:00' == $post->post_date_gmt ) { // draft, 1 or more saves, no date specified
                        $stamp = __('Publish <b>immediately</b>');
                    } else if ( time() < strtotime( $post->post_date_gmt . ' +0000' ) ) { // draft, 1 or more saves, future date specified
                        $stamp = __('Schedule for: <b>%1$s</b>');
                    } else { // draft, 1 or more saves, date specified
                        $stamp = __('Publish on: <b>%1$s</b>');
                    }
                    $date = date_i18n( $datef, strtotime( $post->post_date ) );
                } else { // draft (no saves, and thus no date specified)
                    $stamp = __('Publish <b>immediately</b>');
                    $date = date_i18n( $datef, strtotime( current_time('mysql') ) );
                }

                if ( ! empty( $args['args']['revisions_count'] ) ) :
                    $revisions_to_keep = wp_revisions_to_keep( $post );
                ?>
                <div class="misc-pub-section misc-pub-revisions">
                <?php
                    if ( $revisions_to_keep > 0 && $revisions_to_keep <= $args['args']['revisions_count'] ) {
                        echo '<span title="' . esc_attr( sprintf( __( 'Your site is configured to keep only the last %s revisions.' ),
                            number_format_i18n( $revisions_to_keep ) ) ) . '">';
                        printf( __( 'Revisions: %s' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '+</b>' );
                        echo '</span>';
                    } else {
                        printf( __( 'Revisions: %s' ), '<b>' . number_format_i18n( $args['args']['revisions_count'] ) . '</b>' );
                    }
                ?>
                    <a class="hide-if-no-js" href="<?php echo esc_url( get_edit_post_link( $args['args']['revision_id'] ) ); ?>"><span aria-hidden="true"><?php _ex( 'Browse', 'revisions' ); ?></span> <span class="screen-reader-text"><?php _e( 'Browse revisions' ); ?></span></a>
                </div>
                <?php endif;

                if ( $can_publish ) : // Contributors don't get to choose the date of publish ?>
                <div class="misc-pub-section curtime misc-pub-curtime">
                    <span id="timestamp">
                    <?php printf($stamp, $date); ?></span>
                    <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit date and time' ); ?></span></a>
                    <div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1); ?></div>
                </div><?php // /misc-pub-section ?>
                <?php endif; ?>
                </div>

                <div id="major-publishing-actions">
                <?php
                /**
                 * Fires at the beginning of the publishing actions section of the Publish meta box.
                 *
                 * @since 2.7.0
                 */
                do_action( 'post_submitbox_start' );
                ?>
                <div id="delete-action">
                <?php
                if ( current_user_can( "delete_post", $post->ID ) ) {
                    if ( !EMPTY_TRASH_DAYS )
                        $delete_text = __('Delete Permanently');
                    else
                        $delete_text = __('Move to Trash');
                    ?>
                <a class="submitdelete deletion" href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo $delete_text; ?></a><?php
                } ?>
                </div>

                <div id="publishing-action">
                <span class="spinner"></span>
                <?php
                if ( !in_array( $post->post_status, array('publish', 'future', 'private') ) || 0 == $post->ID ) {
                    if ( $can_publish ) :
                        if ( !empty($post->post_date_gmt) && time() < strtotime( $post->post_date_gmt . ' +0000' ) ) : ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Schedule') ?>" />
                        <?php submit_button( __( 'Schedule' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
                <?php   else : ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Publish') ?>" />
                        <?php submit_button( __( 'Xxx' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
                <?php   endif;
                    else : ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e('Submit for Review') ?>" />
                        <?php submit_button( __( 'Submit for Review' ), 'primary button-large', 'publish', false, array( 'accesskey' => 'p' ) ); ?>
                <?php
                    endif;
                } else { ?>
                        <input name="original_publish" type="hidden" id="original_publish" value="<?php echo $save_text ?>" />
                        <input name="save" type="submit" class="button button-primary button-large" id="publish" accesskey="p" value="<?php printf( __( '%s' ), $this->save_text); ?>" />
                <?php
                } ?>
                </div>
                <div class="clear"></div>
            </div>
        </div><?php
    }

}