<?php
/**
 * The Bookmarks widget replaces the default WordPress Links widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_bookmarks() function.
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
 * Bookmarks Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Bookmarks extends Widget {

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
			'classname'                   => 'widget-bookmarks widget_links',
			'description'                 => esc_html__( 'Displays a list of bookmarks (links).', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array(
			'width'  => 750,
			'height' => 350
		);

		// Create the widget.
		parent::__construct( 'hybrid-bookmarks', __( 'Reloaded - Bookmarks', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up the defaults.
		$this->defaults = array(
			'title_li'         => esc_attr__( 'Bookmarks', 'widgets-reloaded' ),
			'categorize'       => true,
			'category_order'   => 'ASC',
			'category_orderby' => 'name',
			'category'         => array(),
			'exclude_category' => array(),
			'limit'            => -1,
			'order'            => 'ASC',
			'orderby'          => 'name',
			'include'          => array(),
			'exclude'          => array(),
			'search'           => '',
			'hide_invisible'   => true,
			'show_description' => false,
			'show_images'      => false,
			'show_rating'      => false,
			'show_updated'     => false,
			'show_private'     => false,
			'show_name'        => false,
			'class'            => 'linkcat',
			'link_before'      => '',
			'link_after'       => '',
			'between'          => '&thinsp;&ndash;&thinsp;',
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

		// Set up the $before_widget ID for multiple widgets created by the bookmarks widget.
		if ( !empty( $instance['categorize'] ) )
			$sidebar['before_widget'] = preg_replace( '/id="[^"]*"/','id="%id"', $sidebar['before_widget'] );

		// Add a class to $before_widget if one is set.
		if ( !empty( $instance['class'] ) )
			$sidebar['before_widget'] = str_replace( 'class="', 'class="' . esc_attr( $instance['class'] ) . ' ', $sidebar['before_widget'] );

		// Set the $args for wp_list_bookmarks() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// wp_list_bookmarks() hasn't been updated in WP to use wp_parse_id_list(), so we have to pass strings for includes/excludes.
		if ( !empty( $args['category'] ) && is_array( $args['category'] ) )
			$args['category'] = join( ', ', $args['category'] );

		if ( !empty( $args['exclude_category'] ) && is_array( $args['exclude_category'] ) )
			$args['exclude_category'] = join( ', ', $args['exclude_category'] );

		if ( !empty( $args['include'] ) && is_array( $args['include'] ) )
			$args['include'] = join( ',', $args['include'] );

		if ( !empty( $args['exclude'] ) && is_array( $args['exclude'] ) )
			$args['exclude'] = join( ',', $args['exclude'] );

		// If no limit is given, set it to -1.
		$args['limit'] = empty( $args['limit'] ) ? -1 : $args['limit'];

		// Some arguments must be set to the sidebar arguments to be output correctly.
		$args['title_li']        = apply_filters( 'widget_title', ( empty( $args['title_li'] ) ? __( 'Bookmarks', 'widgets-reloaded' ) : $args['title_li'] ), $instance, $this->id_base );
		$args['title_before']    = $sidebar['before_title'];
		$args['title_after']     = $sidebar['after_title'];
		$args['category_before'] = $sidebar['before_widget'];
		$args['category_after']  = $sidebar['after_widget'];
		$args['category_name']   = '';
		$args['echo']            = false;

		// Output the bookmarks widget.
		$bookmarks = str_replace( array( "\r", "\n", "\t" ), '', wp_list_bookmarks( $args ) );

		// If no title is given and the bookmarks aren't categorized, add a wrapper <ul>.
		if ( empty( $args['title_li'] ) && false === $args['categorize'] )
			$bookmarks = '<ul class="xoxo bookmarks">' . $bookmarks . '</ul>';

		// Output the bookmarks.
		echo $bookmarks;
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
		$instance['title_li'] = sanitize_text_field( $new_instance['title_li'] );

		// Strip tags.
		$instance['search']   = strip_tags( $new_instance['search']   );

		// Arrays of post IDs (integers).
		$instance['category']         = array_map( 'absint', $new_instance['category']         );
		$instance['exclude_category'] = array_map( 'absint', $new_instance['exclude_category'] );
		$instance['include']          = array_map( 'absint', $new_instance['include']          );
		$instance['exclude']          = array_map( 'absint', $new_instance['exclude']          );

		// HTML class.
		$instance['class'] = sanitize_html_class( $new_instance['class'] );

		// Integers.
		$instance['limit'] = intval( $new_instance['limit'] );

		// Whitelist options.
		$category_order = $order = array( 'ASC', 'DESC' );
		$category_orderby        = array( 'count', 'ID', 'name', 'slug' );
		$orderby                 = array( 'id', 'description', 'length', 'name', 'notes', 'owner', 'rand', 'rating', 'rel', 'rss', 'target', 'updated', 'url' );

		$instance['category_order']   = in_array( $new_instance['category_order'],   $category_order )   ? $new_instance['category_order']   : 'ASC';
		$instance['category_orderby'] = in_array( $new_instance['category_orderby'], $category_orderby ) ? $new_instance['category_orderby'] : 'name';
		$instance['order']            = in_array( $new_instance['order'],            $order )            ? $new_instance['order']            : 'ASC';
		$instance['orderby']          = in_array( $new_instance['orderby'],          $orderby )          ? $new_instance['orderby']          : 'name';

		// Text boxes. Make sure user can use 'unfiltered_html'.
		$instance['link_before'] = current_user_can( 'unfiltered_html' ) ? $new_instance['link_before'] : wp_filter_post_kses( $new_instance['link_before'] );
		$instance['link_after']  = current_user_can( 'unfiltered_html' ) ? $new_instance['link_after']  : wp_filter_post_kses( $new_instance['link_after']  );
		$instance['between']     = current_user_can( 'unfiltered_html' ) ? $new_instance['between']     : wp_filter_post_kses( $new_instance['between']     );

		// Checkboxes.
		$instance['categorize']       = isset( $new_instance['categorize'] )       ? 1 : 0;
		$instance['hide_invisible']   = isset( $new_instance['hide_invisible'] )   ? 1 : 0;
		$instance['show_private']     = isset( $new_instance['show_private'] )     ? 1 : 0;
		$instance['show_rating']      = isset( $new_instance['show_rating'] )      ? 1 : 0;
		$instance['show_updated']     = isset( $new_instance['show_updated'] )     ? 1 : 0;
		$instance['show_images']      = isset( $new_instance['show_images'] )      ? 1 : 0;
		$instance['show_name']        = isset( $new_instance['show_name'] )        ? 1 : 0;
		$instance['show_description'] = isset( $new_instance['show_description'] ) ? 1 : 0;

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

		$terms     = get_terms( 'link_category' );
		$bookmarks = get_bookmarks( array( 'hide_invisible' => false ) );

		$category_order = $order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' )
		);

		$category_orderby = array(
			'count' => esc_attr__( 'Count', 'widgets-reloaded' ),
			'ID'    => esc_attr__( 'ID',    'widgets-reloaded' ),
			'name'  => esc_attr__( 'Name',  'widgets-reloaded' ),
			'slug'  => esc_attr__( 'Slug',  'widgets-reloaded' )
		);

		$orderby = array(
			'id'          => esc_attr__( 'ID',          'widgets-reloaded' ),
			'description' => esc_attr__( 'Description', 'widgets-reloaded' ),
			'length'      => esc_attr__( 'Length',      'widgets-reloaded' ),
			'name'        => esc_attr__( 'Name',        'widgets-reloaded' ),
			'notes'       => esc_attr__( 'Notes',       'widgets-reloaded' ),
			'owner'       => esc_attr__( 'Owner',       'widgets-reloaded' ),
			'rand'        => esc_attr__( 'Random',      'widgets-reloaded' ),
			'rating'      => esc_attr__( 'Rating',      'widgets-reloaded' ),
			'rel'         => esc_attr__( 'Rel',         'widgets-reloaded' ),
			'rss'         => esc_attr__( 'RSS',         'widgets-reloaded' ),
			'target'      => esc_attr__( 'Target',      'widgets-reloaded' ),
			'updated'     => esc_attr__( 'Updated',     'widgets-reloaded' ),
			'url'         => esc_attr__( 'URL',         'widgets-reloaded' )
		);
		?>

		<div class="reloaded-section reloaded-col-3">
		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title_li' ); ?>" name="<?php $this->field_name( 'title_li' ); ?>" value="<?php echo esc_attr( $instance['title_li'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title_li'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Category Order:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'category_order' ); ?>">

					<?php foreach ( $category_order as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['category_order'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Category Order By:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'category_orderby' ); ?>">

					<?php foreach ( $category_orderby as $option_value => $option_label ) : ?>

						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $instance['category_orderby'], $option_value ); ?>><?php echo esc_html($option_label ); ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<div class="reloaded-control">

			<?php esc_html_e( 'Category:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>

				<?php foreach ( $terms as $term ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'category' ); ?>[]" value="<?php echo esc_attr( $term->term_id ); ?>" <?php checked( in_array( $term->term_id, (array)$instance['category'] ) ); ?> />
							<?php echo esc_html( $term->name ); ?>
						</label>
					</li>

				<?php endforeach; ?>

				</ul>
			</div>
		</div>

		<div class="reloaded-control">

			<?php esc_html_e( 'Exclude Category:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>

				<?php foreach ( $terms as $term ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'exclude_category' ); ?>[]" value="<?php echo esc_attr( $term->term_id ); ?>" <?php checked( in_array( $term->term_id, (array)$instance['exclude_category'] ) ); ?> />
							<?php echo esc_html( $term->name ); ?>
						</label>
					</li>

				<?php endforeach; ?>

				</ul>
			</div>
		</div>

		<p>
			<label>
				<?php esc_html_e( 'Class:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'class' ); ?>" value="<?php echo esc_attr( $instance['class'] ); ?>" placeholder="linkcat" />
			</label>
		</p>

		</div>

		<div class="reloaded-section reloaded-col-3">

		<p>
			<label>
				<?php esc_html_e( 'Limit:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="-1" name="<?php $this->field_name( 'limit' ); ?>" value="<?php echo esc_attr( $instance['limit'] ); ?>" placeholder="-1" />
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

		<div class="reloaded-control">

			<?php esc_html_e( 'Include:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>

				<?php foreach ( $bookmarks as $bookmark ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'include' ); ?>[]" value="<?php echo esc_attr( $bookmark->link_id ); ?>" <?php checked( in_array( $bookmark->link_id, (array)$instance['include'] ) ); ?> />
							<?php echo esc_html( $bookmark->link_name ); ?>
						</label>
					</li>

				<?php endforeach; ?>

				</ul>
			</div>
		</div>

		<div class="reloaded-control">

			<?php esc_html_e( 'Exclude:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>

				<?php foreach ( $bookmarks as $bookmark ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'exclude' ); ?>[]" value="<?php echo esc_attr( $bookmark->link_id ); ?>" <?php checked( in_array( $bookmark->link_id, (array)$instance['exclude'] ) ); ?> />
							<?php echo esc_html( $bookmark->link_name ); ?>
						</label>
					</li>

				<?php endforeach; ?>

				</ul>
			</div>
		</div>

		<p>
			<label>
				<?php esc_html_e( 'Search:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'search' ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div class="reloaded-section reloaded-col-3">

		<p>
			<label>
				<?php esc_html_e( 'Between:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'between' ); ?>" value="<?php echo esc_attr( $instance['between'] ); ?>" placeholder="&thinsp;&ndash;&thinsp;" />
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
				<input type="checkbox" <?php checked( $instance['categorize'], true ); ?> name="<?php $this->field_name( 'categorize' ); ?>" />
				<?php esc_html_e( 'Categorize?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_description'], true ); ?> name="<?php $this->field_name( 'show_description' ); ?>" />
				<?php esc_html_e( 'Show description?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
			<input type="checkbox" <?php checked( $instance['hide_invisible'], true ); ?> name="<?php $this->field_name( 'hide_invisible' ); ?>" />
				<?php esc_html_e( 'Hide invisible?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_rating'], true ); ?> name="<?php $this->field_name( 'show_rating' ); ?>" />
				<?php esc_html_e( 'Show rating?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_updated'], true ); ?> name="<?php $this->field_name( 'show_updated' ); ?>" />
				<?php esc_html_e( 'Show updated?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_images'], true ); ?> name="<?php $this->field_name( 'show_images' ); ?>" />
				<?php esc_html_e( 'Show images?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_name'], true ); ?> name="<?php $this->field_name( 'show_name' ); ?>" />
				<?php esc_html_e( 'Show name?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['show_private'], true ); ?> name="<?php $this->field_name( 'show_private' ); ?>" />
				<?php esc_html_e( 'Show private?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div style="clear:both;">&nbsp;</div>
	<?php }
}
