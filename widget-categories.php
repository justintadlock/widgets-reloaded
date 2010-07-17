<?php
/**
 * Categories Widget Class
 *
 * The Categories widget replaces the default WordPress Categories widget. This version gives total
 * control over the output to the user by allowing the input of all the arguments typically seen
 * in the wp_list_categories() function.
 *
 * @since 0.6
 * @link http://codex.wordpress.org/Template_Tags/wp_list_categories
 * @link http://themehybrid.com/themes/hybrid/widgets
 *
 * @package Hybrid
 * @subpackage Classes
 */

class Hybrid_Widget_Categories extends WP_Widget {

	var $prefix;
	var $textdomain;

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 * @since 0.6
	 */
	function Hybrid_Widget_Categories() {
		$this->prefix = hybrid_get_prefix();
		$this->textdomain = hybrid_get_textdomain();

		$widget_ops = array( 'classname' => 'categories', 'description' => __( 'An advanced widget that gives you total control over the output of your category links.', $this->textdomain ) );
		$control_ops = array( 'width' => 800, 'height' => 350, 'id_base' => "{$this->prefix}-categories" );
		$this->WP_Widget( "{$this->prefix}-categories", __( 'Categories', $this->textdomain ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 * @since 0.6
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$style = $instance['style'];
		$orderby = $instance['orderby'];
		$order = $instance['order'];
		$exclude = $instance['exclude'];
		$exclude_tree = $instance['exclude_tree'];
		$include = $instance['include'];
		$depth = (int)$instance['depth'];
		$number = (int)$instance['number'];
		$child_of = (int)$instance['child_of'];
		$current_category = (int)$instance['current_category'];
		$feed_image = $instance['feed_image'];
		$search = $instance['search'];

		$hierarchical = isset( $instance['hierarchical'] ) ? $instance['hierarchical'] : false;
		$use_desc_for_title = isset( $instance['use_desc_for_title'] ) ? $instance['use_desc_for_title'] : false;
		$show_last_update = isset( $instance['show_last_update'] ) ? $instance['show_last_updated'] : false;
		$show_count = isset( $instance['show_count'] ) ? $instance['show_count'] : false;
		$hide_empty = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : false;
		$feed = isset( $instance['feed'] ) ? $instance['feed'] : false;

		if ( $feed )
			$feed = __( 'RSS', $this->textdomain );

		$args = array(
			'exclude' => $exclude,
			'exclude_tree' => $exclude_tree,
			'include' => $include,
			'number' => $number,
			'depth' => $depth,
			'orderby' => $orderby,
			'order' => $order,
			'show_last_update' => $show_last_update,
			'style' => $style,
			'show_count' => $show_count,
			'hide_empty' => $hide_empty,
			'use_desc_for_title' => $use_desc_for_title,
			'child_of' => $child_of,
			'feed' => $feed,
			'feed_image' => $feed_image,
			'hierarchical' => $hierarchical,
			'title_li' => false,
			'current_category' => $current_category,
			'echo' => 0,
			'depth' => $depth,
			'search' => $search,
		);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		$categories = str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $args ) );

		if ( 'list' == $style )
			$categories = '<ul class="xoxo categories">' . $categories . '</ul><!-- .xoxo .categories -->';

		echo $categories;

		echo $after_widget;
	}

