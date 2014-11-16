<?php

$nc_plugin = NewsletterCampaign::get_instance();
$nc_plugin_slug = $nc_plugin->get_plugin_slug();

class Newsletter_campaign_options {
    public function __construct() {
        add_action( 'admin_init', array($this, 'settings_init') );
    }

    /**
     * Register settings, add sections and add fields
     */
    public function settings_init() {

        register_setting( 'pluginPage', 'nc_settings' );

        add_settings_section(
            'nc_unsubscribe_section',
            __( 'Unsubscription', 'newsletter-campaign' ),
            array( $this, 'nc_settings_section_callback'),
            'pluginPage'
        );

            add_settings_field(
                'nc_unsubscribe',
                __( 'Unsubscribe page', 'newsletter-campaign' ),
                array($this, 'dropdown_pages_render'),
                'pluginPage',
                'nc_unsubscribe_section',
                array(
                    'label_for' => 'nc_unsubscribe',
                    'name'      => 'nc_unsubscribe'
                )
            );

        add_settings_section(
            'nc_shortcodes_section',
            __( 'Shortcodes', 'newsletter-campaign' ),
            array( $this, 'nc_settings_section_callback' ),
            'pluginPage'
        );

            add_settings_field(
                'nc_shortcode_divider',
                __( 'Shortcode divider text', 'newsletter-campaign' ),
                array($this, 'text_field_render'),
                'pluginPage',
                'nc_shortcodes_section',
                array(
                    'label_for' => 'nc_shortcode_divider',
                    'name'      => 'nc_shortcode_divider'
                )
            );
    }

    public function text_field_render($args) {
        $name = $args['name'];
        $options = get_option( 'nc_settings' );
        ?>
        <input type="text" name="nc_settings[<?php echo $name; ?>]" id="<?php echo $name; ?>" value="<?php echo $options[$name]; ?>">
        <?php
    }


    public function dropdown_pages_render($args) {
        $name = $args['name'];
        $options = get_option( 'nc_settings' );
        $wp_dropdown_args = array(
            'selected'          => $options[$name],
            'name'              => 'nc_settings[' . $name . ']',
            'id'                => $name,
            'show_option_none'  => __('Default text', $nc_plugin_slug),
            'show_option_value' => ''
        );
        wp_dropdown_pages($wp_dropdown_args);
    }



    public function nc_settings_section_callback($arg) {
        echo $arg['title'];
    }


}

new Newsletter_campaign_options;