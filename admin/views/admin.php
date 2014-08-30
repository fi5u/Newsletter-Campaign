<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   NewsletterCampaign
 * @author    Fisu <tommybfisher@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014
 */


if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'newsletter-campaign'));
}

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <form method="POST" action="options.php">
        <?php
        /*settings_fields( 'field_test' );
        do_settings_sections( 'section_unsubscribe' );*/

        settings_fields( 'pluginPage' );
        do_settings_sections( 'pluginPage' );

        /*$unsubscribed_page_value = get_option('newsletter_campaign_unsubscribed_page');
        $unsubscribe_page_args = array(
            'name'  => 'newsletter_campaign_options_unsubscribed'
        );

        if ($unsubscribed_page_value) {
            $unsubscribe_page_args['selected'] = $unsubscribed_page_value;
        }

        wp_dropdown_pages($unsubscribe_page_args);
*/

        submit_button( __('Save options', 'newsletter-campaign'), 'primary', 'newsletter_campaign_save_options', false );
        ?>
    </form>

</div>
