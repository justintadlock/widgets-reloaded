<?php
/**
 * The Pages widget replaces the default WordPress Pages widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_pages() function.
 *
 * @package    Hybrid
 * @subpackage Widgets
 * @author     Justin Tadlock <justin@justintadlock.com>
 * @copyright  Copyright (c) 2008 - 2015, Justin Tadlock
 * @link       http://themehybrid.com/hybrid-core
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Widgets_Reloaded\Widgets;

/**
 * Pages Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Pages extends Widget {

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
			'classname'                   => 'widget-pages widget_pages',
			'description'                 => esc_html__( 'An advanced widget that gives you total control over the output of your page links.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array(
			'width'  => 525,
			'height' => 350
		);

		// Create the widget.
		parent::__construct( 'hybrid-pages', __( 'Pages', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up the defaults.
		$this->defaults = array(
			'title'        => esc_attr__( 'Pages', 'widgets-reloaded'),
			'post_type'    => 'page',
			'depth'        => 0,
			'number'       => '',
			'offset'       => '',
			'child_of'     => '',
			'include'      => '',
			'exclude'      => '',
			'exclude_tree' => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'authors'      => '',
			'link_before'  => '',
			'link_after'   => '',
			'show_date'    => '',
			'hierarchical' => true,
			'sort_column'  => 'post_title',
			'sort_order'   => 'ASC',
			'date_format'  => get_option( 'date_format' )
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

		// Set the $args for wp_list_pages() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Set the $title_li and $echo to false.
		$args['title_li'] = false;
		$args['echo']     = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $instance );

		// Output the page list.
		echo '<ul class="xoxo pages">' . str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $args ) ) . '</ul>';

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
		$instance['title']       = strip_tags( $new_instance['title']       );
		$instance['meta_key']    = strip_tags( $new_instance['meta_key']    );
		$instance['meta_value']  = strip_tags( $new_instance['meta_value']  );
		$instance['date_format'] = strip_tags( $new_instance['date_format'] );

		// Sanitize key.
		$instance['post_type'] = sanitize_key( $new_instance['post_type'] );

		// Whitelist options.
		$sort_order  = array( 'ASC', 'DESC' );
		$sort_column = array( 'post_author', 'post_date', 'ID', 'menu_order', 'post_modified', 'post_name', 'post_title' );
		$show_date   = array( '', 'created', 'modified' );

		$instance['sort_column'] = in_array( $new_instance['sort_column'], $sort_column ) ? $new_instance['sort_column'] : 'post_title';
		$instance['sort_order']  = in_array( $new_instance['sort_order'],  $sort_order  ) ? $new_instance['sort_order']  : 'ASC';
		$instance['show_date']   = in_array( $new_instance['show_date'],   $show_date   ) ? $new_instance['show_date']   : '';

		// Text boxes. Make sure user can use 'unfiltered_html'.
		$instance['link_before'] = current_user_can( 'unfiltered_html' ) ? $new_instance['link_before'] : wp_filter_post_kses( $new_instance['link_before'] );
		$instance['link_after']  = current_user_can( 'unfiltered_html' ) ? $new_instance['link_after']  : wp_filter_post_kses( $new_instance['link_after']  );

		// Integers.
		$instance['number']   = intval( $new_instance['number']   );
		$instance['depth']    = absint( $new_instance['depth']    );
		$instance['child_of'] = absint( $new_instance['child_of'] );
		$instance['offset']   = absint( $new_instance['offset']   );

		// Only allow integers and commas.
		$instance['include']      = preg_replace( '/[^0-9,]/', '', $new_instance['include']      );
		$instance['exclude']      = preg_replace( '/[^0-9,]/', '', $new_instance['exclude']      );
		$instance['exclude_tree'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude_tree'] );
		$instance['authors']      = preg_replace( '/[^0-9,]/', '', $new_instance['authors']      );

		// Checkboxes.
		$instance['hierarchical'] = isset( $new_instance['hierarchical'] ) ? 1 : 0;

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

		$post_types = get_post_types( array( 'public' => true, 'hierarchical' => true ), 'objects' );

		$sort_order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		$sort_column = array(
			'post_author'   => esc_attr__( 'Author',     'widgets-reloaded' ),
			'post_date'     => esc_attr__( 'Date',       'widgets-reloaded' ),
			'ID'            => esc_attr__( 'ID',         'widgets-reloaded' ),
			'menu_order'    => esc_attr__( 'Menu Order', 'widgets-reloaded' ),
			'post_modified' => esc_attr__( 'Modified',   'widgets-reloaded' ),
			'post_name'     => esc_attr__( 'Slug',       'widgets-reloaded' ),
			'post_title'    => esc_attr__( 'Title',      'widgets-reloaded' )
		);

		$show_date = array(
			''         => '',
			'created'  => esc_attr__( 'Created',  'widgets-reloaded' ),
			'modified' => esc_attr__( 'Modified', 'widgets-reloaded' )
		);

		$meta_key = array_merge( array( '' ), (array) get_meta_keys() ); ?>

		<div class="hybrid-widget-controls columns-2">

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"  placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Post Type:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'post_type' ); ?>">

					<?php foreach ( $post_types as $post_type ) : ?>

						<option value="<?php echo esc_attr( $post_type->name ); ?>" <?php selected( $instance['post_type'], $post_type->name ); ?>><?php echo esc_html( $post_type->labels->singular_name ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'sort_order' ); ?>">

					<?php foreach ( $sort_order as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['sort_order'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order By:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'sort_column' ); ?>">

					<?php foreach ( $sort_column as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['sort_column'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Depth:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'depth' ); ?>" value="<?php echo esc_attr( $instance['depth'] ); ?>" placeholder="0" />
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
				<?php esc_html_e( 'Offset:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'offset' ); ?>" value="<?php echo esc_attr( $instance['offset'] ); ?>" placeholder="0"  />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Child Of:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'child_of' ); ?>" value="<?php echo esc_attr( $instance['child_of'] ); ?>" placeholder="0" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Include:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'include' ); ?>" value="<?php echo esc_attr( $instance['include'] ); ?>" placeholder="1,2,3&hellip;" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Exclude:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'exclude' ); ?>" value="<?php echo esc_attr( $instance['exclude'] ); ?>" placeholder="1,2,3&hellip;" />
			</label>
		</p>

		</div><!-- .hybrid-widget-controls -->

		<div class="hybrid-widget-controls columns-2 column-last">

		<p>
			<label>
				<?php esc_html_e( 'Exclude Tree:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'exclude_tree' ); ?>" value="<?php echo esc_attr( $instance['exclude_tree'] ); ?>" placeholder="1,2,3&hellip;" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Meta Key:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'meta_key' ); ?>">

					<?php foreach ( $meta_key as $meta ) : ?>

						<option value="<?php echo esc_attr( $meta ); ?>" <?php selected( $instance['meta_key'], $meta ); ?>><?php echo esc_html( $meta ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Meta Value:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'meta_value' ); ?>" value="<?php echo esc_attr( $instance['meta_value'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Authors:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'authors' ); ?>" value="<?php echo esc_attr( $instance['authors'] ); ?>" placeholder="1,2,3&hellip;" />
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
				<?php esc_html_e( 'Show Date:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'show_date' ); ?>">

					<?php foreach ( $show_date as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['show_date'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Date Format:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'date_format' ); ?>" value="<?php echo esc_attr( $instance['date_format'] ); ?>" placeholder="<?php echo esc_attr( get_option( 'date_format' ) ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> name="<?php $this->field_name( 'hierarchical' ); ?>" />
				<?php esc_html_e( 'Hierarchical?', 'widgets-reloaded'); ?>
			</label>
		</p>

		</div><!-- .hybrid-widget-controls -->

		<div style="clear:both;">&nbsp;</div>
	<?php }
}
