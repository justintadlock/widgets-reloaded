<?php
/**
 * Plugin Name: Widgets Reloaded
 * Plugin URI: http://themehybrid.com/plugins/widgets-reloaded
 * Description: Replaces many of the default WordPress widgets with versions that allow much more control.  Widgets come with highly-customizable control panels that provide a ton of flexibility.
 * Version: 0.6.0-beta-1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 * Text Domain: widgets-reloaded
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation; either version 2 of the License, 
 * or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * You should have received a copy of the GNU General Public License along with this program; if not, write 
 * to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 * @package   WidgetsReloaded
 * @version   0.6.0
 * @since     0.1.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2013, Justin Tadlock
 * @link      http://themehybrid.com/plugins/widgets-reloaded
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Sets up the plugin
 *
 * @since 0.5.0
 */
final class Widgets_Reloaded_Plugin {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    object
	 */
	private static $instance;

	/**
	 * Stores the directory path for this plugin.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    string
	 */
	private $directory_path;

	/**
	 * Stores the directory URI for this plugin.
	 *
	 * @since  0.5.0
	 * @access private
	 * @var    string
	 */
	private $directory_uri;

	/**
	 * Plugin setup.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		/* Set the properties needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'setup' ), 1 );

		/* Load translation files. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		/* Set up theme support. */
		add_action( 'after_setup_theme', array( $this, 'theme_support' ), 12 );

		/* Load the plugin includes. */
		add_action( 'after_setup_theme', array( $this, 'includes' ), 95 );

		/* Register widgets. */
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		/* Load admin scripts and styles. */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Defines the directory path and URI for the plugin.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function setup() {
		$this->directory_path = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->directory_uri  = trailingslashit( plugin_dir_url(  __FILE__ ) );
	}

	/**
	 * Removes 'hybrid-core-widgets' theme support.  This is so that the plugin will take over the 
	 * widgets instead of themes built on Hybrid Core.  Plugin updates can get out quicker to users, 
	 * so the plugin should have priority.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function theme_support() {
		remove_theme_support( 'hybrid-core-widgets' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function includes() {
		require_once( $this->directory_path . 'inc/widget-archives.php' );
		require_once( $this->directory_path . 'inc/widget-authors.php' );
		require_once( $this->directory_path . 'inc/widget-bookmarks.php' );
		require_once( $this->directory_path . 'inc/widget-calendar.php' );
		require_once( $this->directory_path . 'inc/widget-categories.php' );
		require_once( $this->directory_path . 'inc/widget-nav-menu.php' );
		require_once( $this->directory_path . 'inc/widget-pages.php' );
		require_once( $this->directory_path . 'inc/widget-search.php' );
		require_once( $this->directory_path . 'inc/widget-tags.php' );
	}

	/**
	 * Note that we're using the 'widgets-reloaded' textdomain here.  This is because the widgets 
	 * are ported from the Hybrid Core framework.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function i18n() {
		load_plugin_textdomain( 'widgets-reloaded', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Registers widget files.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function register_widgets() {

		/* Unregister the default WordPress widgets. */
		unregister_widget( 'WP_Widget_Archives' );
		unregister_widget( 'WP_Widget_Calendar' );
		unregister_widget( 'WP_Widget_Categories' );
		unregister_widget( 'WP_Widget_Links' );
		unregister_widget( 'WP_Nav_Menu_Widget' );
		unregister_widget( 'WP_Widget_Pages' );
		unregister_widget( 'WP_Widget_Search' );
		unregister_widget( 'WP_Widget_Tag_Cloud' );

		/* Register the archives widget. */
		register_widget( 'Hybrid_Widget_Archives' );

		/* Register the authors widget. */
		register_widget( 'Hybrid_Widget_Authors' );

		/* Register the bookmarks widget. */
		if ( get_option( 'link_manager_enabled' ) )
			register_widget( 'Hybrid_Widget_Bookmarks' );

		/* Register the calendar widget. */
		register_widget( 'Hybrid_Widget_Calendar' );

		/* Register the categories widget. */
		register_widget( 'Hybrid_Widget_Categories' );

		/* Register the nav menu widget. */
		register_widget( 'Hybrid_Widget_Nav_Menu' );

		/* Register the pages widget. */
		register_widget( 'Hybrid_Widget_Pages' );

		/* Register the search widget. */
		register_widget( 'Hybrid_Widget_Search' );

		/* Register the tags widget. */
		register_widget( 'Hybrid_Widget_Tags' );
	}

	/**
	 * Loads admin CSS files.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		if ( 'widgets.php' == $hook_suffix )
			wp_enqueue_style( 'widgets-reloaded', "{$this->directory_uri}css/admin.min.css" );
	}

	/**
	 * Returns the instance.
	 *
	 * @since  0.5.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}

Widgets_Reloaded_Plugin::get_instance();

?>