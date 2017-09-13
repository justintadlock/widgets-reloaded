<?php
/**
 * Custom recent posts widget.
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
 * Posts widget class.
 *
 * @since  1.0.0
 * @access public
 */
class Posts extends Widget {

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
			'classname'                   => 'widget-posts widget_recent_entries',
			'description'                 => esc_html__( "Displays a list of your site's posts.", 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Create the widget.
		parent::__construct( 'reloaded-posts', __( 'Reloaded - Posts', 'widgets-reloaded' ), $widget_options );

		// Set up the defaults.
		$this->defaults = array(
			'title'     => esc_attr__( 'Recent Posts', 'widgets-reloaded' ),
			'post_type' => array( 'post' ),
			'order'     => 'DESC',
			'orderby'   => 'date',
			'number'    => 10,
			'show_date' => false
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
		$instance = wp_parse_args( $instance, $this->defaults );

		$loop = new \WP_Query(
			array(
				'posts_per_page'      => $instance['number'],
				'post_type'           => $instance['post_type'],
				'order'               => $instance['order'],
				'orderby'             => $instance['orderby'],
				'no_found_rows'       => true,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => true,
			)
		);

		if ( $loop->have_posts() ) : ?>

			<?php echo $sidebar['before_widget']; ?>

			<?php $this->widget_title( $sidebar, $instance ); ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<li>
					<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>

					<?php if ( $instance['show_date'] ) : ?>
						<span class="post-date"><?php echo get_the_date(); ?></span>
					<?php endif; ?>
				</li>

			<?php endwhile; ?>

			<?php echo $sidebar['after_widget']; ?>

			<?php wp_reset_postdata();

		endif;
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

		// Sanitize key.
		$instance['post_type'] = array_map( 'sanitize_key', $new_instance['post_type'] );

		// Whitelist options.
		$order   = array( 'ASC', 'DESC' );
		$orderby = array( 'author', 'name', 'none', 'type', 'date', 'ID', 'modified', 'parent', 'comment_count', 'menu_order', 'title' );

		$instance['order']   = in_array( $new_instance['order'],   $order )   ? $new_instance['order']   : 'DESC';
		$instance['orderby'] = in_array( $new_instance['orderby'], $orderby ) ? $new_instance['orderby'] : 'date';

		// Integers.
		$instance['number']   = intval( $new_instance['number'] );

		// Checkboxes.
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? 1 : 0;

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

		// <select> element options.
		$types = get_post_types( array( 'public' => true ), 'objects' );

		$order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		$orderby = array(
			'author'        => esc_attr__( 'Author',          'widgets-reloaded' ),
			'name'          => esc_attr__( 'Slug',            'widgets-reloaded' ),
			'none'          => esc_attr__( 'None',            'widgets-reloaded' ),
			'type'          => esc_attr__( 'Type',            'widgets-reloaded' ),
			'date'          => esc_attr__( 'Date',            'widgets-reloaded' ),
			'ID'            => esc_attr__( 'ID',              'widgets-reloaded' ),
			'modified'      => esc_attr__( 'Date (Modified)', 'widgets-reloaded' ),
			'parent'        => esc_attr__( 'Parent',          'widgets-reloaded' ),
			'comment_count' => esc_attr__( 'Comment Count',   'widgets-reloaded' ),
			'menu_order'    => esc_attr__( 'Menu Order',      'widgets-reloaded' ),
			'title'         => esc_attr__( 'Title',           'widgets-reloaded' )
		); ?>

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<div class="reloaded-control">
			<?php esc_html_e( 'Post Type:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>
				<?php foreach ( $types as $type ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'post_type' ); ?>[]" value="<?php echo esc_attr( $type->name ); ?>" <?php checked( in_array( $type->name, (array)$instance['post_type'] ) ); ?> />
							<?php echo esc_html( $type->labels->singular_name ); ?>
						</label>
					</li>

				<?php endforeach; ?>
				</ul>
			</div>
		</div>

		<p>
			<label>
				<?php esc_html_e( 'Number:', 'widgets-reloaded' ); ?>
				<input type="number" min="1" size="3" class="widefat code" name="<?php $this->field_name( 'number' ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['number'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'order' ); ?>">

					<?php foreach ( $order as $option_value => $option_label ) : ?>

						<option value="<?php echo $option_value; ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo $option_label; ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order By:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'orderby' ); ?>">

					<?php foreach ( $orderby as $option_value => $option_label ) : ?>

						<option value="<?php echo $option_value; ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo $option_label; ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_date'], true ); ?> name="<?php $this->field_name( 'show_date' ); ?>" />
				<?php esc_html_e( 'Display post date?', 'widgets-reloaded' ); ?>
			</label>
		</p>
	<?php }
}
