<?php
/*
Plugin Name: Widgets Reloaded
Plugin URI: http://justintadlock.com/archives/2008/12/08/widgets-reloaded-wordpress-plugin
Description: Replaces many of the default widgets with versions that allow much more control.  Widgets come with highly customizable control panels.  Each widget can also be used any number of times.
Version: 0.1.1
Author: Justin Tadlock
Author URI: http://justintadlock.com
*/

/*
* Copyright (c) 2008 Justin Tadlock.  All rights reserved.
* http://justintadlock.com
*
* Widgets Reloaded is released under the GNU General Public License, version 2 (GPL).
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

// Localization
	load_plugin_textdomain('reloaded');

// Make sure we get the correct directory
	if(!defined('WP_CONTENT_DIR'))
		define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
	if(!defined('WP_PLUGIN_DIR'))
		define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

// Define constant path to plugin folder
	define(RELOADED_WIDGETS, WP_PLUGIN_DIR . '/widgets-reloaded');

// Call the function that unregisters the default widgets
	add_action('widgets_init', 'reloaded_unregister_widgets');

// Load widgets after WP functions have been loaded
	add_action('plugins_loaded', 'reloaded_load_widgets');

/**
* Loads all the widget files
* Calls the register function for each widget
*
* @since 0.1
*/
function reloaded_load_widgets() {

	require_once(RELOADED_WIDGETS . '/archives.php');
	require_once(RELOADED_WIDGETS . '/authors.php');
	require_once(RELOADED_WIDGETS . '/bookmarks.php');
	require_once(RELOADED_WIDGETS . '/categories.php');
	require_once(RELOADED_WIDGETS . '/pages.php');
	require_once(RELOADED_WIDGETS . '/search.php');
	require_once(RELOADED_WIDGETS . '/tags.php');

	widget_reloaded_archives_register();
	widget_reloaded_authors_register();
	widget_reloaded_bookmarks_register();
	widget_reloaded_categories_register();
	widget_reloaded_pages_register();
	widget_reloaded_search_register();
	widget_reloaded_tags_register();
}

/**
* Unregister default WordPress widgets
* Replace them with enhanced widgets
* Each replacement widget should be a "multi-widget"
* Each should also try to cover all possible parameters in the widget controls
*
* @since 0.1
*/
function reloaded_unregister_widgets() {
	unregister_sidebar_widget('links');
	unregister_sidebar_widget('pages');
	unregister_sidebar_widget('categories-1');
	unregister_sidebar_widget('archives');
	unregister_sidebar_widget('recent-posts');
	unregister_sidebar_widget('tag_cloud');
	unregister_sidebar_widget('search');
}

?>