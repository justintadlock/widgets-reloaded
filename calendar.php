<?php
/**
 * Calendar Widget
 *
 * Replaces the default WordPress Calendar widget.
 *
 * In 0.2, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the calendar widget.
 * The settings are based on get_calendar()
 * @link http://codex.wordpress.org/Template_Tags/get_calendar
 *
 * @since 0.2
 */
class Widgets_Reloaded_Widget_Calendar extends WP_Widget {

	function Widgets_Reloaded_Widget_Calendar() {
		$widget_ops = array( 'classname' => 'calendar', 'description' => __('An advanced widget that gives you total control over the output of your calendar.', 'widgets-reloaded') );
		$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'widgets-reloaded-calendar' );
		$this->WP_Widget( 'widgets-reloaded-calendar', __('Calendar', 'widgets-reloaded'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$hide_empty = isset( $instance['initial'] ) ? $instance['initial'] : false;

		echo $before_widget;

		if ( $title )
			echo "\n\t\t\t" . $before_title . $title . $after_title;

			echo "\n\t\t\t" . '<div class="calendar-wrap">';
				get_calendar( $initial );
			echo "\n\t\t\t" . '</div><!-- .calendar-wrap -->';

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['initial'] = $new_instance['initial'];

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Calendar', 'widgets-reloaded'), 'initial' => false );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'widgets-reloaded'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'initial' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['initial'], true ); ?> id="<?php echo $this->get_field_id( 'initial' ); ?>" name="<?php echo $this->get_field_name( 'initial' ); ?>" /> <?php _e('One-letter abbreviation?', 'widgets-reloaded'); ?> <code>initial</code></label>
		</p>
	<?php
	}
}

?>