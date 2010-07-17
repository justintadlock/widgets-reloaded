<?php
/**
 * Tags Widget
 *
 * Replaces the default WordPress Tag Cloud widget.
 *
 * In 0.2, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the tags widget.
 * Each setting is an argument for wp_tag_cloud()
 * @link http://codex.wordpress.org/Template_Tags/wp_tag_cloud
 *
 * @since 0.2
 */
class Widgets_Reloaded_Widget_Tags extends WP_Widget {

	function Widgets_Reloaded_Widget_Tags() {
		$widget_ops = array( 'classname' => 'tags', 'description' => __('An advanced widget that gives you total control over the output of your tags.','widgets-reloaded') );
		$control_ops = array( 'width' => 700, 'height' => 350, 'id_base' => 'widgets-reloaded-tags' );
		$this->WP_Widget( 'widgets-reloaded-tags', __('Tags', 'widgets-reloaded'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$taxonomy = $instance['taxonomy'];
		$format = $instance['format'];
		$order = $instance['order'];
		$orderby = $instance['orderby'];
		$unit = $instance['unit'];
		$largest = (int)$instance['largest'];
		$smallest = (int)$instance['smallest'];
		$number = (int)$instance['number'];
		$exclude = $instance['exclude'];
		$include = $instance['include'];
		$link = $instance['link'];

		if ( !$largest )
			$largest = 22;
		if ( !$smallest )
			$smallest = 8;

		$tags = array(
			'taxonomy' => $taxonomy,
			'smallest' => $smallest,
			'largest' => $largest,
			'unit' => $unit,
			'number' => $number,
			'format' => $format,
			'orderby' => $orderby,
			'order' => $order,
			'exclude' => $exclude,
			'include' => $include,
			'link' => $link,
			'echo' => 0,
		);

		echo "\n\t\t\t" . $before_widget;

		if ( $title )
			echo "\n\t\t\t\t" . $before_title . $title . $after_title;

		if ( $format == 'flat' ) :
			echo "\n\t\t\t\t" . '<p class="tag-cloud">';
			echo "\n\t\t\t\t\t" . str_replace( array( "\r", "\n", "\t" ), ' ', wp_tag_cloud( $tags ) );
			echo "\n\t\t\t\t" . '</p><!-- .tag-cloud -->';
		else :
			echo "\n\t\t\t\t" . str_replace( array( "\r", "\n", "\t" ), '', wp_tag_cloud( $tags ) );
		endif;

		echo "\n\t\t\t" . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['smallest'] = strip_tags( $new_instance['smallest'] );
		$instance['largest'] = strip_tags( $new_instance['largest'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['exclude'] = strip_tags( $new_instance['exclude'] );
		$instance['include'] = strip_tags( $new_instance['include'] );
		$instance['unit'] = $new_instance['unit'];
		$instance['format'] = $new_instance['format'];
		$instance['orderby'] = $new_instance['orderby'];
		$instance['order'] = $new_instance['order'];
		$instance['taxonomy'] = $new_instance['taxonomy'];
		$instance['link'] = $new_instance['link'];

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Tags', 'widgets-reloaded'), 'format' => 'flat', 'unit' => 'pt', 'smallest' => 8, 'largest' => 22, 'link' => 'view', 'number' => 45, 'taxonomy' => 'post_tag'  );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'taxonomy' ); ?>"><?php _e('Taxonomy:', 'widgets-reloaded'); ?> <code>taxonomy</code></label> 
			<select id="<?php echo $this->get_field_id( 'taxonomy' ); ?>" name="<?php echo $this->get_field_name( 'taxonomy' ); ?>" class="widefat" style="width:100%;">
			<?php global $wp_taxonomies; ?>
			<?php if ( is_array( $wp_taxonomies ) ) : ?>
				<?php foreach( $wp_taxonomies as $tax ) : ?>
					<option <?php if ( $tax->name == $instance['taxonomy'] ) echo 'selected="selected"'; ?>><?php echo $tax->name; ?></option>
				<?php endforeach; ?>
			<?php endif; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e('Format:', 'widgets-reloaded'); ?> <code>format</code></label> 
			<select id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'flat' == $instance['format'] ) echo 'selected="selected"'; ?>>flat</option>
				<option <?php if ( 'list' == $instance['format'] ) echo 'selected="selected"'; ?>>list</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e('Order:', 'widgets-reloaded'); ?> <code>order</code></label> 
			<select id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'ASC' == $instance['order'] ) echo 'selected="selected"'; ?>>ASC</option>
				<option <?php if ( 'DESC' == $instance['order'] ) echo 'selected="selected"'; ?>>DESC</option>
				<option <?php if ( 'RAND' == $instance['order'] ) echo 'selected="selected"'; ?>>RAND</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php _e('Order By:', 'widgets-reloaded'); ?> <code>orderby</code></label> 
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'name' == $instance['orderby'] ) echo 'selected="selected"'; ?>>name</option>
				<option <?php if ( 'count' == $instance['orderby'] ) echo 'selected="selected"'; ?>>count</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e('Number:', 'widgets-reloaded'); ?> <code>number</code></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" style="width:100%;" />
		</p>
		</div>

		<div style="float:right;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'largest' ); ?>"><?php _e('Largest:', 'widgets-reloaded'); ?> <code>largest</code></label>
			<input id="<?php echo $this->get_field_id( 'largest' ); ?>" name="<?php echo $this->get_field_name( 'largest' ); ?>" value="<?php echo $instance['largest']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'smallest' ); ?>"><?php _e('Smallest:', 'widgets-reloaded'); ?> <code>smallest</code></label>
			<input id="<?php echo $this->get_field_id( 'smallest' ); ?>" name="<?php echo $this->get_field_name( 'smallest' ); ?>" value="<?php echo $instance['smallest']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'unit' ); ?>"><?php _e('Unit:', 'widgets-reloaded'); ?> <code>unit</code></label> 
			<select id="<?php echo $this->get_field_id( 'unit' ); ?>" name="<?php echo $this->get_field_name( 'unit' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'pt' == $instance['unit'] ) echo 'selected="selected"'; ?>>pt</option>
				<option <?php if ( 'px' == $instance['unit'] ) echo 'selected="selected"'; ?>>px</option>
				<option <?php if ( 'em' == $instance['unit'] ) echo 'selected="selected"'; ?>>em</option>
				<option <?php if ( '%' == $instance['unit'] ) echo 'selected="selected"'; ?>>%</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'exclude' ); ?>"><?php _e('Exclude:', 'widgets-reloaded'); ?> <code>exclude</code></label>
			<input id="<?php echo $this->get_field_id( 'exclude' ); ?>" name="<?php echo $this->get_field_name( 'exclude' ); ?>" value="<?php echo $instance['exclude']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'include' ); ?>"><?php _e('Include:', 'widgets-reloaded'); ?> <code>include</code></label>
			<input id="<?php echo $this->get_field_id( 'include' ); ?>" name="<?php echo $this->get_field_name( 'include' ); ?>" value="<?php echo $instance['include']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Link:', 'widgets-reloaded'); ?> <code>link</code></label> 
			<select id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'view' == $instance['link'] ) echo 'selected="selected"'; ?>>view</option>
				<option <?php if ( 'edit' == $instance['link'] ) echo 'selected="selected"'; ?>>edit</option>
			</select>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>