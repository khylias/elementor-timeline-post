<?php
/**
 * Plugin Name: Elementor Timeline Post
 * Description: Element plugin for timeline some post by category.
 * Plugin URI:  https://www.github.com/khylias/elementor-timeline-post
 * Version:     1.0.0
 * Author:      Vincent Kraus
 * Author URI:  https://krausvincent.fr
 * Text Domain: Elementor timeline post
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_TIMELINE__FILE__', __FILE__ );

define( 'ELEMENTOR_TIMELINE_URL', plugins_url( '/', __FILE__ ) );
define( 'ELEMENTOR_TIMELINE_PATH', plugin_dir_path( __FILE__ ) );

require_once __DIR__.'/inc/helper.php';

/**
 * Load Timeline Post
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function timeline_load() {
	// Load localization file
	load_plugin_textdomain( 'timeline' );

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'timeline_fail_load' );
		return;
	}

	// Check required version
	$elementor_version_required = '1.8.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'timeline_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require( __DIR__ . '/plugin.php' );
}
add_action( 'plugins_loaded', 'timeline_load' );

function timeline_scripts(){
    wp_enqueue_style('timeline-style',ELEMENTOR_TIMELINE_URL.'assets/css/style.css');

    /* animated timeline js file*/
    wp_enqueue_script('timeline-js',ELEMENTOR_TIMELINE_URL.'assets/js/timeline.js', array('jquery'),'1.0', true);
}
add_action( 'wp_enqueue_scripts', 'timeline_scripts' );

function timeline_fail_load_out_of_date() {
	if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$file_path = 'elementor/elementor.php';

	$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
	$message = '<p>' . __( 'Elementor Timeline is not working because you are using an old version of Elementor.', 'timeline' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, __( 'Update Elementor Now', 'timeline' ) ) . '</p>';

	echo '<div class="error">' . $message . '</div>';
}