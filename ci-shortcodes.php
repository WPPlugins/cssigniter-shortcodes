<?php
/*
Plugin Name: CSSIgniter Shortcodes
Plugin URI: http://www.cssigniter.com/ignite/ci-shortcodes/
Description: Registers a lot of useful shortcodes
Version: 2.3
Author: The CSSIgniter.com Team
Author URI: http://www.cssigniter.com
License: GPL2
Text Domain: cssigniter-shortcodes
Domain Path: /languages
*/
?>
<?php
/*	Copyright 2012-2016  CSSIgniter  (email : info@cssigniter.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php

if ( ! defined( 'CI_SHORTCODES_VERSION' ) )          define( 'CI_SHORTCODES_VERSION', '2.3' );
if ( ! defined( 'CI_SHORTCODES_PLUGIN_INSTALLED' ) ) define( 'CI_SHORTCODES_PLUGIN_INSTALLED', 'cssigniter_shortcodes_plugin_version' );
if ( ! defined( 'CI_SHORTCODES_BASENAME' ) )         define( 'CI_SHORTCODES_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined( 'CI_SHORTCODES_ABS_DIR' ) )          define( 'CI_SHORTCODES_ABS_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'CI_SHORTCODES_PLUGIN_OPTIONS' ) )   define( 'CI_SHORTCODES_PLUGIN_OPTIONS', 'cssigniter_shortcodes_plugin' );

load_plugin_textdomain( 'cssigniter-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

$cishort_options = array(); // Hold the plugin options

require_once( 'inc/panel.php' );
require_once( 'inc/helpers.php' );
require_once( 'inc/upgrade.php' );
require_once( 'inc/shortcodes/accordion.php' );
require_once( 'inc/shortcodes/box.php' );
require_once( 'inc/shortcodes/button.php' );
require_once( 'inc/shortcodes/grid.php' );
require_once( 'inc/shortcodes/heading.php' );
require_once( 'inc/shortcodes/list.php' );
require_once( 'inc/shortcodes/slider.php' );
require_once( 'inc/shortcodes/map.php' );
require_once( 'inc/shortcodes/quote.php' );
require_once( 'inc/shortcodes/separator.php' );
require_once( 'inc/shortcodes/tabs.php' );
require_once( 'inc/shortcodes/tooltip.php' );


// Loads options from DB. Needs to run from within a hook.
add_action( 'init', 'ci_shortcodes_load_options' );
function ci_shortcodes_load_options() {
	global $cishort_options;
	$cishort_options = ci_shortcodes_settings_validate( get_option( CI_SHORTCODES_PLUGIN_OPTIONS ) );

	// Activation/Deactivation hooks don't run for all sites in Multisite.
	// Therefore, we'll have to check on each pageload if the options array is actually set.
	// It's not much of an overhead, since we are loading the options anyway.
	if ( empty( $cishort_options ) ) {
		ci_shortcodes_activate();
	}
}


//
// No code. Prevents execution of shortcodes. Useful for tutorials.
//
function ci_shortcodes_nocode( $atts, $content = '' ) {
	return $content;
}

//
// Demo. Prevents execution of shortcodes. Useful for tutorials.
//
function ci_shortcodes_demo( $atts, $content = null, $tag ) {
	$has_span = array_search( 'span', (array)$atts, true );
	if ( $has_span !== false && is_numeric( $has_span ) ) {
		$atts['element'] = 'span';
	}

	$atts = shortcode_atts( array(
		'element' => 'div',
	), $atts, $tag );

	return sprintf( '<%1$s class="cisc-demo">%2$s</%1$s>', $atts['element'], $content );
}

//
// Register our shortcodes with or without a prefix, according to user setting.
//
function ci_shortcodes_get_shortcodes( $include_prefix = 'default' ) {
	if ( 'default' == $include_prefix ) {
		global $cishort_options;
		$prefix = $cishort_options['compatibility'] == 'enabled' ? 'ci-' : '';
	} elseif ( true == $include_prefix ) {
		$prefix = 'ci-';
	} else {
		$prefix = '';
	}

	$shortcodes = array(
		$prefix . 'button'          => 'ci_shortcodes_buttons',
		$prefix . 'box'             => 'ci_shortcodes_boxes',
		$prefix . 'h'               => 'ci_shortcodes_headings',
		$prefix . 'h1'              => 'ci_shortcodes_headings',
		$prefix . 'h2'              => 'ci_shortcodes_headings',
		$prefix . 'h3'              => 'ci_shortcodes_headings',
		$prefix . 'h4'              => 'ci_shortcodes_headings',
		$prefix . 'h5'              => 'ci_shortcodes_headings',
		$prefix . 'h6'              => 'ci_shortcodes_headings',
		$prefix . 'map'             => 'ci_shortcodes_googlemaps',
		$prefix . 'googlemap'       => 'ci_shortcodes_googlemaps',
		$prefix . 'tabs'            => 'ci_shortcodes_tabs',
		$prefix . 'tab'             => 'ci_shortcodes_tab',
		$prefix . 'accordion'       => 'ci_shortcodes_accordion',
		$prefix . 'accordion_tab'   => 'ci_shortcodes_accordion_tab',
		$prefix . 'tooltip'         => 'ci_shortcodes_tooltips',
		$prefix . 'quote'           => 'ci_shortcodes_quotes',
		$prefix . 'separator'       => 'ci_shortcodes_hr',
		$prefix . 'hr'              => 'ci_shortcodes_hr',
		$prefix . 'list'            => 'ci_shortcodes_lists',
		$prefix . 'slider'          => 'ci_shortcodes_slider',
		$prefix . 'slide'           => 'ci_shortcodes_slide',
		$prefix . 'nocode'          => 'ci_shortcodes_nocode',
		$prefix . 'demo'            => 'ci_shortcodes_demo',
		$prefix . 'row'             => 'ci_shortcodes_row',
		$prefix . '_row'            => 'ci_shortcodes_row',
		$prefix . '__row'           => 'ci_shortcodes_row',
		$prefix . 'column'          => 'ci_shortcodes_columns',
		$prefix . '_column'         => 'ci_shortcodes_columns',
		$prefix . '__column'        => 'ci_shortcodes_columns',
		$prefix . 'col'             => 'ci_shortcodes_columns',
		$prefix . '_col'            => 'ci_shortcodes_columns',
		$prefix . '__col'           => 'ci_shortcodes_columns',
		$prefix . 'one_half'        => 'ci_shortcodes_columns',
		$prefix . 'one_half_last'   => 'ci_shortcodes_columns',
		$prefix . 'one_third'       => 'ci_shortcodes_columns',
		$prefix . 'one_third_last'  => 'ci_shortcodes_columns',
		$prefix . 'two_thirds'      => 'ci_shortcodes_columns',
		$prefix . 'two_thirds_last' => 'ci_shortcodes_columns',
	);

	return $shortcodes;
}

function ci_shortcodes_get_default_color_schemes() {
	return apply_filters( 'cisc_default_color_schemes', array(
		'blue',
		'red',
		'yellow',
		'orange',
		'purple',
		'pink',
		'brown',
		'green',
		'gray',
		'white',
		'black',
	) );
}

//function ci_shortcodes_register_no_texturize( $excludes_shortcodes ) {
//	global $cishort_options;
//	$prefix = 'enabled' == $cishort_options['compatibility'] ? 'ci-' : '';
//
//	$shortcodes = array(
//		$prefix . 'tabs',
//		$prefix . 'accordion',
//		$prefix . 'row',
//		$prefix . '_row',
//		$prefix . '__row',
//	);
//
//	return array_merge($excludes_shortcodes, $shortcodes);
//}
//add_filter( 'no_texturize_shortcodes', 'ci_shortcodes_register_no_texturize' );

add_action( 'init', 'ci_shortcodes_register_shortcodes' );
function ci_shortcodes_register_shortcodes() {
	$shortcodes = ci_shortcodes_get_shortcodes();
	foreach ( $shortcodes as $shortcode => $function ) {
		add_shortcode( $shortcode, $function );
	}
}


// Register our scripts.
add_action( 'init', 'ci_shortcode_register_scripts_styles' );
function ci_shortcode_register_scripts_styles() {
	wp_register_script( 'cisc-google-maps', ci_shortcodes_get_google_maps_api_url(), array(), null, false );

	wp_register_script( 'jquery-flexslider', plugins_url( 'src/js/jquery.flexslider.js', __FILE__ ), array( 'jquery' ), '2.2.2', true );
	wp_register_script( 'cisc-shortcodes', plugins_url( 'src/js/scripts.js', __FILE__ ), array(
		'jquery',
		'jquery-flexslider',
	), CI_SHORTCODES_VERSION, true );

	wp_register_style( 'cisc-shortcodes', plugins_url( 'src/style.css', __FILE__ ), array(), CI_SHORTCODES_VERSION );
	wp_register_style( 'font-awesome', plugins_url( 'src/css/font-awesome.css', __FILE__ ), array(), '4.6.3' );

	wp_register_style( 'cisc-panel', plugins_url( 'src/css/admin/panel.css', __FILE__ ), array(), CI_SHORTCODES_VERSION );
}

add_action( 'wp_enqueue_scripts', 'ci_shortcodes_enqueue_scripts_styles' );
function ci_shortcodes_enqueue_scripts_styles() {
	global $cishort_options;

	if ( isset( $cishort_options['google_maps_api_enable'] ) && $cishort_options['google_maps_api_enable'] == 'enabled' ) {
		wp_enqueue_script( 'cisc-google-maps' );
	}

	wp_enqueue_script( 'cisc-shortcodes' );
	wp_enqueue_style( 'cisc-shortcodes' );
	wp_enqueue_style( 'font-awesome' );
}

add_action( 'admin_enqueue_scripts', 'ci_shortcodes_enqueue_admin_scripts_styles' );
function ci_shortcodes_enqueue_admin_scripts_styles() {
	wp_enqueue_style( 'cisc-panel' );
}

add_filter( 'plugin_action_links_' . CI_SHORTCODES_BASENAME, 'ci_shortcodes_plugin_action_links' );
function ci_shortcodes_plugin_action_links( $links ) {
	$url = admin_url( 'options-general.php?page=cssigniter_shortcodes_plugin' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'cssigniter-shortcodes' ) . '</a>' );

	return $links;
}

add_action( 'in_plugin_update_message-' . CI_SHORTCODES_BASENAME, 'ci_shortcodes_plugin_update_message', 10, 2 );
function ci_shortcodes_plugin_update_message( $plugin_data, $r ) {
	if ( ! empty( $r->upgrade_notice ) ) {
		printf( '<p style="margin: 3px 0 0 0; border-top: 1px solid #ddd; padding-top: 3px">%s</p>', $r->upgrade_notice );
	}
}


function ci_shortcodes_get_google_maps_api_url() {
	global $cishort_options;

	$args = array(
		'v' => '3',
	);

	$key = ! empty( $cishort_options['google_maps_api_key'] ) ? trim( $cishort_options['google_maps_api_key'] ) : '';

	if ( $key ) {
		$args['key'] = $key;
	}

	return esc_url_raw( add_query_arg( $args, '//maps.googleapis.com/maps/api/js' ) );
}


//
// Handle activation / deactivation
//
register_activation_hook( __FILE__, 'ci_shortcodes_activate' );
function ci_shortcodes_activate() {
	global $cishort_options;
	$cishort_options = get_option( CI_SHORTCODES_PLUGIN_OPTIONS );
	if ( $cishort_options === false || empty( $cishort_options ) ) {
		$cishort_options = ci_shortcodes_settings_validate( array() );
		update_option( CI_SHORTCODES_PLUGIN_OPTIONS, $cishort_options );
	}
}

register_deactivation_hook( __FILE__, 'ci_shortcodes_deactivate' );
function ci_shortcodes_deactivate() {
	delete_option( CI_SHORTCODES_PLUGIN_OPTIONS );
	unregister_setting( 'ci_shortcodes_plugin_settings', CI_SHORTCODES_PLUGIN_OPTIONS );
	delete_option( CI_SHORTCODES_PLUGIN_INSTALLED );
}
