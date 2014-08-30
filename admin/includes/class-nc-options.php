<?php
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
            'nc_pluginPage_section',
            __( 'Your section description', 'newsletter-campaign' ),
            array( $this, 'nc_settings_section_callback'),
            'pluginPage'
        );

        add_settings_field(
            'nc_unsubscribe',
            __( 'Unsubscribe field', 'newsletter-campaign' ),
            array( $this, 'text_field_render'),
            'pluginPage',
            'nc_pluginPage_section',
            array(
                'label_for' => 'nc_unsubscribe',
                'name'      => 'nc_unsubscribe'
            )
        );
    }

    public function text_field_render($args) {
        $name = $args['name'];
        $options = get_option( 'nc_settings' );
        ?>
        <input type='text' name='nc_settings[<?php echo $name; ?>]' value='<?php echo $options[$name]; ?>'>
        <?php

    }

    public function nc_settings_section_callback() {
        echo __( 'This section description', 'newsletter-campaign' );
    }


}

new Newsletter_campaign_options;