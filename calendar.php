<?php
/**
 * Calendar Widget
 * Replaces the default WordPress Calendar widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the calendar widget
 * The settings are based on get_calendar()
 * @link http://codex.wordpress.org/Template_Tags/get_calendar
 *
 * @since 0.1.2
 */
function widget_reloaded_calendar( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_calendar' );

	if ( !isset( $options[$number] ) )
		return;

	$title = apply_filters( 'widget_title', $options[$number]['title'] );
	$initial = $options[$number]['initial'] ? '1' : '0';

	echo $before_widget;

	if ( $title )
		echo $before_title . $title . $after_title;

		echo '<div class="calendar-wrap">';
			get_calendar( $initial );
		echo '</div>';

	echo $after_widget;
}

/**
 * Widget controls for the calendar widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1.2
 */
function widget_reloaded_calendar_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_calendar' );

	if ( !is_array( $options ) )
		$options = array();

	if ( !$updated && !empty( $_POST['sidebar'] ) ) :

		$sidebar = (string)$_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();

		if ( isset( $sidebars_widgets[$sidebar] ) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( $this_sidebar as $_widget_id ) :

			if ( 'widget_reloaded_calendar' == $wp_registered_widgets[$_widget_id]['callback'] && isset( $wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-calendar'] as $widget_number => $widget_reloaded_calendar ) :

			$title = strip_tags( stripslashes( $widget_reloaded_calendar['title'] ) );

			$initial = strip_tags( stripslashes( $widget_reloaded_calendar['initial'] ) );

			$options[$widget_number] = compact( 'title', 'initial' );

		endforeach;

		update_option( 'widget_reloaded_calendar', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$title = '';
		$initial = '';
		$number = '%i%';
	else :
		$title = attribute_escape( $options[$number]['title'] );
		$initial = attribute_escape( $options[$number]['initial'] );
	endif;

?>

	<p>
		<label for="reloaded-calendar-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-calendar-title-<?php echo $number; ?>" name="widget-reloaded-calendar[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-calendar-initial-<?php echo $number; ?>" name="widget-reloaded-calendar[<?php echo $number; ?>][initial]" <?php if($initial == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-calendar-initial-<?php echo $number; ?>">
			<?php _e('One-letter abbreviation?','reloaded'); ?> <code>initial</code>
		</label>
	</p>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-calendar-submit-<?php echo $number; ?>" name="reloaded-calendar-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
 * Register the calendar widget
 * Register the calendar widget controls
 *
 * @since 0.1.2
 */
function widget_reloaded_calendar_register() {

	if ( !$options = get_option( 'widget_reloaded_calendar' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'calendar',
		'description' => __('An advanced widget that gives you total control over the output of your calendar.','reloaded'),
	);

	$control_ops = array(
		'width' => 200,
		'height' => 350,
		'id_base' => 'reloaded-calendar',
	);

	$name = __('Calendar','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset( $options[$o]['title'] ) )
			continue;

		$id = 'reloaded-calendar-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_calendar', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_calendar_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-calendar-1', $name, 'widget_reloaded_calendar', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-calendar-1', $name, 'widget_reloaded_calendar_control', $control_ops, array( 'number' => -1 ) );

	endif;
}

?>