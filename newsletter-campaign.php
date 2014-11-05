<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   NewsletterCampaign
 * @author    Fisu <tommybfisher@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014
 *
 * @wordpress-plugin
 * Plugin Name:       Newsletter Campaign
 * Plugin URI:        @TODO
 * Description:       @TODO
 * Version:           0.0.0
 * Author:            Fisu
 * Author URI:        https//github.com/fi5u
 * Text Domain:       newsletter-campaign-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );


// Load global constants
require_once( plugin_dir_path( __FILE__ ) . 'includes/globals.php' );


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-newsletter-campaign.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'NewsletterCampaign', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NewsletterCampaign', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'NewsletterCampaign', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {
    // Plugin-wide functions
    require_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-newsletter-campaign-admin.php' );
	add_action( 'plugins_loaded', array( 'NewsletterCampaignAdmin', 'get_instance' ) );
}