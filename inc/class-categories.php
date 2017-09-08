<?php
/**
 * The Categories widget replaces the default WordPress Categories widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_categories() function.
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
 * Categories Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Categories extends Widget {

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
			'classname'                   => 'widget-categories widget_categories',
			'description'                 => esc_html__( 'An advanced widget that gives you total control over the output of your category links.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array(
			'width'  => 525,
			'height' => 350
		);

		// Create the widget.
		parent::__construct( 'hybrid-categories', __( 'Categories', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up the defaults.
		$this->defaults = array(
			'title'              => esc_attr__( 'Categories', 'widgets-reloaded' ),
			'taxonomy'           => 'category',
			'style'              => 'list',
			'include'            => '',
			'exclude'            => '',
			'exclude_tree'       => '',
			'child_of'           => '',
			'current_category'   => '',
			'search'             => '',
			'hierarchical'       => true,
			'hide_empty'         => true,
			'order'              => 'ASC',
			'orderby'            => 'name',
			'depth'              => 0,
			'number'             => '',
			'feed'               => '',
			'feed_type'          => '',
			'feed_image'         => '',
			'use_desc_for_title' => false,
			'show_count'         => false,
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

		// Set the $args for wp_list_categories() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Set the $title_li and $echo arguments to false.
		$args['title_li'] = false;
		$args['echo']     = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $instance );

		// Get the categories list.
		$categories = str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $args ) );

		// If 'list' is the user-selected style, wrap the categories in an unordered list.
		if ( 'list' == $args['style'] )
			$categories = '<ul class="xoxo categories">' . $categories . '</ul><!-- .xoxo .categories -->';

		// If no style is given, wrap in a <p> tag for formatting.
		else
			$categories = '<p class="categories style-none">' . $categories . '</p>';

		// Output the categories list.
		echo $categories;

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

		// If new taxonomy is chosen, reset includes and excludes.
		if ( $new_instance['taxonomy'] !== $old_instance['taxonomy'] )
			$new_instance['include'] = $new_instance['exclude'] = '';

		// Sanitize key.
		$instance['taxonomy'] = sanitize_key( $new_instance['taxonomy'] );

		// Strip tags.
		$instance['title']            = strip_tags( $new_instance['title']            );
		$instance['search']           = strip_tags( $new_instance['search']           );
		$instance['feed']             = strip_tags( $new_instance['feed']             );

		// Whitelist options.
		$order   = array( 'ASC', 'DESC' );
		$orderby = array( 'count', 'ID', 'name', 'slug', 'term_group' );
		$style   = array( 'list', 'none' );
		$feed_type = array( '', 'atom', 'rdf', 'rss', 'rss2' );

		$instance['order']     = in_array( $new_instance['order'],     $order )     ? $new_instance['order']     : 'ASC';
		$instance['orderby']   = in_array( $new_instance['orderby'],   $orderby )   ? $new_instance['orderby']   : 'name';
		$instance['style']     = in_array( $new_instance['style'],     $style )     ? $new_instance['style']     : 'list';
		$instance['feed_type'] = in_array( $new_instance['feed_type'], $feed_type ) ? $new_instance['feed_type'] : '';

		// Integers.
		$instance['number']           = intval( $new_instance['number']           );
		$instance['depth']            = absint( $new_instance['depth']            );
		$instance['child_of']         = absint( $new_instance['child_of']         );
		$instance['current_category'] = absint( $new_instance['current_category'] );

		// Only allow integers and commas.
		$instance['include']      = preg_replace( '/[^0-9,]/', '', $new_instance['include']      );
		$instance['exclude']      = preg_replace( '/[^0-9,]/', '', $new_instance['exclude']      );
		$instance['exclude_tree'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude_tree'] );

		// URLs.
		$instance['feed_image'] = esc_url_raw( $new_instance['feed_image'] );

		// Checkboxes.
		$instance['hierarchical']       = isset( $new_instance['hierarchical'] )       ? 1 : 0;
		$instance['use_desc_for_title'] = isset( $new_instance['use_desc_for_title'] ) ? 1 : 0;
		$instance['show_count']         = isset( $new_instance['show_count'] )         ? 1 : 0;
		$instance['hide_empty']         = isset( $new_instance['hide_empty'] )         ? 1 : 0;

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
		$taxonomies = get_taxonomies( array( 'show_tagcloud' => true ), 'objects' );
		$terms      = get_terms( $instance['taxonomy'] );

		$style = array(
			'list' => esc_attr__( 'List', 'widgets-reloaded' ),
			'none' => esc_attr__( 'None', 'widgets-reloaded' )
		);

		$order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		$orderby = array(
			'count'      => esc_attr__( 'Count',      'widgets-reloaded' ),
			'ID'         => esc_attr__( 'ID',         'widgets-reloaded' ),
			'name'       => esc_attr__( 'Name',       'widgets-reloaded' ),
			'slug'       => esc_attr__( 'Slug',       'widgets-reloaded' ),
			'term_group' => esc_attr__( 'Term Group', 'widgets-reloaded' )
		);

		$feed_type = array(
			''     => '',
			'atom' => esc_attr__( 'Atom',    'widgets-reloaded' ),
			'rdf'  => esc_attr__( 'RDF',     'widgets-reloaded' ),
			'rss'  => esc_attr__( 'RSS',     'widgets-reloaded' ),
			'rss2' => esc_attr__( 'RSS 2.0', 'widgets-reloaded' )
		); ?>

		<div class="hybrid-widget-controls columns-2">

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Taxonomy:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'taxonomy' ); ?>">

					<?php foreach ( $taxonomies as $taxonomy ) : ?>

						<option value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php selected( $instance['taxonomy'], $taxonomy->name ); ?>><?php echo esc_html( $taxonomy->labels->singular_name ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Style:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'style' ); ?>">

					<?php foreach ( $style as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['style'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'order' ); ?>">

					<?php foreach ( $order as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['order'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Order By:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'orderby' ); ?>">

					<?php foreach ( $orderby as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['orderby'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

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
				<?php esc_html_e( 'Child Of:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'child_of' ); ?>" value="<?php echo esc_attr( $instance['child_of'] ); ?>" placeholder="0" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Current Category:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'current_category' ); ?>" value="<?php echo esc_attr( $instance['current_category'] ); ?>" placeholder="0" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Search:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'search' ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
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
				<?php esc_html_e( 'Feed Type:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'feed_type' ); ?>">

					<?php foreach ( $feed_type as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['feed_type'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
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
				<input type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> name="<?php $this->field_name( 'hierarchical' ); ?>" />
				<?php esc_html_e( 'Hierarchical?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['use_desc_for_title'], true ); ?> name="<?php $this->field_name( 'use_desc_for_title' ); ?>" />
				<?php esc_html_e( 'Use description?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_count'], true ); ?> name="<?php $this->field_name( 'show_count' ); ?>" />
				<?php esc_html_e( 'Show count?', 'widgets-reloaded' ); ?>
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
