<?php
/**
 * Plugin Name: Widgets Reloaded
 * Plugin URI: http://justintadlock.com/archives/2008/12/08/widgets-reloaded-wordpress-plugin
 * Description: Replaces many of the default widgets with versions that allow much more control.  Widgets come with highly-customizable control panels.  Each widget can also be used any number of times.
 * Version: 0.2
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * Widgets Reloaded was designed to give users complete control
 * over the output of the default WordPress widgets.  Each widget
 * comes with a highly-customizable settings panel that takes out 
 * all of the work that usually comes with coding.  Each widget can
 * also be used any number of times.
 *
 * @copyright 2008 - 2009
 * @version 0.2
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

/**
 * Yes, we're localizing the plugin.  This partly makes sure non-English
 * users can use it too.  To translate into your language use the
 * en_EN.po file as as guide.  Poedit is a good tool to for translating.
 * @link http://poedit.net
 *
 * @since 0.1
 */
load_plugin_textdomain( 'widgets-reloaded' );

/**
 * Make sure we get the correct directory.
 * @since 0.1
 */
if ( !defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

/**
 * Define constant paths to the plugin folder.
 * @since 0.1
 */
define( 'WIDGETS_RELOADED', WP_PLUGIN_DIR . '/widgets-reloaded'  );

/**
 * Call the function that unregisters the default widgets
 * @since 0.1
 */
add_action( 'widgets_init', 'widgets_reloaded_unregister_widgets' );

/**
 * Load widgets after WP functions have been loaded
 * @since 0.1
 */
add_action( 'widgets_init', 'widgets_reloaded_load_widgets' );

/**
 * Register the extra widgets. Each widget is meant to 
 * replace or extend the current default WordPress widgets.
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 *
 * @since 0.1
 */
function widgets_reloaded_load_widgets() {

	/* Load each widget file. */
	require_once( WIDGETS_RELOADED . '/archives.php' );
	require_once( WIDGETS_RELOADED . '/authors.php' );
	require_once( WIDGETS_RELOADED . '/bookmarks.php' );
	require_once( WIDGETS_RELOADED . '/calendar.php' );
	require_once( WIDGETS_RELOADED . '/categories.php' );
	require_once( WIDGETS_RELOADED . '/pages.php' );
	require_once( WIDGETS_RELOADED . '/search.php' );
	require_once( WIDGETS_RELOADED . '/tags.php' );

	/* Register each widget. */
	register_widget( 'Widgets_Reloaded_Widget_Archives' );
	register_widget( 'Widgets_Reloaded_Widget_Authors' );
	register_widget( 'Widgets_Reloaded_Widget_Bookmarks' );
	register_widget( 'Widgets_Reloaded_Widget_Calendar' );
	register_widget( 'Widgets_Reloaded_Widget_Categories' );
	register_widget( 'Widgets_Reloaded_Widget_Pages' );
	register_widget( 'Widgets_Reloaded_Widget_Search' );
	register_widget( 'Widgets_Reloaded_Widget_Tags' );
}

/**
 * Unregister default WordPress widgets we don't need.
 * The theme adds its own versions of these widgets.
 * @uses unregister_widget() Removes individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 *
 * @since 0.1
 */
function widgets_reloaded_unregister_widgets() {
	unregister_widget( 'WP_Widget_Pages' );
	unregister_widget( 'WP_Widget_Calendar' );
	unregister_widget( 'WP_Widget_Archives' );
	unregister_widget( 'WP_Widget_Links' );
	unregister_widget( 'WP_Widget_Categories' );
	unregister_widget( 'WP_Widget_Recent_Posts' );
	unregister_widget( 'WP_Widget_Search' );
	unregister_widget( 'WP_Widget_Tag_Cloud' );
}

?>