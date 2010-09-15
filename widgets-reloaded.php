<?php
/**
 * Plugin Name: Widgets Reloaded
 * Plugin URI: http://justintadlock.com/archives/2008/12/08/widgets-reloaded-wordpress-plugin
 * Description: Replaces many of the default widgets with versions that allow much more control.  Widgets come with highly-customizable control panels.  Each widget can also be used any number of times.
 * Version: 0.4.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * Widgets Reloaded was designed to give users complete control over the output of the default 
 * WordPress widgets.  Each widget comes with a highly-customizable settings panel that takes out 
 * all of the work that usually comes with coding.  Each widget can also be used any number of times.
 * Rather than recoding each widget, the widgets are ported over from the Hybrid theme framework.
 *
 * @copyright 2008 - 2010
 * @version 0.4.1
 * @author Justin Tadlock
 * @link http://justintadlock.com/archives/2008/12/08/widgets-reloaded-wordpress-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package WidgetsReloaded
 */

/* Launch the plugin. */
add_action( 'plugins_loaded', 'widgets_reloaded_plugin_init' );

/**
 * Initializes the plugin and it's features.
 *
 * @since 0.3
 */
function widgets_reloaded_plugin_init() {

	/* Set constant path to the members plugin directory. */
	define( WIDGETS_RELOADED_DIR, plugin_dir_path( __FILE__ ) );

	/* Set constant path to the members plugin directory. */
	define( WIDGETS_RELOADED_URL, plugin_dir_url( __FILE__ ) );

	/* Load the translation of the plugin. */
	load_plugin_textdomain( 'widgets-reloaded', false, 'widgets-reloaded/languages' );

	/* Unregisters the default widgets. */
	add_action( 'widgets_init', 'widgets_reloaded_unregister_widgets' );

	/* Loads and registers the new widgets. */
	add_action( 'widgets_init', 'widgets_reloaded_load_widgets' );

	/* Load the admin stylesheet for the widgets screen. */
	add_action( 'load-widgets.php', 'widgets_reloaded_enqueue_style' );
}

/**
 * Load the stylesheet needed for the widgets page.
 *
 * @since 0.4
 */
function widgets_reloaded_enqueue_style() {
	wp_enqueue_style( hybrid_get_prefix() . '-admin', WIDGETS_RELOADED_URL . 'admin.css', false, 0.4, 'screen' );
}

/**
 * Compatibility function since the widgets are based off the Hybrid framework.
 *
 * @since 0.3
 */
function hybrid_get_prefix() {
	return 'widgets-reloaded';
}

/**
 * Compatibility function since the widgets are based off the Hybrid framework.
 *
 * @since 0.3
 */
function hybrid_get_textdomain() {
	return 'widgets-reloaded';
}

/**
 * Compatibility function since the widgets are based off the Hybrid framework.
 *
 * @since 0.4
 */
function hybrid_get_transient_expiration() {
	return apply_filters( hybrid_get_prefix() . '_transient_expiration', 43200 );
}

/**
 * Unregister default WordPress widgets we don't need. The plugin adds its own versions 
 * of these widgets.
 *
 * @since 0.1
 * @uses unregister_widget() Removes individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 */
function widgets_reloaded_unregister_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Nav_Menu_Widget' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

/**
 * Register the extra widgets. Each widget is meant to replace or extend the current default 
 * WordPress widgets.
 *
 * @since 0.1
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 */
function widgets_reloaded_load_widgets() {

	/* Load each widget file. */
	require_once( WIDGETS_RELOADED_DIR . 'widget-archives.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-authors.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-bookmarks.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-calendar.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-categories.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-nav-menu.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-pages.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-search.php' );
	require_once( WIDGETS_RELOADED_DIR . 'widget-tags.php' );

	/* Register each widget. */
	register_widget( 'Hybrid_Widget_Archives' );
	register_widget( 'Hybrid_Widget_Authors' );
	register_widget( 'Hybrid_Widget_Bookmarks' );
	register_widget( 'Hybrid_Widget_Calendar' );
	register_widget( 'Hybrid_Widget_Categories' );
	register_widget( 'Hybrid_Widget_Nav_Menu' );
	register_widget( 'Hybrid_Widget_Pages' );
	register_widget( 'Hybrid_Widget_Search' );
	register_widget( 'Hybrid_Widget_Tags' );
}

?>