<?php
/**
 * Categories Widget
 * Replaces the default WordPress Categories widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the Categories widget
 * Arguments are based on the wp_list_categories() function
 * @link http://codex.wordpress.org/Template_Tags/wp_list_categories
 *
 * 'exclude_tree' still seems to be buggy with WP
 *
 * @since 0.1
 */
function widget_reloaded_categories( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_categories' );

	if ( !isset( $options[$number] ) )
		return;

	$title = apply_filters( 'widget_title', $options[$number]['title'] );
	$orderby = $options[$number]['orderby'];
	$order = $options[$number]['order'];
	$exclude = $options[$number]['exclude'];
	$exclude_tree = $options[$number]['exclude_tree'];
	$include = $options[$number]['include'];
	$depth = (int)$options[$number]['depth'];
	$cat_number = (int)$options[$number]['cat_number'];
	$child_of = (int)$options[$number]['child_of'];
	$current_category = (int)$options[$number]['current_category'];
	$feed_image = $options[$number]['feed_image'];

	$hierarchical = $options[$number]['hierarchical'] ? '1' : '0';
	$use_desc_for_title = $options[$number]['use_desc_for_title'] ? '1' : '0';
	$show_last_updated = $options[$number]['show_last_updated'] ? '1' : '0';
	$show_count = $options[$number]['show_count'] ? '1' : '0';
	$hide_empty = $options[$number]['hide_empty'] ? '1' : '0';
	$feed = $options[$number]['feed'] ? '1' : '0';
	if ( $feed )
		$feed = __('RSS','reloaded');
	$dropdown = $options[$number]['dropdown'] ? '1' : '0';

	$categories = array(
		'exclude' => $exclude,
		//'exclude_tree' => $exclude_tree,
		'include' => $include,
		'number' => $cat_number,
		'depth' => $depth,
		'orderby' => $orderby,
		'order' => $order,
		'show_last_updated' => $show_last_updated,
		'style' => 'list',
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
	);

	echo $before_widget;

	if ( $title )
		echo $before_title . $title . $after_title;

	echo '<ul class="xoxo categories">';

	echo str_replace( array( "\r", "\n", "\t" ), '', wp_list_categories( $categories ) );

	echo '</ul>' . $after_widget;
}

/**
 * Widget controls for the categories widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1
 * @param int $widget_args
 */
