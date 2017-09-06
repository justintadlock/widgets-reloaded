<?php
/**
 * Base widget class.  Technically, it's a sub-class of core WordPress' `WP_Widget` class.
 * However, it's used as a base for the classes in this plugin.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded;

/**
 * Archives widget class.
 *
 * @since  1.0.0
 * @access public
 */
abstract class Widget extends \WP_Widget {

	/**
	 * Default arguments for the widget settings.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    array
	 */
	public $defaults = array();

	/**
	 * Outputs the formatted widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $args
	 * @param  array  $instance
	 * @return void
	 */
	public function widget_title( $args, $instance ) {

		if ( $instance['title'] )
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $args['after_title'];
	}

	/**
	 * Outputs the escaped version of the field name.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function field_name( $name ) {

		echo esc_attr( $this->get_field_name( $name ) );
	}

	/**
	 * Outputs the escaped version of the field ID.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $name
	 * @return void
	 */
	public function field_id( $name ) {

		echo esc_attr( $this->get_field_id( $name ) );
	}
}
