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

        $subscriber_list_cat_args = apply_filters( 'newsletter_campaign_subscriber_list_cat_args', array(
                'taxonomy'  => 'subscriber_list'
            )
        );

        // Fetch the array of subscriber lists, prepending with 'all lists' option
        $subscriber_list_cats = array_merge(array(array('name' => __('All lists'), 'slug' => 'nc_all')), get_categories($subscriber_list_cat_args));

        $shortcode_btns = apply_filters( 'newsletter_campaign_shortcode_btns', array(
            array(
                'title'             => __('HTML', $this->plugin_slug),
                'class'             => 'nc-button-bar__parent',
                'children'          => array(
                    array(
                        'title'             => __('Document structure', $this->plugin_slug),
                        'class'             => 'nc-button-bar__parent',
                        'instance_include'  => 'newsletter_campaign_template_base-html',
                        'children'          => array(
                            array(
                                'title'     => 'Doctype',
                                'id'        => 'nc-button-doctype',
                                'class'     => 'nc-button-bar__button',
                                'shortcode' => 'nc_doctype',
                                'args'      => array(
                                    array(
                                        'name'  => 'nc-shortcode-arg-html-doctype',
                                        'arg'   => 'doctype',
                                        'title' => 'Doctype',
                                        'type'  => 'select',
                                        'values'=>  array(
                                            array(
                                                'name' => 'HTML5',
                                                'value'=> 'html5'
                                            ),
                                            array(
                                                'name' => 'HTML 4.01 Strict',
                                                'value'=> 'html-4-01-strict'
                                            ),
                                            array(
                                                'name' => 'HTML 4.01 Transitional',
                                                'value'=> 'html-4-01-transitional'
                                            ),
                                            array(
                                                'name' => 'XHTML 1.0 Strict',
                                                'value'=> 'xhtml-1-strict'
                                            ),
                                            array(
                                                'name' => 'XHTML 1.0 Transitional',
                                                'value'=> 'xhtml-1-transitional'
                                            ),
                                        ),
                                        'key'   => 'name',
                                        'value' => 'value'
                                    )
                                )
                            ),
                            array(
                                'title'             => 'HTML',
                                'id'                => 'nc-button-html',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_html',
                                'enclosing'         => true,
                                'enclosing_text'    => __('HTML content', $this->plugin_slug),
                                'args'              => array_merge(nc_html_attributes('html'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Head',
                                'id'                => 'nc-button-head',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_head',
                                'enclosing'         => true,
                                /* translators: do not translate ´Head´ - is an HTML element name */
                                'enclosing_text'    => __('Head content', $this->plugin_slug),
                                'args'              => array_merge(nc_html_attributes('head'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Body',
                                'id'                => 'nc-button-body',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_body',
                                'enclosing'         => true,
                                /* translators: do not translate ´Body´ - is an HTML element name */
                                'enclosing_text'    => __('Body content', $this->plugin_slug),
                                'args'              => array_merge(nc_html_attributes('body'), nc_html_attributes())
                            )
                        )
                    ),
                    array(
                        /* translators: do not translate ´Head´ - is an HTML element name */
                        'title'             => __('Head elements', $this->plugin_slug),
                        'class'             => 'nc-button-bar__parent',
                        'instance_include'  => 'newsletter_campaign_template_base-html',
                        'children'          => array(
                            array(
                                'title'             => 'Base',
                                'id'                => 'nc-button-base',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_base',
                                'args'              => array_merge(nc_html_attributes('base'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Link',
                                'id'                => 'nc-button-link',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_link',
                                'args'              => array_merge(nc_html_attributes('link'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Meta',
                                'id'                => 'nc-button-meta',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_meta',
                                'args'              => array_merge(nc_html_attributes('meta'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Style',
                                'id'                => 'nc-button-style',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_style',
                                'enclosing'         => true,
                                /* translators: do not translate ´Style´ - is an HTML element name */
                                'enclosing_text'    => __('Style content', $this->plugin_slug),
                                'args'              => array_merge(nc_html_attributes('style'), nc_html_attributes())
                            ),
                            array(
                                'title'             => 'Title',
                                'id'                => 'nc-button-title',
                                'class'             => 'nc-button-bar__button',
                                'shortcode'         => 'nc_title',
                                'enclosing'         => true,
                                /* translators: do not translate ´Title´ - is an HTML element name */
                                'enclosing_text'    => __('Title content', $this->plugin_slug)
                            )
                        )
                    ),
                    array(
                        /* translators: do not translate ´Body´ - is an HTML element name */
                        'title'             => __('Body elements', $this->plugin_slug),
                        'class'             => 'nc-button-bar__parent',
                        'children'          => array(
                            array(
                                'title'     => __('Block elements', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(
                                    array(
                                        'title'             => 'p',
                                        'id'                => 'nc-button-p',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_p',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´p´ - is an HTML element name */
                                        'enclosing_text'    => __('p content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('p'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h1',
                                        'id'                => 'nc-button-h1',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h1',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h1´ - is an HTML element name */
                                        'enclosing_text'    => __('h1 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h1'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h2',
                                        'id'                => 'nc-button-h2',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h2',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h2´ - is an HTML element name */
                                        'enclosing_text'    => __('h2 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h2'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h3',
                                        'id'                => 'nc-button-h3',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h3',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h3´ - is an HTML element name */
                                        'enclosing_text'    => __('h3 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h3'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h4',
                                        'id'                => 'nc-button-h4',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h4',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h4´ - is an HTML element name */
                                        'enclosing_text'    => __('h4 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h4'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h5',
                                        'id'                => 'nc-button-h5',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h5',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h5´ - is an HTML element name */
                                        'enclosing_text'    => __('h5 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h5'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'h6',
                                        'id'                => 'nc-button-h6',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_h6',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´h6´ - is an HTML element name */
                                        'enclosing_text'    => __('h6 content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('h6'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'dl',
                                        'id'                => 'nc-button-dl',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_dl',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´dl´ - is an HTML element name */
                                        'enclosing_text'    => __('dl content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('dl'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'dt',
                                        'id'                => 'nc-button-dt',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_dt',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´dt´ - is an HTML element name */
                                        'enclosing_text'    => __('dt content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('dt'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'dd',
                                        'id'                => 'nc-button-dd',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_dd',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´dd´ - is an HTML element name */
                                        'enclosing_text'    => __('dd content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('dd'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'ol',
                                        'id'                => 'nc-button-ol',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_ol',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´ol´ - is an HTML element name */
                                        'enclosing_text'    => __('ol content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('ol'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'ul',
                                        'id'                => 'nc-button-ul',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_ul',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´ul´ - is an HTML element name */
                                        'enclosing_text'    => __('ul content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('ul'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'li',
                                        'id'                => 'nc-button-li',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_li',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´li´ - is an HTML element name */
                                        'enclosing_text'    => __('li content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('li'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'address',
                                        'id'                => 'nc-button-address',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_address',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´address´ - is an HTML element name */
                                        'enclosing_text'    => __('address content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('address'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'blockquote',
                                        'id'                => 'nc-button-blockquote',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_blockquote',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´blockquote´ - is an HTML element name */
                                        'enclosing_text'    => __('blockquote content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('blockquote'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'del',
                                        'id'                => 'nc-button-del',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_del',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´del´ - is an HTML element name */
                                        'enclosing_text'    => __('del content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('del'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'div',
                                        'id'                => 'nc-button-div',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_div',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´div´ - is an HTML element name */
                                        'enclosing_text'    => __('div content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('div'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'hr',
                                        'id'                => 'nc-button-hr',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_hr',
                                        'args'              => array_merge(nc_html_attributes('hr'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'ins',
                                        'id'                => 'nc-button-ins',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_ins',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´ins´ - is an HTML element name */
                                        'enclosing_text'    => __('ins content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('ins'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'pre',
                                        'id'                => 'nc-button-pre',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_pre',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´pre´ - is an HTML element name */
                                        'enclosing_text'    => __('pre content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('pre'), nc_html_attributes())
                                    )
                                )
                            ),
                            array(
                                'title'     => __('Inline elements', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(
                                    array(
                                        'title'             => 'a',
                                        'id'                => 'nc-button-a',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_a',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´a´ - is an HTML element name */
                                        'enclosing_text'    => __('a content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('a'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'abbr',
                                        'id'                => 'nc-button-abbr',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_abbr',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´abbr´ - is an HTML element name */
                                        'enclosing_text'    => __('abbr content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('abbr'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'dfn',
                                        'id'                => 'nc-button-dfn',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_dfn',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´dfn´ - is an HTML element name */
                                        'enclosing_text'    => __('dfn content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('dfn'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'em',
                                        'id'                => 'nc-button-em',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_em',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´em´ - is an HTML element name */
                                        'enclosing_text'    => __('em content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('em'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'strong',
                                        'id'                => 'nc-button-strong',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_strong',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´strong´ - is an HTML element name */
                                        'enclosing_text'    => __('strong content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('strong'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'code',
                                        'id'                => 'nc-button-code',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_code',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´code´ - is an HTML element name */
                                        'enclosing_text'    => __('code content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('code'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'samp',
                                        'id'                => 'nc-button-samp',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_samp',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´samp´ - is an HTML element name */
                                        'enclosing_text'    => __('samp content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('samp'), nc_html_attributes())
                                    )
                                )
                            ),
                            array(
                                'title'     => __('Images and objects', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(

                                )
                            ),
                            array(
                                'title'     => __('Forms', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(

                                )
                            ),
                            array(
                                'title'     => __('Tables', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(

                                )
                            )
                        )
                    )
                )
            ),
            array(
                'title'             => __('Email functionality', $this->plugin_slug),
                'class'             => 'nc-button-bar__parent',
                'instance_include'  => 'newsletter_campaign_template_base-html',
                'children'          => array(
                    array(
                        'title'             => __('View in browser', $this->plugin_slug),
                        'id'                => 'nc-button-view-browser',
                        'class'             => 'nc-button-bar__button',
                        'shortcode'         => 'nc_browser_link',
                        'enclosing'         => true,
                        'enclosing_text'    => __('View in browser', $this->plugin_slug),
                    ),
                    array(
                        'title'             => __('Unsubscribe link', $this->plugin_slug),
                        'id'                => 'nc-button-unsubscribe',
                        'class'             => 'nc-button-bar__button',
                        'shortcode'         => 'nc_unsubscribe_link',
                        'enclosing'         => true,
                        'enclosing_text'    => __('Unsubscribe text', $this->plugin_slug),
                        'args'              => array(
                            array(
                                'name'  => 'nc-shortcode-arg-unsubscribe-list',
                                'arg'   => 'list',
                                'title' => __('Subscriber list', $this->plugin_slug),
                                'type'  => 'select',
                                'values'=>  $subscriber_list_cats,
                                'key'   => 'name',
                                'value' => 'slug'
                            )
                        )
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