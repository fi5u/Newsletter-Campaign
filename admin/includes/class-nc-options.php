<?php
class Newsletter_campaign_options {
    public function __construct() {
        /*add_action( 'current_screen', array($this, 'check_screen') );*/
        add_action( 'admin_init', array($this, 'register_options') );
    }

    /*public function check_screen() {
        // If not in campaign screen, exit
        $screen = get_current_screen();

        if ( 'toplevel_page_newsletter-campaign' === $screen->id ) {
            return true;
        } else {
            return false;
        }
    }*/


    public function field_test() {
        echo 'option:' . get_option('field_test');
        echo '<input type="text" name="field_test" id="field_test" value="'. get_option('field_test', 'ooo') .'">';
    }


    /**
     * Echos out the output for each option section
     */
    public function section_callback($arg) {
        echo '<h1>' . $arg['title'] . '</h1>';
    }


    public function register_options() {
        // All callbacks must be valid names of functions, even if provided functions are blank
        add_settings_section( 'section_unsubscribe', __('Unsubscribe', 'newsletter-campaign'), array($this, 'section_callback'), 'newsletter-campaign' );
        add_settings_field( 'field_test', __('Test', 'newsletter-campaign'), array($this, 'field_test'), 'section_unsubscribe', 'section_unsubscribe', array( 'label_for' => 'field_test' ) );

        register_setting( 'field_test', 'field_test', 'sanitize_callback' );
    }
}

new Newsletter_campaign_options;