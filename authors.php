<?php

/**
* Advanced authors widget
* Replaces the default WordPress Authors widget
* Arguments are input through the widget control panel
*
* @since 0.1.1
*/
function widget_reloaded_authors($args, $widget_args = 1) {

	extract($args, EXTR_SKIP);

	if(is_numeric($widget_args))
		$widget_args = array('number' => $widget_args);

	$widget_args = wp_parse_args($widget_args, array('number' => -1));

	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_reloaded_authors');

	if(!isset($options[$number]))
		return;

	$title = apply_filters('widget_title', $options[$number]['title']);
	$feed = $options[$number]['feed'];
	$feed_image = $options[$number]['feed_image'];
	$optioncount = $options[$number]['optioncount'] ? '1' : '0';
	$exclude_admin = $options[$number]['exclude_admin'] ? '1' : '0';
	$show_fullname = $options[$number]['show_fullname'] ? '1' : '0';
	$hide_empty = $options[$number]['hide_empty'] ? '1' : '0';

	$authors = array(
		'optioncount' => $optioncount,
		'exclude_admin' => $exclude_admin,
		'show_fullname' => $show_fullname,
		'hide_empty' => $hide_empty,
		'feed' => $feed,
		'feed_image' => $feed_image,
		'echo' => 0,
	);

	echo $before_widget;

	if($title)
		echo $before_title . $title . $after_title;

	echo '<ul class="xoxo authors">';

	echo str_replace(array("\r", "\n", "\t"), '', wp_list_authors($authors));

	echo '</ul>';

	echo $after_widget;
}

/**
* Widget controls for the authors widget
* Options are chosen from user input from the widget panel
*
* @since 0.1.1
*/
function widget_reloaded_authors_control($widget_args) {

	global $wp_registered_widgets;

	static $updated = false;

	if(is_numeric($widget_args))
		$widget_args = array('number' => $widget_args);

	$widget_args = wp_parse_args($widget_args, array('number' => -1));

	extract($widget_args, EXTR_SKIP);

	$options = get_option('widget_reloaded_authors');

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

			if('widget_reloaded_authors' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset($options[$widget_number]);

			endif;

		endforeach;

		foreach((array)$_POST['widget-reloaded-authors'] as $widget_number => $widget_reloaded_authors) :

			$title = strip_tags(stripslashes($widget_reloaded_authors['title']));

			$optioncount = strip_tags(stripslashes($widget_reloaded_authors['optioncount']));

			$exclude_admin = strip_tags(stripslashes($widget_reloaded_authors['exclude_admin']));

			$show_fullname = strip_tags(stripslashes($widget_reloaded_authors['show_fullname']));

			$hide_empty = strip_tags(stripslashes($widget_reloaded_authors['hide_empty']));

			$feed = strip_tags(stripslashes($widget_reloaded_authors['feed']));

			$feed_image = strip_tags(stripslashes($widget_reloaded_authors['feed_image']));

			$options[$widget_number] = compact('title', 'optioncount', 'exclude_admin', 'show_fullname', 'hide_empty', 'feed', 'feed_image');

		endforeach;

		update_option('widget_reloaded_authors', $options);

		$updated = true;

	endif;

	if($number == -1) :
		$title = '';
		$optioncount = '';
		$exclude_admin = '';
		$show_fullname = '';
		$hide_empty = '';
		$feed = '';
		$feed_image = '';
		$number = '%i%';
	else :
		$title = attribute_escape($options[$number]['title']);
		$optioncount = attribute_escape($options[$number]['optioncount']);
		$exclude_admin = attribute_escape($options[$number]['exclude_admin']);
		$show_fullname = attribute_escape($options[$number]['show_fullname']);
		$hide_empty = attribute_escape($options[$number]['hide_empty']);
		$feed = attribute_escape($options[$number]['feed']);
		$feed_image = attribute_escape($options[$number]['feed_image']);
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-authors-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-authors-title-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-authors-feed-<?php echo $number; ?>">
			<?php _e('Feed:','reloaded'); ?> <code>feed</code>
		</label>
		<input id="reloaded-authors-feed-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][feed]" type="text" value="<?php echo $feed; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-authors-feed_image-<?php echo $number; ?>">
			<?php _e('Feed Image:','reloaded'); ?> <code>feed_image</code>
		</label>
		<input id="reloaded-authors-feed_image-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][feed_image]" type="text" value="<?php echo $feed_image; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<input type="checkbox" id="reloaded-authors-optioncount-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][optioncount]" <?php if($optioncount == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-authors-optioncount-<?php echo $number; ?>">
			<?php _e('Show post count?','reloaded'); ?> <code>optioncount</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-authors-exclude_admin-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][exclude_admin]" <?php if($exclude_admin == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-authors-exclude_admin-<?php echo $number; ?>">
			<?php _e('Exclude admin?','reloaded'); ?> <code>exclude_admin</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-authors-show_fullname-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][show_fullname]" <?php if($show_fullname == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-authors-show_fullname-<?php echo $number; ?>">
			<?php _e('Show full name?','reloaded'); ?> <code>show_fullname</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-authors-hide_empty-<?php echo $number; ?>" name="widget-reloaded-authors[<?php echo $number; ?>][hide_empty]" <?php if($hide_empty == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-authors-hide_empty-<?php echo $number; ?>">
			<?php _e('Hide empty?','reloaded'); ?> <code>hide_empty</code>
		</label>
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-authors-submit-<?php echo $number; ?>" name="reloaded-authors-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
* Register the authors widget
* Register the authors widget controls
*
* @since 0.1.1
*/
function widget_reloaded_authors_register() {

	if(!$options = get_option('widget_reloaded_authors'))
		$options = array();

	$widget_ops = array(
		'classname' => 'authors',
		'description' => __('An advanced widget that gives you total control over the output of your author lists.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-authors',
	);

	$name = __('Authors','reloaded');

	$id = false;

	foreach(array_keys($options) as $o) :

		if(!isset($options[$o]['title']))
			continue;

		$id = 'reloaded-authors-' . $o;

		wp_register_sidebar_widget($id, $name, 'widget_reloaded_authors', $widget_ops, array('number' => $o));

		wp_register_widget_control($id, $name, 'widget_reloaded_authors_control', $control_ops, array('number' => $o));

	endforeach;

	if(!$id) :

		wp_register_sidebar_widget('reloaded-authors-1', $name, 'widget_reloaded_authors', $widget_ops, array('number' => -1));

		wp_register_widget_control('reloaded-authors-1', $name, 'widget_reloaded_authors_control', $control_ops, array('number' => -1));

	endif;

}

?>