function widget_reloaded_categories_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_categories' );

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

			if ( 'widget_reloaded_categories' == $wp_registered_widgets[$_widget_id]['callback'] && isset( $wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-categories'] as $widget_number => $widget_reloaded_categories ) :

			$use_desc_for_title = strip_tags( stripslashes( $widget_reloaded_categories['use_desc_for_title'] ) );

			$title = strip_tags( stripslashes( $widget_reloaded_categories['title'] ) );

			$exclude = strip_tags( stripslashes( $widget_reloaded_categories['exclude'] ) );

			$exclude_tree = strip_tags( stripslashes( $widget_reloaded_categories['exclude_tree'] ) );

			$include = strip_tags( stripslashes( $widget_reloaded_categories['include'] ) );

			$order = strip_tags( stripslashes( $widget_reloaded_categories['order'] ) );

			$orderby = strip_tags( stripslashes( $widget_reloaded_categories['orderby'] ) );

			$feed = strip_tags( stripslashes( $widget_reloaded_categories['feed'] ) );

			$feed_image = strip_tags( stripslashes( $widget_reloaded_categories['feed_image'] ) );

			$child_of = strip_tags( stripslashes( $widget_reloaded_categories['child_of'] ) );

			$depth = strip_tags( stripslashes( $widget_reloaded_categories['depth'] ) );

			$show_count = strip_tags( stripslashes( $widget_reloaded_categories['show_count'] ) );

			$show_last_updated = strip_tags( stripslashes( $widget_reloaded_categories['show_last_updated'] ) );

			$hide_empty = strip_tags( stripslashes( $widget_reloaded_categories['hide_empty'] ) );

			$cat_number = strip_tags( stripslashes( $widget_reloaded_categories['cat_number'] ) );

			$hierarchical = strip_tags( stripslashes( $widget_reloaded_categories['hierarchical'] ) );

			$current_category = strip_tags( stripslashes( $widget_reloaded_categories['current_category'] ) );

			$options[$widget_number] = compact( 'title', 'use_desc_for_title', 'exclude', 'exclude_tree', 'include', 'orderby', 'order', 'show_last_updated', 'show_count', 'hide_empty', 'feed', 'feed_image', 'child_of', 'depth', 'cat_number', 'hierarchical', 'current_category' );

		endforeach;

		update_option( 'widget_reloaded_categories', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$title = '';
		$exclude = '';
		$include = '';
		$orderby = '';
		$order = '';
		$use_desc_for_title = '';
		$show_last_updated = '';
		$show_count = '';
		$hide_empty = '';
		$feed = '';
		$feed_image = '';
		$child_of = '';
		$depth = '';
		$cat_number = '';
		$hierarchical = '';
		$current_category = '';
		$exclude_tree = '';
		$number = '%i%';
	else :
		$title = attribute_escape( $options[$number]['title'] );
		$exclude = attribute_escape( $options[$number]['exclude'] );
		$include = attribute_escape( $options[$number]['include'] );
		$orderby = attribute_escape( $options[$number]['orderby'] );
		$order = attribute_escape( $options[$number]['order'] );
		$use_desc_for_title = attribute_escape( $options[$number]['use_desc_for_title'] );
		$show_count = attribute_escape( $options[$number]['show_count'] );
		$show_last_updated = attribute_escape( $options[$number]['show_last_updated'] );
		$hide_empty = attribute_escape( $options[$number]['hide_empty'] );
		$feed = attribute_escape( $options[$number]['feed'] );
		$feed_image = attribute_escape( $options[$number]['feed_image'] );
		$child_of = attribute_escape( $options[$number]['child_of'] );
		$depth = attribute_escape( $options[$number]['depth'] );
		$cat_number = attribute_escape( $options[$number]['cat_number'] );
		$hierarchical = attribute_escape( $options[$number]['hierarchical'] );
		$current_category = attribute_escape( $options[$number]['current_category'] );
		$exclude_tree = attribute_escape( $options[$number]['exclude_tree'] );
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-categories-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-categories-title-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-categories-orderby-<?php echo $number; ?>">
			<?php _e('Order By:','reloaded'); ?> <code>orderby</code>
		</label>

		<select id="reloaded-categories-orderby-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][orderby]" style="width:100%;">
			<option <?php if ( 'name' == $orderby ) echo 'selected="selected"'; ?>>name</option>
			<option <?php if ( 'ID' == $orderby ) echo 'selected="selected"'; ?>>ID</option>
			<option <?php if ( 'count' == $orderby ) echo 'selected="selected"'; ?>>count</option>
		</select>
	</p>

	<p>
		<label for="reloaded-categories-order-<?php echo $number; ?>">
			<?php _e('Order:','reloaded'); ?> <code>order</code>
		</label>

		<select id="reloaded-categories-order-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][order]" class="widefat" style="width:100%;">
			<option <?php if ( 'ASC' == $order ) echo 'selected="selected"'; ?>>ASC</option>
			<option <?php if ( 'DESC' == $order ) echo 'selected="selected"'; ?>>DESC</option>
		</select>
	</p>
	<p>
		<label for="reloaded-categories-exclude-<?php echo $number; ?>">
			<?php _e('Exclude:','reloaded'); ?> <code>exclude</code>
		</label>
		<input id="reloaded-categories-exclude-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][exclude]" type="text" value="<?php echo $exclude; ?>" style="width:100%;" />
	</p>
<?php /*
	<p>
		<label for="reloaded-categories-exclude_tree-<?php echo $number; ?>">
			<?php _e('Exclude Tree:','reloaded'); ?> <em><?php _e('(WP 2.7+)', 'reloaded'); ?></em> <code>exclude_tree</code>
		</label>
		<input id="reloaded-categories-exclude_tree-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][exclude_tree]" type="text" value="<?php echo $exclude_tree; ?>" style="width:100%;" />
	</p>
*/ ?>
	<p>
		<label for="reloaded-categories-include-<?php echo $number; ?>">
			<?php _e('Include:','reloaded'); ?> <code>include</code>
		</label>
		<input id="reloaded-categories-include-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][include]" type="text" value="<?php echo $include; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-categories-depth-<?php echo $number; ?>">
			<?php _e('Depth:','reloaded'); ?> <code>depth</code>
		</label>
		<input id="reloaded-categories-depth-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][depth]" type="text" value="<?php echo $depth; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-categories-cat_number-<?php echo $number; ?>">
			<?php _e('Number:','reloaded'); ?> <code>number</code>
		</label>
		<input id="reloaded-categories-cat_number-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][cat_number]" type="text" value="<?php echo $cat_number; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<label for="reloaded-categories-current_category-<?php echo $number; ?>">
			<?php _e('Current Category:','reloaded'); ?> <code>current_category</code>
		</label>
		<input id="reloaded-categories-current_category-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][current_category]" type="text" value="<?php echo $current_category; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-categories-child_of-<?php echo $number; ?>">
			<?php _e('Child Of:','reloaded'); ?> <code>child_of</code>
		</label>
		<input id="reloaded-categories-child_of-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][child_of]" type="text" value="<?php echo $child_of; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-categories-feed_image-<?php echo $number; ?>">
			<?php _e('Feed Image:','reloaded'); ?> <code>feed_image</code>
		</label>
		<input id="reloaded-categories-feed_image-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][feed_image]" type="text" value="<?php echo $feed_image; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-hierarchical-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][hierarchical]" <?php if($hierarchical == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-hierarchical-<?php echo $number; ?>">
			<?php _e('Hierarchical?','reloaded'); ?> <code>hierarchical</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-feed-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][feed]" <?php if($feed == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-feed-<?php echo $number; ?>">
			<?php _e('Show RSS feed?','reloaded'); ?> <code>feed</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-use_desc_for_title-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][use_desc_for_title]" <?php if($use_desc_for_title == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-use_desc_for_title-<?php echo $number; ?>">
			<?php _e('Use description for title?','reloaded'); ?> <code>use_desc_for_title</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-show_last_updated-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][show_last_updated]" <?php if($show_last_updated == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-show_last_updated-<?php echo $number; ?>">
			<?php _e('Show last updated?','reloaded'); ?> <code>show_last_updated</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-show_count-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][show_count]" <?php if($show_count == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-show_count-<?php echo $number; ?>">
			<?php _e('Show count?','reloaded'); ?> <code>show_count</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-categories-hide_empty-<?php echo $number; ?>" name="widget-reloaded-categories[<?php echo $number; ?>][hide_empty]" <?php if($hide_empty == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-categories-hide_empty-<?php echo $number; ?>">
			<?php _e('Hide empty?','reloaded'); ?> <code>hide_empty</code>
		</label>
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-categories-submit-<?php echo $number; ?>" name="reloaded-categories-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
 * Register the categories widget
 * Register the categories widget controls
 *
 * @since 0.1
 */
function widget_reloaded_categories_register() {

	if ( !$options = get_option( 'widget_reloaded_categories' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'categories',
		'description' => __('An advanced widget that gives you total control over the output of your category links.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-categories',
	);

	$name = __('Categories','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset( $options[$o]['title'] ) )
			continue;

		$id = 'reloaded-categories-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_categories', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_categories_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-categories-1', $name, 'widget_reloaded_categories', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-categories-1', $name, 'widget_reloaded_categories_control', $control_ops, array( 'number' => -1 ) );

	endif;

}

?>