<?php
/**
 * Plugin Name: Widgets Reloaded
 * Plugin URI:  http://themehybrid.com/plugins/widgets-reloaded
 * Description: Replaces many of the default WordPress widgets with versions that allow much more control.  Widgets come with highly-customizable control panels that provide a ton of flexibility.
 * Version:     1.0.0-dev
 * Author:      Justin Tadlock
 * Author URI:  http://justintadlock.com
 * Text Domain: widgets-reloaded
 * Domain Path: /languages
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
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2015, Justin Tadlock
 * @link      http://themehybrid.com/plugins/widgets-reloaded
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded;

/**
 * Sets up the plugin
 *
 * @since  0.5.0
 * @access public
 */
final class Plugin {

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

		// Set the properties needed by the plugin.
		add_action( 'plugins_loaded', array( $this, 'setup' ), 1 );

		// Load translation files.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );

		// Set up theme support.
		add_action( 'after_setup_theme', array( $this, 'theme_support' ), 12 );

		// Load the plugin includes.
		add_action( 'after_setup_theme', array( $this, 'includes' ), 95 );

		// Register widgets.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// Load admin scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// Make sure widgets are considered wide.
		add_filter( 'is_wide_widget_in_customizer', array( $this, 'is_wide_widget' ), 10, 2 );
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
		require_once( $this->directory_path . 'inc/class-widget.php'      );
		require_once( $this->directory_path . 'inc/class-archives.php'   );
		require_once( $this->directory_path . 'inc/class-authors.php'    );
		require_once( $this->directory_path . 'inc/class-bookmarks.php'  );
		require_once( $this->directory_path . 'inc/class-calendar.php'   );
		require_once( $this->directory_path . 'inc/class-categories.php' );
		require_once( $this->directory_path . 'inc/class-nav-menu.php'   );
		require_once( $this->directory_path . 'inc/class-pages.php'      );
		require_once( $this->directory_path . 'inc/class-search.php'     );
		require_once( $this->directory_path . 'inc/class-tags.php'       );
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

		// Unregister the default WordPress widgets.
		if ( apply_filters( 'widgets_reloaded_disable_core_widgets', false ) ) {

			unregister_widget( 'WP_Widget_Archives'   );
			unregister_widget( 'WP_Widget_Calendar'   );
			unregister_widget( 'WP_Widget_Categories' );
			unregister_widget( 'WP_Widget_Links'      );
			unregister_widget( 'WP_Nav_Menu_Widget'   );
			unregister_widget( 'WP_Widget_Pages'      );
			unregister_widget( 'WP_Widget_Search'     );
			unregister_widget( 'WP_Widget_Tag_Cloud'  );
		}

		// Register custom widgets.
		register_widget( 'Widgets_Reloaded\Widgets\Archives'   );
		register_widget( 'Widgets_Reloaded\Widgets\Authors'    );
		register_widget( 'Widgets_Reloaded\Widgets\Calendar'   );
		register_widget( 'Widgets_Reloaded\Widgets\Categories' );
		register_widget( 'Widgets_Reloaded\Widgets\Nav_Menu'   );
		register_widget( 'Widgets_Reloaded\Widgets\Pages'      );
		register_widget( 'Widgets_Reloaded\Widgets\Search'     );
		register_widget( 'Widgets_Reloaded\Widgets\Tags'       );

		if ( get_option( 'link_manager_enabled' ) )
			register_widget( 'Widgets_Reloaded\Widgets\Bookmarks' );
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
			wp_enqueue_style( 'widgets-reloaded', "{$this->directory_uri}css/admin.css" );
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
	public function is_wide_widget( $is_wide, $widget_id ) {

		$widgets = array(
			'hybrid-archives',
			'hybrid-authors',
			'hybrid-bookmarks',
			'hybrid-categories',
			'hybrid-nav-menu',
			'hybrid-pages',
			'hybrid-tags'
		);

		$parsed = $this->parse_widget_id( $widget_id );

		return in_array( $parsed['id_base'], $widgets ) ? true : $is_wide;
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
	public function parse_widget_id( $widget_id ) {

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

Plugin::get_instance();
