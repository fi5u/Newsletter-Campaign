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

        register_post_type( 'subscriber',
            apply_filters( 'newsletter_campaign_register_post_type_subscriber',
                array(
                    'labels' => array(
                            'name'                  => __( 'Subscribers', 'newsletter-campaign' ),
                            'singular_name'         => __( 'Subscriber', 'newsletter-campaign' ),
                            'menu_name'             => _x( 'Subscribers', 'Admin menu name', 'newsletter-campaign' ),
                            'add_new'               => __( 'Add Subscriber', 'newsletter-campaign' ),
                            'add_new_item'          => __( 'Add New Subscriber', 'newsletter-campaign' ),
                            'edit'                  => __( 'Edit', 'newsletter-campaign' ),
                            'edit_item'             => __( 'Edit Subscriber', 'newsletter-campaign' ),
                            'new_item'              => __( 'New Subscriber', 'newsletter-campaign' ),
                            'view'                  => __( 'View Subscriber', 'newsletter-campaign' ),
                            'view_item'             => __( 'View Subscriber', 'newsletter-campaign' ),
                            'search_items'          => __( 'Search Subscribers', 'newsletter-campaign' ),
                            'not_found'             => __( 'No Subscribers found', 'newsletter-campaign' ),
                            'not_found_in_trash'    => __( 'No Subscribers found in trash', 'newsletter-campaign' ),
                            'parent'                => __( 'Parent Subscriber', 'newsletter-campaign' )
                        ),
                    'description'           => __( 'Stores subscriber details.', 'newsletter-campaign' ),
                    'public'                => true,
                    'show_ui'               => true,
                    'capability_type'       => 'subscriber',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => true,
                    'exclude_from_search'   => false,
                    'hierarchical'          => false,
                    'query_var'             => true,
                    'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
                    'has_archive'           => false,
                    'show_in_nav_menus'     => true
                )
            )
        );
    }
}

new Newsletter_campaign_post_types();