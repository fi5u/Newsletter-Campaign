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
     * Register core post types
     */
    public static function register_post_types() {
        if ( post_type_exists('product') )
            return;

        do_action( 'woocommerce_register_post_type' );

        $permalinks        = get_option( 'woocommerce_permalinks' );
        $product_permalink = empty( $permalinks['product_base'] ) ? _x( 'product', 'slug', 'woocommerce' ) : $permalinks['product_base'];

        register_post_type( "product",
            apply_filters( 'woocommerce_register_post_type_product',
                array(
                    'labels' => array(
                            'name'                  => __( 'Products', 'woocommerce' ),
                            'singular_name'         => __( 'Product', 'woocommerce' ),
                            'menu_name'             => _x( 'Products', 'Admin menu name', 'woocommerce' ),
                            'add_new'               => __( 'Add Product', 'woocommerce' ),
                            'add_new_item'          => __( 'Add New Product', 'woocommerce' ),
                            'edit'                  => __( 'Edit', 'woocommerce' ),
                            'edit_item'             => __( 'Edit Product', 'woocommerce' ),
                            'new_item'              => __( 'New Product', 'woocommerce' ),
                            'view'                  => __( 'View Product', 'woocommerce' ),
                            'view_item'             => __( 'View Product', 'woocommerce' ),
                            'search_items'          => __( 'Search Products', 'woocommerce' ),
                            'not_found'             => __( 'No Products found', 'woocommerce' ),
                            'not_found_in_trash'    => __( 'No Products found in trash', 'woocommerce' ),
                            'parent'                => __( 'Parent Product', 'woocommerce' )
                        ),
                    'description'           => __( 'This is where you can add new products to your store.', 'woocommerce' ),
                    'public'                => true,
                    'show_ui'               => true,
                    'capability_type'       => 'product',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => true,
                    'exclude_from_search'   => false,
                    'hierarchical'          => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite'               => $product_permalink ? array( 'slug' => untrailingslashit( $product_permalink ), 'with_front' => false, 'feeds' => true ) : false,
                    'query_var'             => true,
                    'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes' ),
                    'has_archive'           => ( $shop_page_id = wc_get_page_id( 'shop' ) ) && get_page( $shop_page_id ) ? get_page_uri( $shop_page_id ) : 'shop',
                    'show_in_nav_menus'     => true
                )
            )
        );

        register_post_type( "product_variation",
            apply_filters( 'woocommerce_register_post_type_product_variation',
                array(
                    'label'        => __( 'Variations', 'woocommerce' ),
                    'public'       => false,
                    'hierarchical' => false,
                    'supports'     => false
                )
            )
        );

        $menu_name = _x('Orders', 'Admin menu name', 'woocommerce' );

        if ( $order_count = wc_processing_order_count() ) {
            $menu_name .= " <span class='awaiting-mod update-plugins count-$order_count'><span class='processing-count'>" . number_format_i18n( $order_count ) . "</span></span>" ;
        }

        register_post_type( "shop_order",
            apply_filters( 'woocommerce_register_post_type_shop_order',
                array(
                    'labels' => array(
                            'name'                  => __( 'Orders', 'woocommerce' ),
                            'singular_name'         => __( 'Order', 'woocommerce' ),
                            'add_new'               => __( 'Add Order', 'woocommerce' ),
                            'add_new_item'          => __( 'Add New Order', 'woocommerce' ),
                            'edit'                  => __( 'Edit', 'woocommerce' ),
                            'edit_item'             => __( 'Edit Order', 'woocommerce' ),
                            'new_item'              => __( 'New Order', 'woocommerce' ),
                            'view'                  => __( 'View Order', 'woocommerce' ),
                            'view_item'             => __( 'View Order', 'woocommerce' ),
                            'search_items'          => __( 'Search Orders', 'woocommerce' ),
                            'not_found'             => __( 'No Orders found', 'woocommerce' ),
                            'not_found_in_trash'    => __( 'No Orders found in trash', 'woocommerce' ),
                            'parent'                => __( 'Parent Orders', 'woocommerce' ),
                            'menu_name'             => $menu_name
                        ),
                    'description'           => __( 'This is where store orders are stored.', 'woocommerce' ),
                    'public'                => false,
                    'show_ui'               => true,
                    'capability_type'       => 'shop_order',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => false,
                    'exclude_from_search'   => true,
                    'show_in_menu'          => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
                    'hierarchical'          => false,
                    'show_in_nav_menus'     => false,
                    'rewrite'               => false,
                    'query_var'             => false,
                    'supports'              => array( 'title', 'comments', 'custom-fields' ),
                    'has_archive'           => false,
                )
            )
        );

        if ( get_option( 'woocommerce_enable_coupons' ) == 'yes' ) {
            register_post_type( "shop_coupon",
                apply_filters( 'woocommerce_register_post_type_shop_coupon',
                    array(
                        'labels' => array(
                                'name'                  => __( 'Coupons', 'woocommerce' ),
                                'singular_name'         => __( 'Coupon', 'woocommerce' ),
                                'menu_name'             => _x( 'Coupons', 'Admin menu name', 'woocommerce' ),
                                'add_new'               => __( 'Add Coupon', 'woocommerce' ),
                                'add_new_item'          => __( 'Add New Coupon', 'woocommerce' ),
                                'edit'                  => __( 'Edit', 'woocommerce' ),
                                'edit_item'             => __( 'Edit Coupon', 'woocommerce' ),
                                'new_item'              => __( 'New Coupon', 'woocommerce' ),
                                'view'                  => __( 'View Coupons', 'woocommerce' ),
                                'view_item'             => __( 'View Coupon', 'woocommerce' ),
                                'search_items'          => __( 'Search Coupons', 'woocommerce' ),
                                'not_found'             => __( 'No Coupons found', 'woocommerce' ),
                                'not_found_in_trash'    => __( 'No Coupons found in trash', 'woocommerce' ),
                                'parent'                => __( 'Parent Coupon', 'woocommerce' )
                            ),
                        'description'           => __( 'This is where you can add new coupons that customers can use in your store.', 'woocommerce' ),
                        'public'                => false,
                        'show_ui'               => true,
                        'capability_type'       => 'shop_coupon',
                        'map_meta_cap'          => true,
                        'publicly_queryable'    => false,
                        'exclude_from_search'   => true,
                        'show_in_menu'          => current_user_can( 'manage_woocommerce' ) ? 'woocommerce' : true,
                        'hierarchical'          => false,
                        'rewrite'               => false,
                        'query_var'             => false,
                        'supports'              => array( 'title' ),
                        'show_in_nav_menus'     => false,
                        'show_in_admin_bar'     => true
                    )
                )
            );
        }
    }
}

new Newsletter_campaign_post_types();