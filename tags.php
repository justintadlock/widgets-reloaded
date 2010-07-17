<?php
/**
 * Tags Widget
 * Replaces the default WordPress Tag Cloud widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the tags widget
 * Each setting is an argument for wp_tag_cloud()
 * @link http://codex.wordpress.org/Template_Tags/wp_tag_cloud
 *
 * @since 0.1
 */
function widget_reloaded_tags( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_tags' );

	if ( !isset( $options[$number] ) )
		return;

	$title = apply_filters( 'widget_title', $options[$number]['title'] );
	$format = $options[$number]['format'];
	$order = $options[$number]['order'];
	$orderby = $options[$number]['orderby'];
	$unit = $options[$number]['unit'];
	$largest = (int)$options[$number]['largest'];
	$smallest = (int)$options[$number]['smallest'];
	$tag_number = (int)$options[$number]['tag_number'];
	$exclude = $options[$number]['exclude'];
	$include = $options[$number]['include'];

	if ( !$largest )
		$largest = 22;
	if ( !$smallest )
		$smallest = 8;

	$tags = array(
		'smallest' => $smallest,
		'largest' => $largest,
		'unit' => $unit,
		'number' => $tag_number,
		'format' => $format,
		'orderby' => $orderby,
		'order' => $order,
		'exclude' => $exclude,
		'include' => $include,
	);

	echo $before_widget;

	if ( $title )
		echo $before_title . $title . $after_title;

	if ( $format == 'flat' ) :
		echo '<p class="tag-cloud">';
		wp_tag_cloud( $tags );
		echo '</p>';
	else :
		wp_tag_cloud( $tags );
	endif;

	echo $after_widget;
}

/**
 * Widget controls for the tags widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1
 */
