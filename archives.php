<?php
/**
 * Archives Widget
 *
 * Replaces the default WordPress Archives widget.
 *
 * In 0.2, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the Archives widget.
 * Arguments are based on the wp_get_archives() function.
 * @link http://codex.wordpress.org/Template_Tags/wp_get_archives
 *
 * @since 0.2
 */
class Widgets_Reloaded_Widget_Archives extends WP_Widget {

	function Widgets_Reloaded_Widget_Archives() {
		$widget_ops = array( 'classname' => 'archives', 'description' => __('An advanced widget that gives you total control over the output of your archives.', 'widgets-reloaded') );
		$control_ops = array( 'width' => 700, 'height' => 350, 'id_base' => 'widgets-reloaded-archives' );
		$this->WP_Widget( 'widgets-reloaded-archives', __('Archives'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$type = $instance['type']; 
		$format = $instance['format'];
		$before = $instance['before'];
		$after = $instance['after'];
		$limit = (int)$instance['limit'];

		$show_post_count = isset( $instance['show_post_count'] ) ? $instance['show_post_count'] : false;

		if ( !$limit )
			$limit = '';

		$archives = array(
			'type' => $type,
			'limit' => $limit,
			'format' => $format,
			'before' => $before,
			'after' => $after,
			'show_post_count' => $show_post_count,
			'echo' => 0,
		);

		echo $before_widget;

		if ( $title )
			echo "\n\t\t\t\t" . $before_title . $title . $after_title;

		if ( $format == 'option' ) :

			if ( $type == 'yearly' ) :
				$option_title = __('Select Year','widgets-reloaded');
			elseif ( $type == 'monthly' ) :
				$option_title = __('Select Month','widgets-reloaded');
			elseif ( $type == 'weekly' ) :
				$option_title = __('Select Week','widgets-reloaded');
			elseif ( $type == 'daily' ) :
				$option_title = __('Select Day','widgets-reloaded');
			elseif ( $type == 'postbypost' ) :
				$option_title = __('Select Post','widgets-reloaded');
			endif;

			echo "\n\t\t\t\t" . '<select name="archive-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>';

			echo '<option value="">' . esc_attr( $option_title ) . '</option>';

			echo "\n\t\t\t\t\t" . str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $archives ) );

			echo "\n\t\t\t\t" . '</select>';

		elseif ( $format == 'html' ) :

			echo "\n\t\t\t\t" . '<ul class="xoxo archives">';
			echo "\n\t\t\t\t\t" . str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $archives ) );
			echo "\n\t\t\t\t" . '</ul><!-- .xoxo .archives -->';

		else :

			echo "\n\t\t\t\t" . str_replace( array( "\r", "\n", "\t" ), '', wp_get_archives( $archives ) );

		endif;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['before'] = strip_tags( $new_instance['before'] );
		$instance['after'] = strip_tags( $new_instance['after'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['type'] = $new_instance['type'];
		$instance['format'] = $new_instance['format'];

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Archives', 'widgets-reloaded'), 'limit' => '', 'type' => 'monthly', 'format' => 'html', 'before' => '', 'after' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e('Limit:', 'widgets-reloaded'); ?> <code>limit</code></label>
			<input id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e('Type:','widgets-reloaded'); ?> <code>type</code></label> 
			<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'yearly' == $instance['type'] ) echo 'selected="selected"'; ?>>yearly</option>
				<option <?php if ( 'monthly' == $instance['type'] ) echo 'selected="selected"'; ?>>monthly</option>
				<option <?php if ( 'weekly' == $instance['type'] ) echo 'selected="selected"'; ?>>weekly</option>
				<option <?php if ( 'daily' == $instance['type'] ) echo 'selected="selected"'; ?>>daily</option>
				<option <?php if ( 'postbypost' == $instance['type'] ) echo 'selected="selected"'; ?>>postbypost</option>
				<option <?php if ( 'alpha' == $instance['type'] ) echo 'selected="selected"'; ?>>alpha</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'format' ); ?>"><?php _e('Format:','widgets-reloaded'); ?> <code>format</code></label> 
			<select id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>" class="widefat" style="width:100%;">
				<option <?php if ( 'html' == $instance['format'] ) echo 'selected="selected"'; ?>>html</option>
				<option <?php if ( 'option' == $instance['format'] ) echo 'selected="selected"'; ?>>option</option>
				<option <?php if ( 'custom' == $instance['format'] ) echo 'selected="selected"'; ?>>custom</option>
			</select>
		</p>
		</div>

		<div style="float:right;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'before' ); ?>"><?php _e('Before:', 'widgets-reloaded'); ?> <code>before</code></label>
			<input id="<?php echo $this->get_field_id( 'before' ); ?>" name="<?php echo $this->get_field_name( 'before' ); ?>" value="<?php echo $instance['before']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'after' ); ?>"><?php _e('After:', 'widgets-reloaded'); ?> <code>after</code></label>
			<input id="<?php echo $this->get_field_id( 'after' ); ?>" name="<?php echo $this->get_field_name( 'after' ); ?>" value="<?php echo $instance['after']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_post_count' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_post_count'], true ); ?> id="<?php echo $this->get_field_id( 'show_post_count' ); ?>" name="<?php echo $this->get_field_name( 'show_post_count' ); ?>" /> <?php _e('Show post count?', 'widgets-reloaded'); ?> <code>show_post_count</code></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>