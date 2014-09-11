<?php

class Newsletter_campaign_shortcode_btns {

    public function __construct() {
        $plugin = NewsletterCampaign::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action( 'admin_enqueue_scripts', array( $this, 'define_shortcode_btns' ), 99 );
    }

    public function define_shortcode_btns() {

        // Ensure that we're on the template screen
        $screen = get_current_screen();
        if ( 'template' !== $screen->post_type ) {
            return;
        }

        $shortcode_btns = apply_filters( 'newsletter_campaign_shortcode_btns', array(
            array(
                'title'     => __('Email functionality', $this->plugin_slug),
                'class'     => 'nc-button-bar__parent',
                'children'  => array(
                    array(
                        'title'     => __('View in browser', $this->plugin_slug),
                        'id'        => 'nc-button-view-browser',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_browser_link'
                    ),
                    array(
                        'title'     => __('Unsubscribe link', $this->plugin_slug),
                        'id'        => 'nc-button-unsubscribe',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_unsubscribe_link'
                    )
                )
            ),
            array(
                'title'     => __('Personal fields', $this->plugin_slug),
                'class'     => 'nc-button-bar__parent',
                'children'  => array(
                    array(
                        'title'     => __('Name', $this->plugin_slug),
                        'id'        => 'nc-button-personal-name',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_name',
                        'args'      => array(
                            array(
                                'name'  => 'nc-shortcode-arg-name-before',
                                'arg'   => 'before',
                                'title' => __('Before', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-name-after',
                                'arg'   => 'after',
                                'title' => __('After', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-name-noval',
                                'arg'   => 'noval',
                                'title' => __('If no value', $this->plugin_slug)
                            )
                        )
                    ),
                    array(
                        'title'     => __('Email', $this->plugin_slug),
                        'id'        => 'nc-button-personal-email',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_email',
                        'args'      => array(
                            array(
                                'name'  => 'nc-shortcode-arg-email-before',
                                'arg'   => 'before',
                                'title' => __('Before', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-email-after',
                                'arg'   => 'after',
                                'title' => __('After', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-email-noval',
                                'arg'   => 'noval',
                                'title' => __('If no value', $this->plugin_slug)
                            )
                        )
                    ),
                    array(
                        'title'     => __('Extra info', $this->plugin_slug),
                        'id'        => 'nc-button-personal-extra',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_extra',
                        'args'      => array(
                            array(
                                'name'  => 'nc-shortcode-arg-extra-before',
                                'arg'   => 'before',
                                'title' => __('Before', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-extra-after',
                                'arg'   => 'after',
                                'title' => __('After', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-extra-noval',
                                'arg'   => 'noval',
                                'title' => __('If no value', $this->plugin_slug)
                            )
                        )
                    )
                )
            )
        ));

        wp_localize_script( $this->plugin_slug . '-template-script', 'buttons', $shortcode_btns);
    }
}

new Newsletter_campaign_shortcode_btns;