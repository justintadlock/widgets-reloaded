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
 * @version   1.0.0
 * @author    Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2008 - 2015, Justin Tadlock
 * @link      http://themehybrid.com/plugins/widgets-reloaded
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded;

/**
 * Sets up the plugin
 *
 * @since  1.0.0
 * @access public
 */
final class Plugin {

	/**
	 * Stores the directory path for this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $dir;

	/**
	 * Stores the directory URI for this plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $uri;

	/**
	 * Plugin setup.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Defines the directory path and URI for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup() {

		$this->dir = trailingslashit( plugin_dir_path( __FILE__ ) );
		$this->uri = trailingslashit( plugin_dir_url(  __FILE__ ) );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function includes() {

		require_once( $this->dir . 'inc/functions-core.php' );

		require_once( $this->dir . 'inc/widgets/class-widget.php'     );
		require_once( $this->dir . 'inc/widgets/class-archives.php'   );
		require_once( $this->dir . 'inc/widgets/class-authors.php'    );
		require_once( $this->dir . 'inc/widgets/class-bookmarks.php'  );
		require_once( $this->dir . 'inc/widgets/class-calendar.php'   );
		require_once( $this->dir . 'inc/widgets/class-categories.php' );
		require_once( $this->dir . 'inc/widgets/class-nav-menu.php'   );
		require_once( $this->dir . 'inc/widgets/class-pages.php'      );
		require_once( $this->dir . 'inc/widgets/class-search.php'     );
		require_once( $this->dir . 'inc/widgets/class-tags.php'       );
	}

	/**
	 * Sets up necessary actions for the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Load translation files.
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 2 );
	}

	/**
	 * Note that we're using the 'widgets-reloaded' textdomain here.  This is because the widgets
	 * are ported from the Hybrid Core framework.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function i18n() {

		load_plugin_textdomain( 'widgets-reloaded', false, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup();
			$instance->includes();
			$instance->setup_actions();
		}

		return $instance;
	}
}

/**
 * Wrapper function for the main plugin class.
 *
 * @since  1.0.0
 * @access public
 * @return object
 */
function plugin() {

	return Plugin::get_instance();
}

// Launch the plugin!
plugin();
