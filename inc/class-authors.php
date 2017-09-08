<?php
/**
 * The authors widget was created to give users the ability to list the authors of their blog because
 * there was no equivalent WordPress widget that offered the functionality. This widget allows full
 * control over its output by giving access to the parameters of wp_list_authors().
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
 * Authors Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Authors extends Widget {

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
			'classname'                   => 'widget-authors',
			'description'                 => esc_html__( 'An advanced widget that gives you total control over the output of your author lists.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array(
			'width'  => 525,
			'height' => 350
		);

		// Create the widget.
		parent::__construct( 'hybrid-authors', __( 'Authors', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up defaults.
		$this->defaults = array(
			'title'         => esc_attr__( 'Authors', 'widgets-reloaded' ),
			'order'         => 'ASC',
			'orderby'       => 'display_name',
			'number'        => '',
			'include'       => '',
			'exclude'       => '',
			'optioncount'   => false,
			'exclude_admin' => false,
			'show_fullname' => true,
			'hide_empty'    => true,
			'style'         => 'list',
			'html'          => true,
			'feed'          => '',
			'feed_image'    => ''
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

		// Set the $args for wp_list_authors() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Overwrite the $echo argument and set it to false.
		$args['echo'] = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $instance );

		// Get the authors list.
		$authors = str_replace( array( "\r", "\n", "\t" ), '', wp_list_authors( $args ) );

		// If 'list' is the style and the output should be HTML, wrap the authors in a <ul>.
		if ( 'list' == $args['style'] && $args['html'] )
			$authors = '<ul class="xoxo authors">' . $authors . '</ul><!-- .xoxo .authors -->';

		// If 'none' is the style and the output should be HTML, wrap the authors in a <p>.
		elseif ( 'none' == $args['style'] && $args['html'] )
			$authors = '<p class="authors">' . $authors . '</p><!-- .authors -->';

		// Display the authors list.
		echo $authors;

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

		// Strip tags.
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['feed']  = strip_tags( $new_instance['feed']  );

		// Whitelist options.
		$order   = array( 'ASC', 'DESC' );
		$orderby = array( 'display_name', 'email', 'ID', 'nicename', 'post_count', 'registered', 'url', 'user_login' );
		$style   = array( 'list', 'none' );

		$instance['order']   = in_array( $new_instance['order'], $order )     ? $new_instance['order']   : 'ASC';
		$instance['orderby'] = in_array( $new_instance['orderby'], $orderby ) ? $new_instance['orderby'] : 'display_name';
		$instance['style']   = in_array( $new_instance['style'], $style )     ? $new_instance['style']   : 'list';

		// Integers.
		$instance['number'] = intval( $new_instance['number'] );

		// Only allow integers and commas.
		$instance['include'] = preg_replace( '/[^0-9,]/', '', $new_instance['include'] );
		$instance['exclude'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude'] );

		// URLs.
		$instance['feed_image'] = esc_url_raw( $new_instance['feed_image'] );

		// Checkboxes.
		$instance['html']          = isset( $new_instance['html'] )          ? 1 : 0;
		$instance['optioncount']   = isset( $new_instance['optioncount'] )   ? 1 : 0;
		$instance['exclude_admin'] = isset( $new_instance['exclude_admin'] ) ? 1 : 0;
		$instance['show_fullname'] = isset( $new_instance['show_fullname'] ) ? 1 : 0;
		$instance['hide_empty']    = isset( $new_instance['hide_empty'] )    ? 1 : 0;

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

		$order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		$orderby = array(
			'display_name' => esc_attr__( 'Display Name', 'widgets-reloaded' ),
			'email'        => esc_attr__( 'Email',        'widgets-reloaded' ),
			'ID'           => esc_attr__( 'ID',           'widgets-reloaded' ),
			'nicename'     => esc_attr__( 'Nice Name',    'widgets-reloaded' ),
			'post_count'   => esc_attr__( 'Post Count',   'widgets-reloaded' ),
			'registered'   => esc_attr__( 'Registered',   'widgets-reloaded' ),
			'url'          => esc_attr__( 'URL',          'widgets-reloaded' ),
			'user_login'   => esc_attr__( 'Login',        'widgets-reloaded' )
		);

		$style = array(
			'list' => esc_attr__( 'List', 'widgets-reloaded'),
			'none' => esc_attr__( 'None', 'widgets-reloaded' )
		);

		?>

		<div class="hybrid-widget-controls columns-2">

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" id="<?php $this->field_id( 'order' ); ?>" name="<?php $this->field_name( 'order' ); ?>">

					<?php foreach ( $order as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order By:', 'widgets-reloaded' ); ?>

				<select class="widefat" id="<?php $this->field_id( 'orderby' ); ?>" name="<?php $this->field_name( 'orderby' ); ?>">

					<?php foreach ( $orderby as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Number:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'number' ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" placeholder="0" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Style:', 'widgets-reloaded' ); ?>

				<select class="widefat" id="<?php $this->field_id( 'style' ); ?>" name="<?php $this->field_name( 'style' ); ?>">

					<?php foreach ( $style as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['style'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Include:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'include' ); ?>" value="<?php echo esc_attr( $instance['include'] ); ?>" placeholder="1,2,3&hellip;" />
			</label>
		</p>

		</div><!-- .hybrid-widget-controls -->

		<div class="hybrid-widget-controls columns-2 column-last">

		<p>
			<label>
				<?php esc_html_e( 'Exclude:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'exclude' ); ?>" value="<?php echo esc_attr( $instance['exclude'] ); ?>" placeholder="1,2,3&hellip;" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Feed:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'feed' ); ?>" value="<?php echo esc_attr( $instance['feed'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Feed Image:', 'widgets-reloaded' ); ?>
				<input type="url" class="widefat code" name="<?php $this->field_name( 'feed_image' ); ?>" value="<?php echo esc_attr( $instance['feed_image'] ); ?>" placeholder="<?php echo esc_attr( home_url( 'images/example.png' ) ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['html'], true ); ?> name="<?php $this->field_name( 'html' ); ?>" />
				<?php esc_html_e( 'HTML?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['optioncount'], true ); ?> name="<?php $this->field_name( 'optioncount' ); ?>" />
				<?php esc_html_e( 'Show post count?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['exclude_admin'], true ); ?> name="<?php $this->field_name( 'exclude_admin' ); ?>" />
				<?php esc_html_e( 'Exclude admin?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_fullname'], true ); ?> name="<?php $this->field_name( 'show_fullname' ); ?>" />
				<?php esc_html_e( 'Show full name?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> name="<?php $this->field_name( 'hide_empty' ); ?>" />
				<?php esc_html_e( 'Hide empty?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		</div><!-- .hybrid-widget-controls -->

		<div style="clear:both;">&nbsp;</div>
	<?php }
}
