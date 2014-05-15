<?php

/**
 * Register custom meta boxes
 */
class Newsletter_campaign_meta_boxes {

    /**
     * Constructor
     */
    public function __construct() {

        add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ), 10 );
        //add_action( 'save_post', array( __CLASS__, 'save_meta_boxes' ), 1 );
    }

    /**
     * Add Newsletter Campaign custom meta boxes
     */
    public static function add_meta_boxes() {

        add_meta_box(
            'nc-subscriber-email-add', // ID
            __('Email address', 'newsletter-campaign'), // title
            array(__CLASS__, 'main_text_input'), // callback
            'subscriber', // post_type
            'normal', // context
            'high'//, // priority
            //$callback_args // callback_args
        );
    }

    public static function main_text_input() {

    }

}

new Newsletter_campaign_meta_boxes;