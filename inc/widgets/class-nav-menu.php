<?php
/**
 * The nav menu widget was created to give users the ability to show nav menus created from the
 * Menus screen, by the theme, or by plugins using the wp_nav_menu() function.  It replaces the default
 * WordPress navigation menu class.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justintadlock@gmail.com>
 * @copyright  Copyright (c) 2008 - 2017, Justin Tadlock
 * @link       https://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded\Widgets;

/**
 * Nav Menu Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Nav_Menu extends Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Set up the widget options.
		$widget_options = array(
			'classname'                   => 'widget-nav-menu widget_nav_menu',
			'description'                 => esc_html__( 'Displays a custom menu.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array( 'width' => 525 );

		// Create the widget.
		parent::__construct( 'hybrid-nav-menu', __( 'Reloaded - Menu', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up the defaults.
		$this->defaults = array(
			'title'           => esc_attr__( 'Navigation', 'widgets-reloaded' ),
			'menu'            => '',
			'container'       => 'div',
			'container_id'    => '',
			'container_class' => '',
			'menu_id'         => '',
			'menu_class'      => 'nav-menu',
			'depth'           => 0,
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'fallback_cb'     => 'wp_page_menu',
		);
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $sidebar
	 * @param  array  $instance
	 * @return void
	 */
	public function widget( $sidebar, $instance ) {

		// Set the $args for wp_nav_menu() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Overwrite the $echo argument and set it to false.
		$args['echo'] = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $args );

		// Output the nav menu.
		echo str_replace( array( "\r", "\n", "\t" ), '', wp_nav_menu( $args ) );

		// Close the sidebar's widget wrapper.
		echo $sidebar['after_widget'];
	}

	/**
	 * The update callback for the widget control options.  This method is used to sanitize and/or
	 * validate the options before saving them into the database.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $new_instance
	 * @param  array  $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		// Sanitize title.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Strip tags.
		$instance['menu'] = strip_tags( $new_instance['menu']  );

		// Whitelist options.
		$container = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );

		$instance['container'] = in_array( $new_instance['container'], $container ) ? $new_instance['container'] : 'div';

		// Integers.
		$instance['depth'] = absint( $new_instance['depth'] );

		// HTML class.
		$instance['container_id']    = sanitize_html_class( $new_instance['container_id']    );
		$instance['container_class'] = sanitize_html_class( $new_instance['container_class'] );
		$instance['menu_id']         = sanitize_html_class( $new_instance['menu_id']         );
		$instance['menu_class']      = sanitize_html_class( $new_instance['menu_class']      );

		// Text boxes. Make sure user can use 'unfiltered_html'.
		$instance['before']      = current_user_can( 'unfiltered_html' ) ? $new_instance['before']      : wp_filter_post_kses( $new_instance['before']      );
		$instance['after']       = current_user_can( 'unfiltered_html' ) ? $new_instance['after']       : wp_filter_post_kses( $new_instance['after']       );
		$instance['link_before'] = current_user_can( 'unfiltered_html' ) ? $new_instance['link_before'] : wp_filter_post_kses( $new_instance['link_before'] );
		$instance['link_after']  = current_user_can( 'unfiltered_html' ) ? $new_instance['link_after']  : wp_filter_post_kses( $new_instance['link_after']  );

		// Function name.
		$instance['fallback_cb'] = empty( $new_instance['fallback_cb'] ) || function_exists( $new_instance['fallback_cb'] ) ? $new_instance['fallback_cb'] : 'wp_page_menu';

		// Return sanitized options.
		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  array  $instance
	 * @param  void
	 */
	public function form( $instance ) {

		// Merge the user-selected arguments with the defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$container = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) ); ?>

		<div class="reloaded-section reloaded-col-2">

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Menu:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'menu' ); ?>">
					<option value=""></option>

					<?php foreach ( wp_get_nav_menus() as $menu ) : ?>

						<option value="<?php echo esc_attr( $menu->term_id ); ?>" <?php selected( $instance['menu'], $menu->term_id ); ?>><?php echo esc_html( $menu->name ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>
		<p>
			<label>
				<?php esc_html_e( 'Container:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'container' ); ?>">

					<?php foreach ( $container as $option ) : ?>

						<option value="<?php echo esc_attr( $option ); ?>" <?php selected( $instance['container'], $option ); ?>><?php echo esc_html( $option ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Container ID:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'container_id' ); ?>" value="<?php echo esc_attr( $instance['container_id'] ); ?>" placeholder="example" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Container Class:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'container_class' ); ?>" value="<?php echo esc_attr( $instance['container_class'] ); ?>" placeholder="example" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Menu ID:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'menu_id' ); ?>" value="<?php echo esc_attr( $instance['menu_id'] ); ?>" placeholder="example" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Menu Class:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'menu_class' ); ?>" value="<?php echo esc_attr( $instance['menu_class'] ); ?>" placeholder="example" />
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div class="reloaded-section reloaded-col-2">

		<p>
			<label>
				<?php esc_html_e( 'Depth:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'depth' ); ?>" value="<?php echo esc_attr( $instance['depth'] ); ?>" placeholder="0" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Before:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'before' ); ?>" value="<?php echo esc_attr( $instance['before'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'After:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'after' ); ?>" value="<?php echo esc_attr( $instance['after'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Link Before:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'link_before' ); ?>" value="<?php echo esc_attr( $instance['link_before'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Link After:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'link_after' ); ?>" value="<?php echo esc_attr( $instance['link_after'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Fallback Callback Function:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'fallback_cb' ); ?>" value="<?php echo esc_attr( $instance['fallback_cb'] ); ?>" placeholder="wp_page_menu" />
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div style="clear:both;">&nbsp;</div>
	<?php }
}
