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

        $options = get_option( 'nc_settings' );

        $shortcode_btns = apply_filters( 'newsletter_campaign_shortcode_btns', array(
            array(
                'title'             => __('Email functionality', $this->plugin_slug),
                'class'             => 'nc-button-bar__parent',
                'instance_include'  => 'newsletter_campaign_template_base-html',
                'children'          => array(
                    array(
                        'title'     => __('View in browser', $this->plugin_slug),
                        'id'        => 'nc-button-view-browser',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_browser_link'
                    ),
                    array(
                        'title'             => __('Unsubscribe link', $this->plugin_slug),
                        'id'                => 'nc-button-unsubscribe',
                        'class'             => 'nc-button-bar__button',
                        'shortcode'         => 'nc_unsubscribe_link',
                        'enclosing'         => true,
                        'enclosing_text'    => __('Unsubscribe text')
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
            ),
            array(
                'title'             => __('Post', $this->plugin_slug),
                'class'             => 'nc-button-bar__parent',
                'instance_exclude'  => 'newsletter_campaign_template_base-html',
                'children'          => array(
                    array(
                        'title'     => __('Post title', $this->plugin_slug),
                        'id'        => 'nc-button-post-title',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_post_title'
                    ),
                    array(
                        'title'     => __('Post body', $this->plugin_slug),
                        'id'        => 'nc-button-post-body',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_post_body'
                    ),
                    array(
                        'title'     => __('Featured image', $this->plugin_slug),
                        'id'        => 'nc-button-feat-img',
                        'class'     => 'nc-button-bar__button',
                        'shortcode' => 'nc_feat_image',
                        'args'      => array(
                            array(
                                'name'  => 'nc-shortcode-arg-feat-img-size',
                                'arg'   => 'size',
                                'title' => __('Size', $this->plugin_slug),
                                'type'  => 'select',
                                'values'=>  get_intermediate_image_sizes()
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-feat-img-width',
                                'arg'   => 'width',
                                'title' => __('Width', $this->plugin_slug)
                            ),
                            array(
                                'name'  => 'nc-shortcode-arg-feat-img-height',
                                'arg'   => 'height',
                                'title' => __('Height', $this->plugin_slug)
                            ),
                        )
                    ),
                    array(
                        'title'             => __('Post Divider', $this->plugin_slug),
                        'id'                => 'nc-button-divider',
                        'class'             => 'nc-button-bar__button',
                        'shortcode'         => $options['nc_shortcode_divider']
                    )
                )
            )
        ));

        wp_localize_script( $this->plugin_slug . '-template-script', 'buttons', $shortcode_btns);
    }
}

new Newsletter_campaign_shortcode_btns;