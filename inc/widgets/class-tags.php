<?php
/**
 * The Tags widget replaces the default WordPress Tag Cloud widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_tag_cloud() function.
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
 * Tags Widget Class
 *
 * @since  1.0.0
 * @access public
 */
class Tags extends Widget {

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
			'classname'                   => 'widget-tags widget_tag_cloud',
			'description'                 => esc_html__( 'Displays a cloud or list of tags.', 'widgets-reloaded' ),
			'customize_selective_refresh' => true
		);

		// Set up the widget control options.
		$control_options = array(
			'width'  => 750,
			'height' => 350
		);

		// Create the widget.
		parent::__construct( 'hybrid-tags', __( 'Reloaded - Tags', 'widgets-reloaded' ), $widget_options, $control_options );

		// Set up the defaults.
		$topic_count_text = _n_noop( '%s topic', '%s topics', 'widgets-reloaded' );

		$this->defaults = array(
			'title'                      => esc_attr__( 'Tags', 'widgets-reloaded' ),
			'order'                      => 'ASC',
			'orderby'                    => 'name',
			'format'                     => 'flat',
			'include'                    => '',
			'exclude'                    => '',
			'unit'                       => 'pt',
			'smallest'                   => 8,
			'largest'                    => 22,
			'link'                       => 'view',
			'number'                     => 25,
			'separator'                  => ' ',
			'child_of'                   => '',
			'parent'                     => '',
			'taxonomy'                   => array( 'post_tag' ),
			'hide_empty'                 => 1,
			'pad_counts'                 => false,
			'search'                     => '',
			'name__like'                 => '',
			'single_text'                => $topic_count_text['singular'],
			'multiple_text'              => $topic_count_text['plural'],
			'topic_count_text_callback'  => '',
			'topic_count_scale_callback' => 'default_topic_count_scale',
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

		// Set the $args for wp_tag_cloud() to the $instance array.
		$args = wp_parse_args( $instance, $this->defaults );

		// Make sure empty callbacks aren't passed for custom functions.
		$args['topic_count_text_callback']  = !empty( $args['topic_count_text_callback']  ) ? $args['topic_count_text_callback']  : '';
		$args['topic_count_scale_callback'] = !empty( $args['topic_count_scale_callback'] ) ? $args['topic_count_scale_callback'] : 'default_topic_count_scale';

		// If the separator is empty, set it to the default new line.
		$args['separator'] = !empty( $args['separator'] ) ? $args['separator'] : "\n";

		// Overwrite the echo argument.
		$args['echo'] = false;

		// Output the sidebar's $before_widget wrapper.
		echo $sidebar['before_widget'];

		// If a title was input by the user, display it.
		$this->widget_title( $sidebar, $instance );

		// Get the tag cloud.
		$tags = str_replace( array( "\r", "\n", "\t" ), ' ', wp_tag_cloud( $args ) );

		// If $format should be flat, wrap it in the <p> element.
		if ( 'flat' == $args['format'] ) {
			$classes = array( 'term-cloud' );

			foreach ( (array)$args['taxonomy'] as $tax )
				$classes[] = sanitize_html_class( "{$tax}-cloud" );

			$tags = '<p class="' . join( $classes, ' ' ) . '">' . $tags . '</p>';
		}

		// Output the tag cloud.
		echo $tags;

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
		$instance['separator']     = strip_tags( $new_instance['separator']     );
		$instance['name__like']    = strip_tags( $new_instance['name__like']    );
		$instance['search']        = strip_tags( $new_instance['search']        );
		$instance['single_text']   = strip_tags( $new_instance['single_text']   );
		$instance['multiple_text'] = strip_tags( $new_instance['multiple_text'] );

		// Sanitize key.
		$instance['taxonomy'] = array_map( 'sanitize_key', $new_instance['taxonomy'] );

		// Whitelist options.
		$order   = array( 'ASC', 'DESC', 'RAND' );
		$orderby = array( 'count', 'name' );
		$format  = array( 'flat', 'list' );
		$unit    = array( 'pt', 'px', 'em', '%' );
		$link    = array( 'view', 'edit' );

		$instance['order']   = in_array( $new_instance['order'],   $order )   ? $new_instance['order']   : 'ASC';
		$instance['orderby'] = in_array( $new_instance['orderby'], $orderby ) ? $new_instance['orderby'] : 'name';
		$instance['format']  = in_array( $new_instance['format'],  $format )  ? $new_instance['format']  : 'view';
		$instance['unit']    = in_array( $new_instance['unit'],    $unit )    ? $new_instance['unit']    : 'pt';
		$instance['link']    = in_array( $new_instance['link'],    $link )    ? $new_instance['link']    : 'view';

		// Integers.
		$instance['number']   = intval( $new_instance['number']   );
		$instance['smallest'] = absint( $new_instance['smallest'] );
		$instance['largest']  = absint( $new_instance['largest']  );
		$instance['child_of'] = absint( $new_instance['child_of'] );
		$instance['parent']   = absint( $new_instance['parent']   );

		// Only allow integers and commas.
		$instance['include'] = preg_replace( '/[^0-9,]/', '', $new_instance['include'] );
		$instance['exclude'] = preg_replace( '/[^0-9,]/', '', $new_instance['exclude'] );

		// Check if function exists.
		$instance['topic_count_text_callback']  = empty( $new_instance['fallback_cb'] ) || function_exists( $new_instance['topic_count_text_callback'] )  ? $new_instance['topic_count_text_callback']  : 'default_topic_count_text';
		$instance['topic_count_scale_callback'] = empty( $new_instance['fallback_cb'] ) || function_exists( $new_instance['topic_count_scale_callback'] ) ? $new_instance['topic_count_scale_callback'] : 'default_topic_count_scale';

		// Checkboxes.
		$instance['pad_counts'] = isset( $new_instance['pad_counts'] ) ? 1 : 0;
		$instance['hide_empty'] = isset( $new_instance['hide_empty'] ) ? 1 : 0;

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

		$link = array(
			'view' => esc_attr__( 'View', 'widgets-reloaded' ),
			'edit' => esc_attr__( 'Edit', 'widgets-reloaded' )
		);

		$format = array(
			'flat' => esc_attr__( 'Flat', 'widgets-reloaded' ),
			'list' => esc_attr__( 'List', 'widgets-reloaded' )
		);

		$order = array(
			'ASC'  => esc_attr__( 'Ascending',  'widgets-reloaded' ),
			'DESC' => esc_attr__( 'Descending', 'widgets-reloaded' ),
			'RAND' => esc_attr__( 'Random',     'widgets-reloaded' )
		);

		$orderby = array(
			'count' => esc_attr__( 'Count', 'widgets-reloaded' ),
			'name'  => esc_attr__( 'Name',  'widgets-reloaded' )
		);

		$unit = array(
			'pt' => 'pt',
			'px' => 'px',
			'em' => 'em',
			'%'  => '%'
		); ?>

		<div class="reloaded-section reloaded-col-3">

		<p>
			<label>
				<?php esc_html_e( 'Title:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" id="<?php $this->field_id( 'title' ); ?>" name="<?php $this->field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['title'] ); ?>" />
			</label>
		</p>

		<div class="reloaded-control">
			<?php esc_html_e( 'Taxonomy:', 'widgets-reloaded' ); ?>

			<div class="wp-tab-panel">
				<ul>

				<?php foreach ( $taxonomies as $taxonomy ) : ?>

					<li>
						<label>
							<input type="checkbox" name="<?php $this->field_name( 'taxonomy' ); ?>[]" value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php checked( in_array( $taxonomy->name, (array)$instance['taxonomy'] ) ); ?> />
							<?php echo esc_html( $taxonomy->labels->singular_name ); ?>
						</label>
					</li>
				<?php endforeach; ?>

				</ul>
			</div>
		</div>

		<p>
			<label>
				<?php esc_html_e( 'Format:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'format' ); ?>">

					<?php foreach ( $format as $option_value => $option_label ) : ?>

						<option value="<?php echo $option_value; ?>" <?php selected( $instance['format'], $option_value ); ?>><?php echo $option_label; ?></option>

					<?php endforeach; ?>

				</select>
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
				<?php esc_html_e( 'Link:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'link' ); ?>">

					<?php foreach ( $link as $option_value => $option_label ) : ?>

						<option value="<?php echo $option_value; ?>" <?php selected( $instance['link'], $option_value ); ?>><?php echo $option_label; ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Number:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="0" name="<?php $this->field_name( 'number' ); ?>" value="<?php echo esc_attr( $instance['number'] ); ?>" placeholder="25" />
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div class="reloaded-section reloaded-col-3">

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

		<p>
			<label>
				<?php esc_html_e( 'Largest:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="1" name="<?php $this->field_name( 'largest' ); ?>" value="<?php echo esc_attr( $instance['largest'] ); ?>" placeholder="22" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Smallest:', 'widgets-reloaded' ); ?>
				<input type="number" class="widefat code" size="5" min="1" name="<?php $this->field_name( 'smallest' ); ?>" value="<?php echo esc_attr( $instance['smallest'] ); ?>" placeholder="8" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Unit:', 'widgets-reloaded' ); ?>

				<select class="widefat" name="<?php $this->field_name( 'unit' ); ?>">

					<?php foreach ( $unit as $option_value => $option_label ) : ?>

						<option value="<?php echo $option_value; ?>" <?php selected( $instance['unit'], $option_value ); ?>><?php echo $option_label; ?></option>

					<?php endforeach; ?>

				</select>
			</label>
		</p>
		<p>
			<label>
				<?php esc_html_e( 'Separator:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'separator' ); ?>" value="<?php echo esc_attr( $instance['separator'] ); ?>" placeholder="&thinsp;&ndash;&thinsp;" />
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
				<?php esc_html_e( 'Parent:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'parent' ); ?>" value="<?php echo esc_attr( $instance['parent'] ); ?>" placeholder="0" />
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div class="reloaded-section reloaded-col-3">

		<p>
			<label>
				<?php esc_html_e( 'Search:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'search' ); ?>" value="<?php echo esc_attr( $instance['search'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Name Like:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat code" name="<?php $this->field_name( 'name__like' ); ?>" value="<?php echo esc_attr( $instance['name__like'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Single Text:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" name="<?php $this->field_name( 'single_text' ); ?>" value="<?php echo esc_attr( $instance['single_text'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['single_text'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Multiple Text:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" name="<?php $this->field_name( 'multiple_text' ); ?>" value="<?php echo esc_attr( $instance['multiple_text'] ); ?>" placeholder="<?php echo esc_attr( $this->defaults['multiple_text'] ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Count Text Callback:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" name="<?php $this->field_name( 'topic_count_text_callback' ); ?>" value="<?php echo esc_attr( $instance['topic_count_text_callback'] ); ?>" placeholder="default_topic_count_text" />
			</label>
		</p>
		<p>
			<label>
				<?php esc_html_e( 'Count Scale Callback:', 'widgets-reloaded' ); ?>
				<input type="text" class="widefat" name="<?php $this->field_name( 'topic_count_scale_callback' ); ?>" value="<?php echo esc_attr( $instance['topic_count_scale_callback'] ); ?>" placeholder="default_topic_count_scale" />
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['pad_counts'], true ); ?> name="<?php $this->field_name( 'pad_counts' ); ?>" />
				<?php esc_html_e( 'Pad counts?', 'widgets-reloaded' ); ?>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> name="<?php $this->field_name( 'hide_empty' ); ?>" />
				<?php esc_html_e( 'Hide empty?', 'widgets-reloaded' ); ?>
			</label>
		</p>

		</div><!-- .reloaded-section -->

		<div style="clear:both;">&nbsp;</div>
	<?php }
}
