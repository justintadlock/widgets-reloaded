<?php
/**
 * Core functions for the plugin.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justintadlock@gmail.com>
 * @copyright  Copyright (c) 2008 - 2017, Justin Tadlock
 * @link       https://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded;

# Removes theme support.
add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_support', 12 );

# Register widgets.
add_action( 'widgets_init', __NAMESPACE__ . '\register_widgets' );

# Load admin scripts and styles.
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue' );

# Make sure widgets are considered wide.
add_filter( 'is_wide_widget_in_customizer', __NAMESPACE__ . '\is_wide_widget', 10, 2 );

/**
 * Removes 'hybrid-core-widgets' theme support.  This is so that the plugin will take over the
 * widgets instead of themes built on Hybrid Core.  Plugin updates can get out quicker to users,
 * so the plugin should have priority.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function theme_support() {

	remove_theme_support( 'hybrid-core-widgets' );
}

/**
 * Determines whether the core WP widgets should be enabled that this plugin
 * is replacing.
 *
 * @since  1.0.0
 * @access public
 * @return bool
 */
function core_widgets_enabled() {

	return apply_filters( 'widgets_reloaded_core_widgets_enabled', true );
}

/**
 * Registers widget files.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function register_widgets() {

	// Unregister the default WordPress widgets.
	if ( ! core_widgets_enabled() ) {

		unregister_widget( 'WP_Widget_Archives'     );
		unregister_widget( 'WP_Widget_Calendar'     );
		unregister_widget( 'WP_Widget_Categories'   );
		unregister_widget( 'WP_Widget_Links'        );
		unregister_widget( 'WP_Nav_Menu_Widget'     );
		unregister_widget( 'WP_Widget_Pages'        );
		unregister_widget( 'WP_Widget_Recent_Posts' );
		unregister_widget( 'WP_Widget_Tag_Cloud'    );
	}

	// Register custom widgets.
	register_widget( __NAMESPACE__ . '\Widgets\Archives'   );
	register_widget( __NAMESPACE__ . '\Widgets\Authors'    );
	register_widget( __NAMESPACE__ . '\Widgets\Calendar'   );
	register_widget( __NAMESPACE__ . '\Widgets\Categories' );
	register_widget( __NAMESPACE__ . '\Widgets\Nav_Menu'   );
	register_widget( __NAMESPACE__ . '\Widgets\Pages'      );
	register_widget( __NAMESPACE__ . '\Widgets\Posts'      );
	register_widget( __NAMESPACE__ . '\Widgets\Tags'       );

	if ( get_option( 'link_manager_enabled' ) )
		register_widget( __NAMESPACE__ . '\Widgets\Bookmarks' );
}

/**
 * Loads admin CSS files.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function admin_enqueue( $hook_suffix ) {

	if ( 'widgets.php' == $hook_suffix ) {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'widgets-reloaded', plugin()->uri . "css/admin{$min}.css" );
	}
}

/**
 * Always makes sure that our widgets are considered wide widgets in the customizer.
 *
 * @since  1.0.0
 * @access public
 * @param  bool    $is_wide
 * @param  string  $widget_id
 * @return bool
 */
function is_wide_widget( $is_wide, $widget_id ) {

	$parsed = parse_widget_id( $widget_id );

	return in_array( $parsed['id_base'], get_wide_widgets() ) ? true : $is_wide;
}

/**
 * Returns an array of wide widgets.
 *
 * @since  1.0.0
 * @access public
 * @return array
 */
function get_wide_widgets() {

	$widgets = array(
		'hybrid-archives',
		'hybrid-authors',
		'hybrid-bookmarks',
		'hybrid-categories',
		'hybrid-nav-menu',
		'hybrid-pages',
		'hybrid-tags'
	);

	return apply_filters( 'widgets_reloaded_wide_widgets', $widgets );
}

/**
 * Converts a widget ID into its id_base and number components. Copied from the core
 * `WP_Customize_Widgets` class.
 *
 * @since  1.0.0
 * @access public
 * @param  string $widget_id
 * @return array
 */
function parse_widget_id( $widget_id ) {

	$parsed = array(
		'number'  => null,
		'id_base' => null,
	);

	if ( preg_match( '/^(.+)-(\d+)$/', $widget_id, $matches ) ) {

		$parsed['id_base'] = $matches[1];
		$parsed['number']  = intval( $matches[2] );

	} else {

		$parsed['id_base'] = $widget_id;
	}

	return $parsed;
}
