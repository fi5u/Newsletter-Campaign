<?php
/**
 * Newsletter Campaign
 *
 * @package   NewsletterCampaignAdmin
 * @author    Fisu <tommybfisher@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-newsletter-campaign.php`
 *
 * @package NewsletterCampaignAdmin
 * @author  Fisu <tommybfisher@gmail.com>
 */
class NewsletterCampaignAdmin {

	/**
	 * Instance of this class.
	 *
	 * @since    0.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    0.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     0.0.0
	 */
	private function __construct() {
		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		$plugin = NewsletterCampaign::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ), 9 );
        // Add the options page and menu items for after custom post types
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu_after' ), 11 );

        /*add_action( 'admin_init', array( $this, 'settings_init') );*/

        // Include required files
        $this->includes();

        // Create meta boxes
        $this->create_meta_boxes();

        // Replace submit meta boxes
        $this->replace_submit_meta_boxes();

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

        // Override output messages
        add_action( 'current_screen', array($this, 'output_overrides') );

        // Set some meta boxes to hidden
        add_filter('default_hidden_meta_boxes', array($this, 'hide_meta_boxes'), 10, 2);

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		add_action( '@TODO', array( $this, 'action_method_name' ) );
		add_filter( '@TODO', array( $this, 'filter_method_name' ) );
	}


	/**
	 * Return an instance of this class.
	 *
	 * @since     0.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     0.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
        // TODO: find a working way conditionally load the css
		/*if ( $this->plugin_screen_hook_suffix == $screen->id ) {*/
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), NewsletterCampaign::VERSION );
		/*}*/

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     0.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

        $drag_drop_deps = array(
            'jquery',
            'jquery-ui-core',
            'jquery-ui-widget',
            'jquery-ui-mouse',
            'jquery-ui-draggable',
            'jquery-ui-droppable');

		$screen = get_current_screen();

		if ( 'template' === $screen->post_type ) {
            wp_enqueue_script( $this->plugin_slug . '-codemirror-script', plugins_url( 'assets/js/codemirror.js', __FILE__ ), array(), NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-codemirror-xml', plugins_url( 'assets/js/xml.js', __FILE__ ), array(), NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-codemirror-javascript', plugins_url( 'assets/js/javascript.js', __FILE__ ), array(), NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-codemirror-css', plugins_url( 'assets/js/css.js', __FILE__ ), array(), NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-codemirror-html', plugins_url( 'assets/js/htmlmixed.js', __FILE__ ), array(
                $this->plugin_slug . '-codemirror-xml',
                $this->plugin_slug . '-codemirror-javascript',
                $this->plugin_slug . '-codemirror-css'
            ), NewsletterCampaign::VERSION, true );

            wp_enqueue_style( $this->plugin_slug . '-codemirror-style', plugins_url( 'assets/css/codemirror.css', __FILE__ ), array(), NewsletterCampaign::VERSION );


            wp_enqueue_script( $this->plugin_slug . '-repeater-script', plugins_url( 'assets/js/repeater.js', __FILE__ ), $drag_drop_deps, NewsletterCampaign::VERSION, true );

            wp_enqueue_script( $this->plugin_slug . '-template-script', plugins_url( 'assets/js/template.js', __FILE__ ), array(
                'jquery',
                $this->plugin_slug . '-codemirror-script',
                $this->plugin_slug . '-codemirror-html'
            ), NewsletterCampaign::VERSION, true );

            $codemirror_args = apply_filters( 'newsletter_campaign_codemirror_args', array(
                'lineNumbers'   => true,
                'mode'          => 'htmlmixed'
            ));

            $template_translations = apply_filters( 'newsletter_campaign_template_translations', array(
                'optional'  => __('Optional', 'newsletter-campaign'),
                'insert'    => __('Insert', 'newsletter-campaign'),
                'cancel'    => __('Cancel', 'newsletter-campaign')
            ));

            $shortcode_btns = apply_filters( 'newsletter_campaign_shortcode_btns', array(
                array(
                    'title'     => __('Email functionality', 'newsletter-campaign'),
                    'class'     => 'nc-button-bar__parent',
                    'children'  => array(
                        array(
                            'title'     => __('View in browser', 'newsletter-campaign'),
                            'id'        => 'nc-button-view-browser',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_browser_link'
                        ),
                        array(
                            'title'     => __('Unsubscribe link', 'newsletter-campaign'),
                            'id'        => 'nc-button-unsubscribe',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_unsubscribe_link'
                        )
                    )
                ),
                array(
                    'title'     => __('Personal fields', 'newsletter-campaign'),
                    'class'     => 'nc-button-bar__parent',
                    'children'  => array(
                        array(
                            'title'     => __('Name', 'newsletter-campaign'),
                            'id'        => 'nc-button-personal-name',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_name',
                            'args'      => array(
                                array(
                                    'name'  => 'nc-shortcode-arg-name-before',
                                    'arg'   => 'before',
                                    'title' => __('Before', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-name-after',
                                    'arg'   => 'after',
                                    'title' => __('After', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-name-noval',
                                    'arg'   => 'noval',
                                    'title' => __('If no value', 'newsletter-campaign')
                                )
                            )
                        ),
                        array(
                            'title'     => __('Email', 'newsletter-campaign'),
                            'id'        => 'nc-button-personal-email',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_email',
                            'args'      => array(
                                array(
                                    'name'  => 'nc-shortcode-arg-email-before',
                                    'arg'   => 'before',
                                    'title' => __('Before', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-email-after',
                                    'arg'   => 'after',
                                    'title' => __('After', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-email-noval',
                                    'arg'   => 'noval',
                                    'title' => __('If no value', 'newsletter-campaign')
                                )
                            )
                        ),
                        array(
                            'title'     => __('Extra info', 'newsletter-campaign'),
                            'id'        => 'nc-button-personal-extra',
                            'class'     => 'nc-button-bar__button',
                            'shortcode' => 'nc_extra',
                            'args'      => array(
                                array(
                                    'name'  => 'nc-shortcode-arg-extra-before',
                                    'arg'   => 'before',
                                    'title' => __('Before', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-extra-after',
                                    'arg'   => 'after',
                                    'title' => __('After', 'newsletter-campaign')
                                ),
                                array(
                                    'name'  => 'nc-shortcode-arg-extra-noval',
                                    'arg'   => 'noval',
                                    'title' => __('If no value', 'newsletter-campaign')
                                )
                            )
                        )
                    )
                )
            ));

            wp_localize_script( $this->plugin_slug . '-template-script', 'codemirrorArgs', $codemirror_args);
            wp_localize_script( $this->plugin_slug . '-template-script', 'buttons', $shortcode_btns);
            wp_localize_script( $this->plugin_slug . '-template-script', 'translation', $template_translations);


            wp_enqueue_style( $this->plugin_slug . '-button-bar', plugins_url( 'assets/css/button-bar.css', __FILE__ ), array(), NewsletterCampaign::VERSION );
        }

        if ( 'campaign' === $screen->post_type ) {

            wp_enqueue_script( $this->plugin_slug . '-builder-script', plugins_url( 'assets/js/builder.js', __FILE__ ), $drag_drop_deps, NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-campaign-script', plugins_url( 'assets/js/campaign.js', __FILE__ ), array('jquery'), NewsletterCampaign::VERSION, true );
            // in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script( $this->plugin_slug . '-campaign-script', 'nc_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'please_save' => __('Please save the campaign before proceeding', 'newsletter-campaign') ) );

        }

        // Load JS to ensure the correct menu is visible under subscriber edit tags
        if ('edit-tags' === $screen->base && 'subscriber' === $screen->post_type) {
            wp_enqueue_script( $this->plugin_slug . '-subscriber-edit-tags-script', plugins_url( 'assets/js/subscriber-edit-tags.js', __FILE__ ), array('jquery'), NewsletterCampaign::VERSION, true );
        }

        // Load on all Newsletter Campaign pages, including dashboard
        if ( 'campaign' === $screen->post_type ||
            'template' === $screen->post_type ||
            'subscriber' === $screen->post_type ||
            'toplevel_page_newsletter-campaign' === $screen->base) {
        	wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array('jquery'), NewsletterCampaign::VERSION, true );
        }

	}

	/**
	 * Register the administration menus for this plugin into the WordPress Dashboard menu
	 *
	 * @since    0.0.0
	 */
	public function add_plugin_admin_menu() {
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Newsletter Campaign', $this->plugin_slug ), // Page title
			__( 'Newsletter', $this->plugin_slug ),          // Menu title
			'manage_options',                                // Capability
			$this->plugin_slug,                              // Menu slug
			array( $this, 'display_plugin_admin_page' ),     // Function
            'dashicons-email-alt'                            // Icon url
		);

        $this->plugin_screen_dashboard = add_submenu_page(
            $this->plugin_slug, // Parent slug
            __( 'Dashboard', $this->plugin_slug ),           // Page title
            __( 'Dashboard', $this->plugin_slug ),           // Menu title
            'manage_options',                                // Capability
            $this->plugin_slug,                              // Menu slug
            array( $this, 'display_plugin_admin_page' )      // Function
        );

	}


    /**
     * Register admin menus for after the custom post type edit screens
     *
     * @since    0.0.0
     */
    public function add_plugin_admin_menu_after() {

        $this->plugin_screen_dashboard = add_submenu_page(
            $this->plugin_slug, // Parent slug
            __( 'Subscriber Lists', $this->plugin_slug ), // Page title
            __( 'Subscriber Lists', $this->plugin_slug ), // Menu title
            'manage_options', // Capability
            'edit-tags.php?taxonomy=subscriber_list&post_type=subscriber'
        );
    }



	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}


	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}


	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    0.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}


	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    0.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}


    /**
     * Include required core files used in admin
     *
     * @since    0.0.0
     */
    public function includes() {

        include_once( 'includes/class-nc-meta-boxes.php' );     // Register metaboxes
        include_once( 'includes/class-nc-meta-submit.php' );    // Replace the submit metabox
        include_once( 'includes/class-nc-admin-filters.php' );  // Filter admin output
        include_once( 'includes/class-nc-campaign.php' );       // Format the campaign admin
        include_once( 'includes/class-nc-shortcodes.php' );     // Register the shortcodes
        include_once( 'includes/class-nc-send-campaign.php' );  // Send the selected campaign
        include_once( 'includes/class-nc-options.php' );        // General options page
    }


    /**
     * Create new meta boxes from the class
     *
     * @since    0.0.0
     */
    private static function replace_submit_meta_boxes() {
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('campaign', __('Overview', 'newsletter-campaign'), __('Save campaign', 'newsletter-campaign'));
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('template', __('Overview', 'newsletter-campaign'), __('Save template', 'newsletter-campaign'));
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('subscriber', __('Overview', 'newsletter-campaign'), __('Save subscriber', 'newsletter-campaign'));
    }


    /*
     * Override output messages
     */
    public function output_overrides() {
        // Get current screen
        $screen = get_current_screen();

        switch ($screen->post_type) {
            case 'campaign':
                add_filter('post_updated_messages', array($this,'set_campaign_messages'));
                break;
            case 'template':
                add_filter('post_updated_messages', array($this,'set_template_messages'));
                break;
            case 'subscriber':
                add_filter('post_updated_messages', array($this,'set_subscriber_messages'));
                break;
        }
    }


    /*
     * Set output messages for Campaign
     */
    public function set_campaign_messages($messages) {
        // Do not display 'view post' link
        $messages['post'][1] = __('Campaign updated.', 'newsletter-campaign');
        // Remove 'Post saved' message when mail sent
        $messages['post'][4] = '';
        // Override the message for first save
        $messages['post'][6] = __('Campaign saved.', 'newsletter-campaign');

        return $messages;
    }


    /*
     * Set output messages for Template
     */
    public function set_template_messages($messages) {
        // Do not display 'view post' link
        $messages['post'][1] = __('Template updated.', 'newsletter-campaign');
        // Override the message for first save
        $messages['post'][6] = __('Template saved.', 'newsletter-campaign');

        return $messages;
    }


    /*
     * Set output messages for Subscriber
     */
    public function set_subscriber_messages($messages) {
        // Do not display 'view post' link
        $messages['post'][1] = __('Subscriber updated.', 'newsletter-campaign');
        // Override the message for first save
        $messages['post'][6] = __('Subscriber saved.', 'newsletter-campaign');

        return $messages;
    }


    /**
     * Create new meta boxes from the class
     *
     * @since    0.0.0
     */
    private static function create_meta_boxes() {

        function newsletter_campaign_add_meta_boxes() {
            $add_class = new Newsletter_campaign_meta_box_generator();
            /* $add_class->nc_add_meta_box('nc-subscriber-$field-add', __($title, 'newsletter-campaign'), 'nc_render_meta_box', $post_type, $position, $priority, array('post_type' => $post_type, 'field' => $field, 'title' => __($title, 'newsletter-campaign'))); */

            // Subscribers
            $add_class->nc_add_meta_box( 'nc-subscriber-name-add', __('Name', 'newsletter-campaign'), 'nc_render_meta_box', 'subscriber', 'normal', 'high', array(
                'post_type' => 'subscriber',
                'field' => 'name',
                'title' => __('Name', 'newsletter-campaign')
                )
            );
            $add_class->nc_add_meta_box( 'nc-subscriber-extra-add', __('Extra Information', 'newsletter-campaign'), 'nc_render_meta_box', 'subscriber', 'normal', 'high', array(
                'post_type' => 'subscriber',
                'field' => 'extra',
                'title' => __('Extra Information','newsletter-campaign'),
                'type' => 'textarea'
                )
            );
            $add_class->nc_add_meta_box( 'nc-subscriber-hash-add', __('Secure Hash', 'newsletter-campaign'), 'nc_render_meta_box', 'subscriber', 'normal', 'high', array(
                'post_type' => 'subscriber',
                'field' => 'hash',
                'title' => __('Secure Hash', 'newsletter-campaign'),
                'type' => 'hash'
                )
            );

            // Templates
            $add_class->nc_add_meta_box( 'nc-template-base-html-add', __('Base HTML', 'newsletter-campaign'), 'nc_render_meta_box', 'template', 'normal', 'high', array(
                'post_type' => 'template',
                'field' => 'base-html',
                'title' => __('Base HTML', 'newsletter-campaign'),
                'type' => 'textarea')
            );
            $add_class->nc_add_meta_box( 'nc-template-post-html-add', __('Post HTML', 'newsletter-campaign'), 'nc_render_meta_box', 'template', 'normal', 'high', array(
                'post_type' => 'template',
                'field' => 'post-html',
                'title' => __('Post HTML', 'newsletter-campaign'),
                'type' => 'textarea')
            );
            $add_class->nc_add_meta_box( 'nc-template-special-posts-add', __('Special Posts', 'newsletter-campaign'), 'nc_render_meta_box', 'template', 'normal', 'high', array(
                'post_type' => 'template',
                'field' => 'special-posts',
                'title' => __('Special Posts','newsletter-campaign'),
                'type' => 'repeater',
                'singular' => __('Special Post', 'newsletter-campaign'),
                'subfields' => array(
                    array('field' => 'special-name',
                        'title' => __('Name', 'newsletter-campaign'),
                        'type' => 'text'),
                    array('field' => 'special-body',
                        'title' => __('Special Post HTML', 'newsletter-campaign'),
                        'type' => 'textarea'),
                    array('field' => 'special-code',
                        'title' => __('Special Template Code'),
                        'type' => 'text'),
                    array('field' => 'hidden',
                        'title' => 'hidden',
                        'type' => 'hidden')
                    )
                )
            );

            // Campaigns
            $add_class->nc_add_meta_box( 'nc-campaign-description-add', __('Description', 'newsletter-campaign'), 'nc_render_meta_box', 'campaign', 'normal', 'high', array(
                'post_type' => 'campaign',
                'field' => 'description',
                'title' => __('Description', 'newsletter-campaign'),
                'type' => 'textarea')
            );

            $campaign_template_args = apply_filters(
                'newsletter_campaign_campaign_template_args', array(
                    'posts_per_page'   => -1,
                    'orderby'          => 'title',
                    'order'            => 'DESC',
                    'post_type'        => 'template',
                    'post_status'      => 'publish'
                )
            );

            $add_class->nc_add_meta_box( 'nc-campaign-template-select-add', __('Template', 'newsletter-campaign'), 'nc_render_meta_box', 'campaign', 'side', 'low', array(
                'post_type' => 'campaign',
                'field' => 'template-select',
                'title' => __('Template', 'newsletter-campaign'),
                'type' => 'select',
                'select_options' => get_posts( $campaign_template_args ),
                'key' => 'ID',
                'value' => 'post_title',
                'not_found' => array(
                    __('No templates found', 'newsletter-campaign'), '<a href="' . home_url() . '/wp-admin/post-new.php?post_type=template">' . __('Create a template', 'newsletter-campaign') . '</a>')
                )
            );

            $campaign_subscriber_list_args = apply_filters(
                'newsletter_campaign_campaign_subscriber_list_args', array(
                    'orderby'       => 'name',
                    'order'         => 'ASC',
                    'hide_empty'    => false
                )
            );

            $add_class->nc_add_meta_box('nc-campaign-subscriber-list-check-add', __('Subscriber List', 'newsletter-campaign'), 'nc_render_meta_box', 'campaign', 'side', 'low', array(
                'post_type' => 'campaign',
                'field' => 'subscriber-list-check',
                'title' => __('Subscriber List', 'newsletter-campaign'),
                'type' => 'checkbox',
                'select_options' => get_terms( 'subscriber_list', $campaign_subscriber_list_args ),
                'key' => 'term_id',
                'value' => 'name',
                'not_found' => array(
                    __('No subscriber lists found', 'newsletter-campaign'), '<a href="' . home_url() . '/wp-admin/edit-tags.php?taxonomy=subscriber_list&post_type=subscriber">' . __('Create a subscriber list', 'newsletter-campaign') . '</a>')
                )
            );

            $add_class->nc_add_meta_box('nc-campaign-builder-add', __('Newsletter Builder', 'newsletter-campaign'), 'nc_render_meta_box', 'campaign', 'normal', 'high', array(
                'post_type' => 'campaign',
                'field' => 'builder',
                'title' => __('Newsletter Builder', 'newsletter-campaign'),
                'type' => 'custom',
                'custom_type' => 'builder')
            );

            $add_class->nc_add_meta_box('nc-campaign-send-add', __('Send Campaign', 'newsletter-campaign'), 'nc_render_campaign_send_campaign', 'campaign', 'normal', 'low');

            $add_class->nc_add_meta_box( 'nc-campaign-test-send', __('Test Send', 'newsletter-campaign'), 'nc_render_meta_box', 'campaign', 'side', 'low', array(
                'post_type' => 'campaign',
                'field' => 'test-send',
                'title' => __('Test Send', 'newsletter-campaign'),
                'type' => 'multi',
                'subfields' => array(
                    array('field' => 'test-send-addresses',
                        'title' => __('Email Addresses', 'newsletter-campaign'),
                        'type' => 'textarea',
                        'placeholder' => __('Comma, space or line separated email addresses', 'newsletter-campaign')
                    ),
                    array('field' => 'test-send-btn',
                        'title' => __('Send Test Email', 'newsletter-campaign'),
                        'type' => 'button'
                    )
                ),
                'meta_name' => 'test-send'
                )
            );

        }

        function newsletter_campaign_save_meta_boxes($post) {
            $save_class = new Newsletter_campaign_meta_box_generator();
            /* $save_class->nc_save_meta_box($post, $post-type, $field, $meta_name, $sanitize_as ); */

            // Subscribers
            $save_class->nc_save_meta_box( $post, 'subscriber', 'name' );
            $save_class->nc_save_meta_box( $post, 'subscriber', 'extra' );
            $save_class->nc_save_meta_box( $post, 'subscriber', 'hash' );

            // Templates
            $save_class->nc_save_meta_box( $post, 'template', 'base-html', '', 'code' );
            $save_class->nc_save_meta_box( $post, 'template', 'post-html', '', 'code' );
            $save_class->nc_save_meta_box( $post, 'template', array(
                'special-name', 'special-body', 'special-code', 'hidden'
                ), '', $sanitize_as = array('special-body' => 'code')
            );

            // Campaigns
            $save_class->nc_save_meta_box( $post, 'campaign', 'description' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'template-select' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'subscriber-list-check' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'builder', '', 'code' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'message-subject' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'message-from', '', 'code' );
            $save_class->nc_save_meta_box( $post, 'campaign', array('test-send-addresses'), 'test-send', 'code' );
        }

        add_action( 'add_meta_boxes', 'newsletter_campaign_add_meta_boxes' );
        add_action( 'save_post', 'newsletter_campaign_save_meta_boxes', 10, 2 );

    }


    /**
     * Automatically hide certain meta boxes from view
     * @param  arr $hidden The already set array of hidden meta boxes
     * @param  obj $screen Current active screen
     * @return arr
     */
    public function hide_meta_boxes($hidden, $screen) {
        switch ($screen->base) {
            case 'subscriber':
                $hidden[] = 'hash';
                break;
        }

        return $hidden;
    }

}