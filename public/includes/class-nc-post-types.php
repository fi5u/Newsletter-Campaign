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
                'label'                 => __( 'Subscriber Lists', $this->plugin_slug ),
                'labels' => array(
                        'name'              => __( 'Subscriber Lists', $this->plugin_slug ),
                        'singular_name'     => __( 'Subscriber List', $this->plugin_slug ),
                        'search_items'      => __( 'Search Subscriber Lists', $this->plugin_slug ),
                        'all_items'         => __( 'All Subscriber Lists', $this->plugin_slug ),
                        'edit_item'         => __( 'Edit Subscriber List', $this->plugin_slug ),
                        'update_item'       => __( 'Update Subscriber List', $this->plugin_slug ),
                        'add_new_item'      => __( 'Add New Subscriber List', $this->plugin_slug ),
                        'new_item_name'     => __( 'New Subscriber List Name', $this->plugin_slug )
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
                            'name'                  => __( 'Subscribers', $this->plugin_slug ),
                            'singular_name'         => __( 'Subscriber', $this->plugin_slug ),
                            'menu_name'             => _x( 'Subscribers', 'Admin menu name', $this->plugin_slug ),
                            'add_new'               => __( 'Add Subscriber', $this->plugin_slug ),
                            'add_new_item'          => __( 'Add New Subscriber', $this->plugin_slug ),
                            'edit'                  => __( 'Edit', $this->plugin_slug ),
                            'edit_item'             => __( 'Edit Subscriber', $this->plugin_slug ),
                            'new_item'              => __( 'New Subscriber', $this->plugin_slug ),
                            'view'                  => __( 'View Subscriber', $this->plugin_slug ),
                            'view_item'             => __( 'View Subscriber', $this->plugin_slug ),
                            'search_items'          => __( 'Search Subscribers', $this->plugin_slug ),
                            'not_found'             => __( 'No Subscribers found', $this->plugin_slug ),
                            'not_found_in_trash'    => __( 'No Subscribers found in trash', $this->plugin_slug ),
                            'parent'                => __( 'Parent Subscriber', $this->plugin_slug )
                        ),
                    'description'           => __( 'Stores subscriber details.', $this->plugin_slug ),
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