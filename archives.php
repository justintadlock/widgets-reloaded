<?php

/**
* Advanced archives widget
* Replaces the default WordPress Archives widget
* Arguments are input through the widget control panel
*
* @since 0.1
*/
function widget_reloaded_archives($args, $widget_args = 1) {

	extract($args, EXTR_SKIP);

	if(is_numeric($widget_args))
		$widget_args = array('number' => $widget_args);

	$widget_args = wp_parse_args($widget_args, array('number' => -1));

	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_reloaded_archives');

	if(!isset($options[$number]))
		return;

	$title = apply_filters('widget_title', $options[$number]['title']);
	$type = $options[$number]['type'];
	$format = $options[$number]['format'];
	$before = $options[$number]['before'];
	$after = $options[$number]['after'];
	$limit = (int)$options[$number]['limit'];

	if(!$limit)
		$limit = '';

	$show_post_count = $options[$number]['show_post_count'] ? '1' : '0';

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

	if($title)
		echo $before_title . $title . $after_title;

	if($format == 'option') :

		if($type == 'yearly') :
			$option_title = __('Select Year','reloaded');
		elseif($type == 'monthly') :
			$option_title = __('Select Month','reloaded');
		elseif($type == 'weekly') :
			$option_title = __('Select Week','reloaded');
		elseif($type == 'daily') :
			$option_title = __('Select Day','reloaded');
		elseif($type == 'postbypost') :
			$option_title = __('Select Post','reloaded');
		endif;

		echo '<select name="archive-dropdown" onchange=\'document.location.href=this.options[this.selectedIndex].value;\'>';

		echo '<option value="">' . attribute_escape($option_title) . '</option>';

		echo str_replace(array("\r", "\n", "\t"), '', wp_get_archives($archives));

		echo '</select>';

	elseif($format == 'html') :

		echo '<ul class="xoxo archives">';

		echo str_replace(array("\r", "\n", "\t"), '', wp_get_archives($archives));

		echo '</ul>';

	else :

		echo str_replace(array("\r", "\n", "\t"), '', wp_get_archives($archives));

	endif;

	echo $after_widget;
}

