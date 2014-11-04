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
                                        'title'             => 'span',
                                        'id'                => 'nc-button-span',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_span',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´span´ - is an HTML element name */
                                        'enclosing_text'    => __('span content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('span'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'br',
                                        'id'                => 'nc-button-br',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_br',
                                        'args'              => array_merge(nc_html_attributes('br'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'sub',
                                        'id'                => 'nc-button-sub',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_sub',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´sub´ - is an HTML element name */
                                        'enclosing_text'    => __('sub content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('sub'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'sup',
                                        'id'                => 'nc-button-sup',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_sup',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´sup´ - is an HTML element name */
                                        'enclosing_text'    => __('sup content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('sup'), nc_html_attributes())
                                    )
                                )
                            ),
                            array(
                                'title'     => __('Images', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(
                                    array(
                                        'title'             => 'img',
                                        'id'                => 'nc-button-img',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_img',
                                        'args'              => array_merge(nc_html_attributes('img'), nc_html_attributes())
                                    )
                                )
                            ),
                            array(
                                'title'     => __('Tables', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(
                                    array(
                                        'title'             => 'table',
                                        'id'                => 'nc-button-table',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_table',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´table´ - is an HTML element name */
                                        'enclosing_text'    => __('table content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('table'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'tr',
                                        'id'                => 'nc-button-tr',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_tr',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´tr´ - is an HTML element name */
                                        'enclosing_text'    => __('tr content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('tr'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'th',
                                        'id'                => 'nc-button-th',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_th',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´th´ - is an HTML element name */
                                        'enclosing_text'    => __('th content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('th'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'td',
                                        'id'                => 'nc-button-td',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_td',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´td´ - is an HTML element name */
                                        'enclosing_text'    => __('td content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('td'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'colgroup',
                                        'id'                => 'nc-button-colgroup',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_colgroup',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´colgroup´ - is an HTML element name */
                                        'enclosing_text'    => __('colgroup content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('colgroup'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'col',
                                        'id'                => 'nc-button-col',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_col',
                                        'args'              => array_merge(nc_html_attributes('col'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'caption',
                                        'id'                => 'nc-button-caption',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_caption',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´caption´ - is an HTML element name */
                                        'enclosing_text'    => __('caption content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('caption'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'thead',
                                        'id'                => 'nc-button-thead',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_thead',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´thead´ - is an HTML element name */
                                        'enclosing_text'    => __('thead content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('thead'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'tbody',
                                        'id'                => 'nc-button-tbody',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_tbody',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´tbody´ - is an HTML element name */
                                        'enclosing_text'    => __('tbody content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('tbody'), nc_html_attributes())
                                    ),
                                    array(
                                        'title'             => 'tfoot',
                                        'id'                => 'nc-button-tfoot',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_tfoot',
                                        'enclosing'         => true,
                                        /* translators: do not translate ´tfoot´ - is an HTML element name */
                                        'enclosing_text'    => __('tfoot content', $this->plugin_slug),
                                        'args'              => array_merge(nc_html_attributes('tfoot'), nc_html_attributes())
                                    )
                                )
                            ),
                            array(
                                'title'     => __('Comments', $this->plugin_slug),
                                'class'     => 'nc-button-bar__parent',
                                'children'  => array(
                                    array(
                                        'title'             => 'comment',
                                        'id'                => 'nc-button-comment',
                                        'class'             => 'nc-button-bar__button',
                                        'shortcode'         => 'nc_comment',
                                        'enclosing'         => true,
                                        'enclosing_text'    => __('comment content', $this->plugin_slug)
                                    )
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
                        'title'             => __('Output posts', $this->plugin_slug),
                        'id'                => 'nc-button-posts',
                        'class'             => 'nc-button-bar__button',
                        'shortcode'         => 'nc_posts'
                    ),
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

        wp_localize_script( $this->plugin_slug . '-template-script', 'buttons', nc_get_html_tags());
    }
}

new Newsletter_campaign_shortcode_btns;