	/**
	 * Updates the widget control options for the particular instance of the widget.
	 * @since 0.6
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['exclude'] = strip_tags( $new_instance['exclude'] );
		$instance['exclude_tree'] = strip_tags( $new_instance['exclude_tree'] );
		$instance['include'] = strip_tags( $new_instance['include'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['child_of'] = strip_tags( $new_instance['child_of'] );
		$instance['current_category'] = strip_tags( $new_instance['current_category'] );
		$instance['feed_image'] = strip_tags( $new_instance['feed_image'] );
		$instance['search'] = strip_tags( $new_instance['search'] );
		$instance['style'] = $new_instance['style'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];

		$instance['hierarchical'] = ( isset( $new_instance['hierarchical'] ) ? 1 : 0 );
		$instance['use_desc_for_title'] = ( isset( $new_instance['use_desc_for_title'] ) ? 1 : 0 );
		$instance['show_last_update'] = ( isset( $new_instance['show_last_update'] ) ? 1 : 0 );
		$instance['show_count'] = ( isset( $new_instance['show_count'] ) ? 1 : 0 );
		$instance['hide_empty'] = ( isset( $new_instance['hide_empty'] ) ? 1 : 0 );
		$instance['feed'] = ( isset( $new_instance['feed'] ) ? 1 : 0 );

		return $instance;
	}

	/**
	 * Displays the widget control options in the Widgets admin screen.
	 * @since 0.6
	 */
	function form( $instance ) {

		// Defaults
		$defaults = array( 'title' => __( 'Categories', $this->textdomain ), 'style' => 'list', 'hierarchical' => true, 'hide_empty' => true, 'order' => 'ASC', 'orderby' => 'name' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:31%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', $this->textdomain ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Style:', $this->textdomain ); ?> <code>style</code></label> 
			<select id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'list' == $instance['style'] ) echo 'selected="selected"'; ?>>list</option>
				<option <?php if ( 'none' == $instance['style'] ) echo 'selected="selected"'; ?>>none</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Order:', $this->textdomain ); ?> <code>order</code></label> 
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'ASC' == $instance['order'] ) echo 'selected="selected"'; ?>>ASC</option>
				<option <?php if ( 'DESC' == $instance['order'] ) echo 'selected="selected"'; ?>>DESC</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e( 'Order By:', $this->textdomain ); ?> <code>orderby</code></label> 
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'name' == $instance['orderby'] ) echo 'selected="selected"'; ?>>name</option>
				<option <?php if ( 'slug' == $instance['orderby'] ) echo 'selected="selected"'; ?>>slug</option>
				<option <?php if ( 'ID' == $instance['orderby'] ) echo 'selected="selected"'; ?>>ID</option>
				<option <?php if ( 'count' == $instance['orderby'] ) echo 'selected="selected"'; ?>>count</option>
				<option <?php if ( 'term_group' == $instance['orderby'] ) echo 'selected="selected"'; ?>>term_group</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'depth' ); ?>"><?php _e( 'Depth:', $this->textdomain ); ?> <code>depth</code></label>
			<input id="<?php echo $this->get_field_id( 'depth' ); ?>" name="<?php echo $this->get_field_name( 'depth' ); ?>" type="text" value="<?php echo $instance['depth']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number:', $this->textdomain ); ?> <code>number</code></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $instance['number']; ?>" style="width:100%;" />
		</p>
		</div>

		<div style="float:left;width:31%;margin-left:3.5%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e( 'Include:', $this->textdomain ); ?> <code>include</code></label>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" type="text" value="<?php echo $instance['include']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e( 'Exclude:', $this->textdomain ); ?> <code>exclude</code></label>
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" type="text" value="<?php echo $instance['exclude']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude_tree' ); ?>"><?php _e( 'Exclude Tree:', $this->textdomain ); ?> <code>exclude_tree</code></label>
			<input id="<?php echo $this->get_field_id( 'exclude_tree' ); ?>" name="<?php echo $this->get_field_name( 'exclude_tree' ); ?>" type="text" value="<?php echo $instance['exclude_tree']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'child_of' ); ?>"><?php _e( 'Child Of:', $this->textdomain ); ?> <code>child_of</code></label>
			<input id="<?php echo $this->get_field_id( 'child_of' ); ?>" name="<?php echo $this->get_field_name( 'child_of' ); ?>" type="text" value="<?php echo $instance['child_of']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search' ); ?>"><?php _e( 'Search:', $this->textdomain ); ?> <code>search</code></label>
			<input id="<?php echo $this->get_field_id( 'search' ); ?>" name="<?php echo $this->get_field_name( 'search' ); ?>" type="text" value="<?php echo $instance['search']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'current_category' ); ?>"><?php _e( 'Current Category:', $this->textdomain ); ?> <code>current_category</code></label>
			<input id="<?php echo $this->get_field_id( 'current_category' ); ?>" name="<?php echo $this->get_field_name( 'current_category' ); ?>" type="text" value="<?php echo $instance['current_category']; ?>" style="width:100%;" />
		</p>
		</div>

		<div style="float:right;width:31%;margin-left:3.5%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'feed_image' ); ?>"><?php _e( 'Feed Image:', $this->textdomain ); ?> <code>feed_image</code></label>
			<input id="<?php echo $this->get_field_id( 'feed_image' ); ?>" name="<?php echo $this->get_field_name( 'feed_image' ); ?>" type="text" value="<?php echo $instance['feed_image']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hierarchical' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['hierarchical'], true ); ?> id="<?php echo $this->get_field_id( 'hierarchical' ); ?>" name="<?php echo $this->get_field_name( 'hierarchical' ); ?>" /> <?php _e( 'Hierarchical?', $this->textdomain ); ?> <code>hierarchical</code></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['use_desc_for_title'], true ); ?> id="<?php echo $this->get_field_id( 'use_desc_for_title' ); ?>" name="<?php echo $this->get_field_name( 'use_desc_for_title' ); ?>" /> <?php _e( 'Use description?', $this->textdomain ); ?> <code>use_desc_for_title</code></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_last_update' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_last_update'], true ); ?> id="<?php echo $this->get_field_id( 'show_last_update' ); ?>" name="<?php echo $this->get_field_name( 'show_last_update' ); ?>" /> <?php _e( 'Show last update?', $this->textdomain ); ?> <code>show_last_update</code></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_count' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_count'], true ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" /> <?php _e( 'Show count?', $this->textdomain ); ?> <code>show_count</code></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'hide_empty' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['hide_empty'], true ); ?> id="<?php echo $this->get_field_id( 'hide_empty' ); ?>" name="<?php echo $this->get_field_name( 'hide_empty' ); ?>" /> <?php _e( 'Hide empty?', $this->textdomain ); ?> <code>hide_empty</code></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'feed' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['feed'], true ); ?> id="<?php echo $this->get_field_id( 'feed' ); ?>" name="<?php echo $this->get_field_name( 'feed' ); ?>" /> <?php _e( 'Show RSS feed?', $this->textdomain ); ?> <code>feed</code></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>