function widget_reloaded_tags_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_tags' );

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

			if ( 'widget_reloaded_tags' == $wp_registered_widgets[$_widget_id]['callback'] && isset( $wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-tags'] as $widget_number => $widget_reloaded_tags ) :

			$title = strip_tags( stripslashes( $widget_reloaded_tags['title'] ) );

			$tag_number = strip_tags( stripslashes( $widget_reloaded_tags['tag_number'] ) );

			$format = strip_tags( stripslashes( $widget_reloaded_tags['format'] ) );

			$smallest = strip_tags( stripslashes( $widget_reloaded_tags['smallest'] ) );

			$largest = strip_tags( stripslashes( $widget_reloaded_tags['largest'] ) );

			$unit = strip_tags( stripslashes( $widget_reloaded_tags['unit'] ) );

			$order = strip_tags( stripslashes( $widget_reloaded_tags['order'] ) );

			$orderby = strip_tags( stripslashes( $widget_reloaded_tags['orderby'] ) );

			$exclude = strip_tags( stripslashes( $widget_reloaded_tags['exclude'] ) );

			$include = strip_tags( stripslashes( $widget_reloaded_tags['include'] ) );

			$options[$widget_number] = compact( 'title', 'smallest', 'largest', 'unit', 'tag_number', 'format', 'orderby', 'order', 'exclude', 'include' );

		endforeach;

		update_option( 'widget_reloaded_tags', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$title = '';
		$smallest = '';
		$largest = '';
		$unit = '';
		$tag_number = '';
		$format = '';
		$orderby = '';
		$order = '';
		$exclude = '';
		$include = '';
		$number = '%i%';
	else :
		$title = attribute_escape( $options[$number]['title'] );
		$smallest = attribute_escape( $options[$number]['smallest'] );
		$largest = attribute_escape( $options[$number]['largest'] );
		$unit = attribute_escape( $options[$number]['unit'] );
		$tag_number = attribute_escape( $options[$number]['tag_number'] );
		$format = attribute_escape( $options[$number]['format'] );
		$orderby = attribute_escape( $options[$number]['orderby'] );
		$order = attribute_escape( $options[$number]['order'] );
		$exclude = attribute_escape( $options[$number]['exclude'] );
		$include = attribute_escape( $options[$number]['include'] );
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-tags-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-tags-title-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-tags-format-<?php echo $number; ?>">
			<?php _e('Format:','reloaded'); ?> <code>format</code>
		</label>

		<select id="reloaded-tags-format-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][format]" class="widefat" style="width:100%;">
			<option <?php if ( 'flat' == $format ) echo 'selected="selected"'; ?>>flat</option>
			<option <?php if ( 'list' == $format ) echo 'selected="selected"'; ?>>list</option>
		</select>
	</p>
	<p>
		<label for="reloaded-tags-order-<?php echo $number; ?>">
			<?php _e('Order:','reloaded'); ?> <code>order</code>
		</label>

		<select id="reloaded-tags-order-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][order]" class="widefat" style="width:100%;">
			<option <?php if ( 'ASC' == $order ) echo 'selected="selected"'; ?>>ASC</option>
			<option <?php if ( 'DESC' == $order ) echo 'selected="selected"'; ?>>DESC</option>
			<option <?php if ( 'RAND' == $order ) echo 'selected="selected"'; ?>>RAND</option>
		</select>
	</p>
	<p>
		<label for="reloaded-tags-orderby-<?php echo $number; ?>">
			<?php _e('Order By:','reloaded'); ?> <code>orderby</code>
		</label>

		<select id="reloaded-tags-orderby-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][orderby]" class="widefat" style="width:100%;">
			<option <?php if ( 'name' == $orderby ) echo 'selected="selected"'; ?>>name</option>
			<option <?php if ( 'count' == $orderby ) echo 'selected="selected"'; ?>>count</option>
		</select>
	</p>
	<p>
		<label for="reloaded-tags-tag_number-<?php echo $number; ?>">
			<?php _e('Number:','reloaded'); ?> <code>number</code>
		</label>
		<input id="reloaded-tags-tag_number-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][tag_number]" type="text" value="<?php echo $tag_number; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<label for="reloaded-tags-largest-<?php echo $number; ?>">
			<?php _e('Largest:','reloaded'); ?> <code>largest</code>
		</label>
		<input id="reloaded-tags-largest-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][largest]" type="text" value="<?php echo $largest; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-tags-smallest-<?php echo $number; ?>">
			<?php _e('Smallest:','reloaded'); ?> <code>smallest</code>
		</label>
		<input id="reloaded-tags-smallest-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][smallest]" type="text" value="<?php echo $smallest; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-tags-unit-<?php echo $number; ?>">
			<?php _e('Unit:','reloaded'); ?> <code>unit</code>
		</label>

		<select id="reloaded-tags-unit-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][unit]" class="widefat" style="width:100%;">
			<option <?php if ( 'pt' == $unit ) echo 'selected="selected"'; ?>>pt</option>
			<option <?php if ( 'px' == $unit ) echo 'selected="selected"'; ?>>px</option>
			<option <?php if ( 'em' == $unit ) echo 'selected="selected"'; ?>>em</option>
			<option <?php if ( '%' == $unit ) echo 'selected="selected"'; ?>>%</option>
		</select>
	</p>
	<p>
		<label for="reloaded-tags-exclude-<?php echo $number; ?>">
			<?php _e('Exclude:','reloaded'); ?> <code>exclude</code>
		</label>
		<input id="reloaded-tags-exclude-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][exclude]" type="text" value="<?php echo $exclude; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-tags-include-<?php echo $number; ?>">
			<?php _e('Include:','reloaded'); ?> <code>include</code>
		</label>
		<input id="reloaded-tags-include-<?php echo $number; ?>" name="widget-reloaded-tags[<?php echo $number; ?>][include]" type="text" value="<?php echo $include; ?>" style="width:100%;" />
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-tags-submit-<?php echo $number; ?>" name="reloaded-tags-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
 * Register the tags widget
 * Register the tags widget controls
 *
 * @since 0.1
 */
function widget_reloaded_tags_register() {

	if ( !$options = get_option( 'widget_reloaded_tags' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'tags',
		'description' => __('An advanced widget that gives you total control over the output of your tags.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-tags',
	);

	$name = __('Tags','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset( $options[$o]['title'] ) )
			continue;

		$id = 'reloaded-tags-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_tags', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_tags_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-tags-1', $name, 'widget_reloaded_tags', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-tags-1', $name, 'widget_reloaded_tags_control', $control_ops, array( 'number' => -1 ) );

	endif;

}

?>