<?php

/**
 * Register custom taxonomies and custom post types
 */
class Newsletter_campaign_post_types {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
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
                'hierarchical'          => false,
                'label'                 => __( 'Subscriber Lists', 'newsletter-campaign' ),
                'labels' => array(
                        'name'              => __( 'Subscriber Lists', 'newsletter-campaign' ),
                        'singular_name'     => __( 'Subscriber List', 'newsletter-campaign' ),
                        'search_items'      => __( 'Search Subscriber Lists', 'newsletter-campaign' ),
                        'all_items'         => __( 'All Subscriber Lists', 'newsletter-campaign' ),
                        'edit_item'         => __( 'Edit Subscriber List', 'newsletter-campaign' ),
                        'update_item'       => __( 'Update Subscriber List', 'newsletter-campaign' ),
                        'add_new_item'      => __( 'Add New Subscriber List', 'newsletter-campaign' ),
                        'new_item_name'     => __( 'New Subscriber List Name', 'newsletter-campaign' )
                    ),
                'show_ui'               => true,
                'query_var'             => true
            ) )
        );
    }

    /**
     * Register Newsletter Campaign post types
     */
    public static function register_post_types() {
        if ( post_type_exists('subscriber') )
            return;

        do_action( 'newsletter_campaign_register_post_type' );


        /**
         * Campaigns
         */
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
                    'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );


        /**
         * Templates
         */
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
                    'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );


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
                    'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
                    'has_archive'           => true,
                    'show_in_nav_menus'     => true
                )
            )
        );

    }
}

new Newsletter_campaign_post_types();