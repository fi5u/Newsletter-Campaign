<?php

/**
 * Register custom taxonomies and custom post types
 */
class Newsletter_campaign_post_types {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( __CLASS__, 'register_post_type_campaign' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_post_type_template' ), 10 );
        add_action( 'init', array( __CLASS__, 'register_post_type_subscriber' ), 15 );
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 20 );
    }


    /**
     * Register Newsletter Campaign campaign post type
     */
    public static function register_post_type_campaign() {
        if ( post_type_exists('campaign') )
            return;

        do_action( 'newsletter_campaign_register_post_type_campaign' );

        register_post_type( 'campaign',
            apply_filters( 'newsletter_campaign_register_post_type_campaign',
                array(
                    'labels' => array(
                            'name'                  => __( 'Campaigns', 'newsletter-campaign' ),
                            'singular_name'         => __( 'Campaign', 'newsletter-campaign' ),
                            'menu_name'             => _x( 'Campaigns', 'Admin menu name', 'newsletter-campaign' ),
                            'add_new'               => __( 'Start a Campaign', 'newsletter-campaign' ),
                            'add_new_item'          => __( 'Start a New Campaign', 'newsletter-campaign' ),
                            'edit'                  => __( 'Edit', 'newsletter-campaign' ),
                            'edit_item'             => __( 'Edit Campaign', 'newsletter-campaign' ),
                            'new_item'              => __( 'New Campaign', 'newsletter-campaign' ),
                            'view'                  => __( 'View Campaign', 'newsletter-campaign' ),
                            'view_item'             => __( 'View Campaign', 'newsletter-campaign' ),
                            'search_items'          => __( 'Search Campaigns', 'newsletter-campaign' ),
                            'not_found'             => __( 'No campaigns found', 'newsletter-campaign' ),
                            'not_found_in_trash'    => __( 'No campaigns found in trash', 'newsletter-campaign' ),
                            'parent'                => __( 'Parent Campaign', 'newsletter-campaign' )
                        ),
                    'description'           => __( 'Stores campaign details.', 'newsletter-campaign' ),
                    'public'                => true,
                    'show_ui'               => true,
                    'show_in_menu'          => 'newsletter-campaign',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => true,
                    'exclude_from_search'   => false,
                    'hierarchical'          => false,
                    'query_var'             => true,
                    'supports'              => array( 'title' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );
    }


    /**
     * Register Newsletter Campaign template post type
     */
    public static function register_post_type_template() {
        if ( post_type_exists('template') )
            return;

        do_action( 'newsletter_campaign_register_post_type_template' );

        register_post_type( 'template',
            apply_filters( 'newsletter_campaign_register_post_type_template',
                array(
                    'labels' => array(
                            'name'                  => __( 'Templates', 'newsletter-campaign' ),
                            'singular_name'         => __( 'Template', 'newsletter-campaign' ),
                            'menu_name'             => _x( 'Templates', 'Admin menu name', 'newsletter-campaign' ),
                            'add_new'               => __( 'Create a Template', 'newsletter-campaign' ),
                            'add_new_item'          => __( 'Create a New Template', 'newsletter-campaign' ),
                            'edit'                  => __( 'Edit', 'newsletter-campaign' ),
                            'edit_item'             => __( 'Edit Template', 'newsletter-campaign' ),
                            'new_item'              => __( 'New Template', 'newsletter-campaign' ),
                            'view'                  => __( 'View Template', 'newsletter-campaign' ),
                            'view_item'             => __( 'View Template', 'newsletter-campaign' ),
                            'search_items'          => __( 'Search Templates', 'newsletter-campaign' ),
                            'not_found'             => __( 'No templates found', 'newsletter-campaign' ),
                            'not_found_in_trash'    => __( 'No templates found in trash', 'newsletter-campaign' ),
                            'parent'                => __( 'Parent Template', 'newsletter-campaign' )
                        ),
                    'description'           => __( 'Stores template details.', 'newsletter-campaign' ),
                    'public'                => true,
                    'show_ui'               => true,
                    'show_in_menu'          => 'newsletter-campaign',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => true,
                    'exclude_from_search'   => false,
                    'hierarchical'          => false,
                    'query_var'             => true,
                    'supports'              => array( 'title' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );
    }


    /**
     * Register Newsletter Campaign subscriber post type
     */
    public static function register_post_type_subscriber() {
        if ( post_type_exists('subscriber') )
            return;

        do_action( 'newsletter_campaign_register_post_type_subscriber' );

        /**
         * Subscribers
         */
        register_post_type( 'subscriber',
            apply_filters( 'newsletter_campaign_register_post_type_subscriber',
                array(
                    'labels' => array(
                            'name'                  => __( 'Subscribers', 'newsletter-campaign' ),
                            'singular_name'         => __( 'Subscriber', 'newsletter-campaign' ),
                            'menu_name'             => _x( 'Subscribers', 'Admin menu name', 'newsletter-campaign' ),
                            'add_new'               => __( 'Add a Subscriber', 'newsletter-campaign' ),
                            'add_new_item'          => __( 'Add a New Subscriber', 'newsletter-campaign' ),
                            'edit'                  => __( 'Edit', 'newsletter-campaign' ),
                            'edit_item'             => __( 'Edit Subscriber', 'newsletter-campaign' ),
                            'new_item'              => __( 'New Subscriber', 'newsletter-campaign' ),
                            'view'                  => __( 'View Subscriber', 'newsletter-campaign' ),
                            'view_item'             => __( 'View Subscriber', 'newsletter-campaign' ),
                            'search_items'          => __( 'Search Subscribers', 'newsletter-campaign' ),
                            'not_found'             => __( 'No subscribers found', 'newsletter-campaign' ),
                            'not_found_in_trash'    => __( 'No subscribers found in trash', 'newsletter-campaign' ),
                            'parent'                => __( 'Parent Subscriber', 'newsletter-campaign' )
                        ),
                    'description'           => __( 'Stores subscriber details.', 'newsletter-campaign' ),
                    'public'                => true,
                    'show_ui'               => true,
                    'show_in_menu'          => 'newsletter-campaign',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => true,
                    'exclude_from_search'   => false,
                    'hierarchical'          => false,
                    'query_var'             => true,
                    'supports'              => array( 'title' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );

    }


    /**
     * Register Newsletter Campaign taxonomies
     */
    public static function register_taxonomies() {


        if ( taxonomy_exists( 'subscriber_list' ) )
            return;

        do_action( 'newsletter_campaign_register_taxonomy' );


        register_taxonomy( 'subscriber_list',
            apply_filters( 'newsletter_campaign_taxonomy_objects_subscriber_list', array( 'subscriber' ) ),
            apply_filters( 'newsletter_campaign_taxonomy_args_subscriber_list', array(
                'hierarchical'          => true,
                'label'                 => __( 'Subscriber Lists', 'newsletter-campaign' ),
                'labels' => array(
                        'name'              => __( 'Subscriber Lists', 'newsletter-campaign' ),
                        'singular_name'     => __( 'Subscriber List', 'newsletter-campaign' ),
                        'search_items'      => __( 'Search Subscriber Lists', 'newsletter-campaign' ),
                        'all_items'         => __( 'All Subscriber Lists', 'newsletter-campaign' ),
                        'edit_item'         => __( 'Edit Subscriber List', 'newsletter-campaign' ),
                        'update_item'       => __( 'Update Subscriber List', 'newsletter-campaign' ),
                        'add_new_item'      => __( 'Add New Subscriber List', 'newsletter-campaign' ),
                        'new_item_name'     => __( 'New Subscriber List Name', 'newsletter-campaign' ),
                        'popular_items'     => __( 'Popular Subscriber Lists', 'newsletter-campaign' ),
                        'separate_items_with_commas'
                                            => __( 'Separate subscriber lists with commas', 'newsletter-campaign' ),
                        'choose_from_most_used'
                                            => __( 'Choose from the most used subscriber lists' ),
                        'not_found'         => __( 'No subscriber lists found' )
                    ),
                'show_ui'               => true,
                'query_var'             => true,
                'show_in_nav_menus'     => true
            ) )
        );

        register_taxonomy_for_object_type( 'subscriber_list', 'subscriber' );
    }




}

new Newsletter_campaign_post_types();