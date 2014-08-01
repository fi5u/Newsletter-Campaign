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

        // Include required files
        $this->includes();

        // Create meta boxes
        $this->create_meta_boxes();

        // Replace submit meta boxes
        $this->replace_submit_meta_boxes();

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

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

            wp_enqueue_script( $this->plugin_slug . '-repeater-script', plugins_url( 'assets/js/repeater.js', __FILE__ ), $drag_drop_deps, NewsletterCampaign::VERSION, true );
        }

        if ( 'campaign' === $screen->post_type ) {

            wp_enqueue_script( $this->plugin_slug . '-builder-script', plugins_url( 'assets/js/builder.js', __FILE__ ), $drag_drop_deps, NewsletterCampaign::VERSION, true );
            wp_enqueue_script( $this->plugin_slug . '-campaign-script', plugins_url( 'assets/js/campaign.js', __FILE__ ), array('jquery'), NewsletterCampaign::VERSION, true );
            // in javascript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script( $this->plugin_slug . '-campaign-script', 'nc_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234, 'please_save' => __('Please save the campaign before proceeding', 'newsletter-campaign') ) );

        }

        //if( $this->plugin_screen_hook_suffix === $screen->id ) {
        	wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array('jquery'), NewsletterCampaign::VERSION, true );
        //}

	}

	/**
	 * Register the administration menus for this plugin into the WordPress Dashboard menu
	 *
	 * @since    0.0.0
	 */
	public function add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Newsletter Campaign', $this->plugin_slug ), // Page title
			__( 'Newsletter', $this->plugin_slug ), // Menu title
			'manage_options', // Capability
			$this->plugin_slug, // Menu slug
			array( $this, 'display_plugin_admin_page' ), // Function
            'dashicons-email-alt' // Icon url
		);

        $this->plugin_screen_dashboard = add_submenu_page(
            $this->plugin_slug, // Parent slug
            __( 'Dashboard', $this->plugin_slug ), // Page title
            __( 'Dashboard', $this->plugin_slug ), // Menu title
            'manage_options', // Capability
            $this->plugin_slug, // Menu slug
            array( $this, 'display_plugin_admin_page' ) // Function
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

    }


    /**
     * Create new meta boxes from the class
     *
     * @since    0.0.0
     */
    private static function replace_submit_meta_boxes() {
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('campaign', __('Overview', 'newsletter-campaign'), __('Save campaign', 'newsletter-campaign'));
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('template', __('Overview', 'newsletter-campaign'), __('Save campaign', 'newsletter-campaign'));
        $campaign_submit_meta_box = new Newsletter_campaign_submit_meta('subscriber', __('Overview', 'newsletter-campaign'), __('Save campaign', 'newsletter-campaign'));
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
            $add_class->nc_add_meta_box( 'nc-subscriber-notes-add', __('Notes', 'newsletter-campaign'), 'nc_render_meta_box', 'subscriber', 'normal', 'high', array(
                'post_type' => 'subscriber',
                'field' => 'notes',
                'title' => __('Notes','newsletter-campaign'),
                'type' => 'textarea'
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
            /* $save_class->nc_save_meta_box($post, $post-type, $field ); */

            // Subscribers
            $save_class->nc_save_meta_box( $post, 'subscriber', 'name' );
            $save_class->nc_save_meta_box( $post, 'subscriber', 'notes' );

            // Templates
            $save_class->nc_save_meta_box( $post, 'template', 'base-html' );
            $save_class->nc_save_meta_box( $post, 'template', 'post-html' );
            $save_class->nc_save_meta_box( $post, 'template', array(
                'special-name', 'special-body', 'special-code', 'hidden'
                )
            );

            // Campaigns
            $save_class->nc_save_meta_box( $post, 'campaign', 'description' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'template-select' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'subscriber-list-check' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'builder' );
            $save_class->nc_save_meta_box( $post, 'campaign', 'message-subject' );
            $save_class->nc_save_meta_box( $post, 'campaign', array('test-send-addresses'), 'test-send' );
        }

        add_action( 'add_meta_boxes', 'newsletter_campaign_add_meta_boxes' );
        add_action( 'save_post', 'newsletter_campaign_save_meta_boxes', 10, 2 );

    }

}