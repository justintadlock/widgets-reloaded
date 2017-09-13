<?php
/**
 * The Archives widget replaces the default WordPress Archives widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_get_archives() function.
 *
 * @package    Hybrid
 * @subpackage Includes
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded\Widgets;

/**
 * Archives widget class.
 *
 * @since 1.0.0
 */
class Archives extends Widget {

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
			'classname'                   => 'widget-archives widget_archive',
			'description'                 => esc_html__( 'An advanced widget that gives you total control over the output of your archives.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Create the widget.
		parent::__construct( 'hybrid-archives', __( 'Reloaded - Archives', 'widgets-reloaded' ), $widget_options );

		// Set up defaults.
		$this->defaults = array(
			'title'           => esc_attr__( 'Archives', 'widgets-reloaded' ),
			'limit'           => 10,
			'type'            => 'monthly',
			'order'           => 'DESC',
			'format'          => 'html',
			'before'          => '',
			'after'           => '',
			'show_post_count' => false
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

		// Set the $args for wp_get_archives() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Overwrite the $echo argument and set it to false.
		$args['echo'] = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $instance );

		// Get the archives list.
		$archives = str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $args ) );

		// If the archives should be shown in a <select> drop-down.
		if ( 'option' == $args['format'] ) {

			// Create a title for the drop-down based on the archive type.
			if ( 'yearly' == $args['type'] )
				$option_title = esc_html__( 'Select Year', 'widgets-reloaded' );

			elseif ( 'monthly' == $args['type'] )
				$option_title = esc_html__( 'Select Month', 'widgets-reloaded' );

			elseif ( 'weekly' == $args['type'] )
				$option_title = esc_html__( 'Select Week', 'widgets-reloaded' );

			elseif ( 'daily' == $args['type'] )
				$option_title = esc_html__( 'Select Day', 'widgets-reloaded' );

			elseif ( 'postbypost' == $args['type'] || 'alpha' == $args['type'] )
				$option_title = esc_html__( 'Select Post', 'widgets-reloaded' );

			// Output the <select> element and each <option>.
			echo '<p><select name="archive-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>';
				echo '<option value="">' . $option_title . '</option>';
				echo $archives;
			echo '</select></p>';
		}

		// If the format should be an unordered list.
		elseif ( 'html' == $args['format'] ) {
			echo '<ul class="xoxo archives">' . $archives . '</ul><!-- .xoxo .archives -->';
		}

		// All other formats.
		else {
			echo $archives;
		}

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

		// Whitelist options.
		$type   = array( 'alpha', 'daily', 'monthly', 'postbypost', 'weekly', 'yearly' );
		$order  = array( 'ASC', 'DESC' );
		$format = array( 'custom', 'html', 'option' );

		$instance['type']   = in_array( $new_instance['type'], $type )     ? $new_instance['type']   : 'monthly';
		$instance['order']  = in_array( $new_instance['order'], $order )   ? $new_instance['order']  : 'DESC';
		$instance['format'] = in_array( $new_instance['format'], $format ) ? $new_instance['format'] : 'html';

		// Integers.
		$instance['limit'] = intval( $new_instance['limit'] );
		$instance['limit'] = 0 === $instance['limit'] ? '' : $instance['limit'];

		// Text boxes. Make sure user can use 'unfiltered_html'.
		$instance['before'] = current_user_can( 'unfiltered_html' ) ? $new_instance['before'] : wp_filter_post_kses( $new_instance['before'] );
		$instance['after']  = current_user_can( 'unfiltered_html' ) ? $new_instance['after']  : wp_filter_post_kses( $new_instance['after']  );

		// Checkboxes.
		$instance['show_post_count'] = isset( $new_instance['show_post_count'] ) ? 1 : 0;

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

		// Create an array of archive types.
		$type = array(
			'alpha'      => esc_attr__( 'Alphabetical', 'widgets-reloaded' ),
			'daily'      => esc_attr__( 'Daily',        'widgets-reloaded' ),
			'monthly'    => esc_attr__( 'Monthly',      'widgets-reloaded' ),
			'postbypost' => esc_attr__( 'Post By Post', 'widgets-reloaded' ),
			'weekly'     => esc_attr__( 'Weekly',       'widgets-reloaded' ),
			'yearly'     => esc_attr__( 'Yearly',       'widgets-reloaded' )
		);

		// Create an array of order options.
		$order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		// Create an array of archive formats.
		$format = array(
			'custom' => esc_attr__( 'Custom', 'widgets-reloaded' ),
			'html'   => esc_attr__( 'HTML',   'widgets-reloaded' ),
			'option' => esc_attr__( 'Option', 'widgets-reloaded' )
		);
		?>

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Limit:', 'widgets-reloaded' ); ?></label>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'limit' ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" placeholder="10" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Type:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'type' ); ?>">

					<?php foreach ( $type as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['type'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'order' ); ?>">

					<?php foreach ( $order as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Format:', 'widgets-reloaded' ); ?></label>

				<select class="widefat" name="<?php $this->field_name( 'format' ); ?>">

					<?php foreach ( $format as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['format'], $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>

					<?php endforeach; ?>

				</select>
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
				<input type="checkbox" <?php checked( $instance['show_post_count'], true ); ?> name="<?php $this->field_name( 'show_post_count' ); ?>" />
				<?php esc_html_e( 'Show post count?', 'widgets-reloaded' ); ?>
			</label>
		</p>
	<?php }
}