/**
* Widget controls for the archives widget
* Options are chosen from user input from the widget panel
*
* @since 0.1
*/
function widget_reloaded_archives_control($widget_args) {

	global $wp_registered_widgets;

	static $updated = false;

	if(is_numeric($widget_args))
		$widget_args = array('number' => $widget_args);

	$widget_args = wp_parse_args($widget_args, array('number' => -1));

	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_reloaded_archives');

	if(!is_array($options))
		$options = array();

	if(!$updated && !empty($_POST['sidebar'])) :

		$sidebar = (string)$_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();

		if(isset($sidebars_widgets[$sidebar]))
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach($this_sidebar as $_widget_id) :

			if('widget_reloaded_archives' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset($options[$widget_number]);

			endif;

		endforeach;

		foreach((array)$_POST['widget-reloaded-archives'] as $widget_number => $widget_reloaded_archives) :

			$title = strip_tags(stripslashes($widget_reloaded_archives['title']));

			$type = strip_tags(stripslashes($widget_reloaded_archives['type']));

			$limit = strip_tags(stripslashes($widget_reloaded_archives['limit']));

			$format = strip_tags(stripslashes($widget_reloaded_archives['format']));

			$before = stripslashes($widget_reloaded_archives['before']);

			$after = stripslashes($widget_reloaded_archives['after']);

			$show_post_count = strip_tags(stripslashes($widget_reloaded_archives['show_post_count']));

			$options[$widget_number] = compact('title', 'limit', 'type', 'format', 'before', 'after', 'show_post_count');

		endforeach;

		update_option('widget_reloaded_archives', $options);

		$updated = true;

	endif;

	if($number == -1) :
		$title = '';
		$type = '';
		$limit = '';
		$format = '';
		$before = '';
		$after = '';
		$show_post_count = '';
		$number = '%i%';
	else :
		$title = attribute_escape($options[$number]['title']);
		$type = attribute_escape($options[$number]['type']);
		$limit = attribute_escape($options[$number]['limit']);
		$format = attribute_escape($options[$number]['format']);
		$before = attribute_escape($options[$number]['before']);
		$after = attribute_escape($options[$number]['after']);
		$show_post_count = attribute_escape($options[$number]['show_post_count']);
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-archives-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-archives-title-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-archives-type-<?php echo $number; ?>">
			<?php _e('Type:','reloaded'); ?> <code>type</code>
		</label>

		<select id="reloaded-archives-type-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][type]" class="widefat" style="width:100%;">
			<option <?php if('yearly' == $type) echo 'selected="selected"'; ?>>yearly</option>
			<option <?php if('monthly' == $type) echo 'selected="selected"'; ?>>monthly</option>
			<option <?php if('weekly' == $type) echo 'selected="selected"'; ?>>weekly</option>
			<option <?php if('daily' == $type) echo 'selected="selected"'; ?>>daily</option>
			<option <?php if('postbypost' == $type) echo 'selected="selected"'; ?>>postbypost</option>
		</select>
	</p>
	<p>
		<label for="reloaded-archives-format-<?php echo $number; ?>">
			<?php _e('Format:','reloaded'); ?> <code>format</code>
		</label>

		<select id="reloaded-archives-format-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][format]" class="widefat" style="width:100%;">
			<option <?php if('html' == $format) echo 'selected="selected"'; ?>>html</option>
			<option <?php if('option' == $format) echo 'selected="selected"'; ?>>option</option>
			<option <?php if('custom' == $format) echo 'selected="selected"'; ?>>custom</option>
		</select>
	</p>
	<p>
		<label for="reloaded-archives-limit-<?php echo $number; ?>">
			<?php _e('Limit:','reloaded'); ?> <code>limit</code>
		</label>
		<input id="reloaded-archives-limit-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][limit]" type="text" value="<?php echo $limit; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<label for="reloaded-archives-before-<?php echo $number; ?>">
			<?php _e('Before:','reloaded'); ?> <code>before</code>
		</label>
		<input id="reloaded-archives-before-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][before]" type="text" value="<?php echo $before; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-archives-after-<?php echo $number; ?>">
			<?php _e('After:','reloaded'); ?> <code>after</code>
		</label>
		<input id="reloaded-archives-after-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][after]" type="text" value="<?php echo $after; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-archives-show_post_count-<?php echo $number; ?>" name="widget-reloaded-archives[<?php echo $number; ?>][show_post_count]" <?php if($show_post_count == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-archives-show_post_count-<?php echo $number; ?>">
			<?php _e('Show post count?','reloaded'); ?> <code>show_post_count</code>
		</label>
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-archives-submit-<?php echo $number; ?>" name="reloaded-archives-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
* Register the archives widget
* Register the archives widget controls
*
* @since 0.1
*/
function widget_reloaded_archives_register() {

	if(!$options = get_option('widget_reloaded_archives'))
		$options = array();

	$widget_ops = array(
		'classname' => 'archives',
		'description' => __('An advanced widget that gives you total control over the output of your archives.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-archives',
	);

	$name = __('Archives','reloaded');

	$id = false;

	foreach(array_keys($options) as $o) :

		if(!isset($options[$o]['title']))
			continue;

		$id = 'reloaded-archives-' . $o;

		wp_register_sidebar_widget($id, $name, 'widget_reloaded_archives', $widget_ops, array('number' => $o));

		wp_register_widget_control($id, $name, 'widget_reloaded_archives_control', $control_ops, array('number' => $o));

	endforeach;

	if(!$id) :

		wp_register_sidebar_widget('reloaded-archives-1', $name, 'widget_reloaded_archives', $widget_ops, array('number' => -1));

		wp_register_widget_control('reloaded-archives-1', $name, 'widget_reloaded_archives_control', $control_ops, array('number' => -1));

	endif;

}

?>