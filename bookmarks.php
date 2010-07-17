<?php
/**
 * Bookmarks Widget
 * Replaces the default WordPress Links widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the Bookmarks widget(s)
 * Arguments are based on the wp_list_bookmarks() function
 * @link http://codex.wordpress.org/Template_Tags/wp_list_bookmarks
 *
 * @since 0.1
 */
function widget_reloaded_bookmarks( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_bookmarks' );

	if ( !isset( $options[$number] ) )
		return;

	$category = $options[$number]['category'];
	$category_name = $options[$number]['category_name'];
	$category_order = $options[$number]['category_order'];
	$category_orderby = $options[$number]['category_orderby'];
	$exclude_category = $options[$number]['exclude_category'];
	$limit = (int)$options[$number]['limit'];
	$include = $options[$number]['include'];
	$exclude = $options[$number]['exclude'];
	$orderby = $options[$number]['orderby'];
	$order = $options[$number]['order'];
	$between = $options[$number]['between'];

	$categorize = $options[$number]['categorize'] ? '1' : '0';
	$show_description = $options[$number]['show_description'] ? '1' : '0';
	$hide_invisible = $options[$number]['hide_invisible'] ? '1' : '0';
	$show_rating = $options[$number]['show_rating'] ? '1' : '0';
	$show_updated = $options[$number]['show_updated'] ? '1' : '0';
	$show_images = $options[$number]['show_images'] ? '1' : '0';
	$show_private = $options[$number]['show_private'] ? '1' : '0';

	if ( $categorize )
		$before_widget = preg_replace( '/id="[^"]*"/', 'id="%id"', $before_widget );

	if ( !$limit )
		$limit = -1;

	$bookmarks = array(
		'orderby' => $orderby,
		'order' => $order,
		'limit' => $limit,
		'include' => $include,
		'exclude' => $exclude,
		'hide_invisible' => $hide_invisible,
		'show_rating' => $show_rating,
		'show_updated' => $show_updated,
		'show_description' => $show_description,
		'show_images' => $show_images,
		'between' => ' ' . $between,
		'categorize' => $categorize,
		'category' => $category,
		'exclude_category' => $exclude_category,
		'category_name' => $category_name,
		'show_private' => $show_private,
		'category_orderby' => $category_orderby,
		'category_order' => $category_order,
		'title_li' => $title,
		'title_before' => $before_title,
		'title_after' => $after_title,
		'category_before' => $before_widget,
		'category_after' => $after_widget,
		'link_before' => '<span>',
		'link_after' => '</span>',
		'class' => 'bookmark-cat',
		'echo' => 0,
	);

	if ( !$categorize )
		echo $before_widget . $before_title . __('Bookmarks','reloaded') . $after_title . '<ul class="xoxo blogroll">';

	echo str_replace( array( "\r", "\n", "\t" ), '', wp_list_bookmarks( $bookmarks ) );

	if ( !$categorize )
		echo '</ul>' . $after_widget;
}

/**
 * Widget controls for the bookmark widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1
 */
function widget_reloaded_bookmarks_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_bookmarks' );

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

			if ( 'widget_reloaded_bookmarks' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-bookmarks'] as $widget_number => $widget_reloaded_bookmarks ) :

			$categorize = strip_tags( stripslashes( $widget_reloaded_bookmarks['categorize'] ) );

			$category = strip_tags( stripslashes( $widget_reloaded_bookmarks['category'] ) );

			$category_name = apply_filters( 'link_category', $widget_reloaded_bookmarks['category_name'] );

			$category_order = strip_tags( stripslashes( $widget_reloaded_bookmarks['category_order'] ) );

			$category_orderby = strip_tags( stripslashes( $widget_reloaded_bookmarks['category_orderby'] ) );

			$exclude_category = strip_tags( stripslashes( $widget_reloaded_bookmarks['exclude_category'] ) );

			$limit = strip_tags( stripslashes( $widget_reloaded_bookmarks['limit'] ) );

			$include = strip_tags( stripslashes( $widget_reloaded_bookmarks['include'] ) );

			$exclude = strip_tags( stripslashes( $widget_reloaded_bookmarks['exclude'] ) );

			$orderby = strip_tags( stripslashes( $widget_reloaded_bookmarks['orderby'] ) );

			$order = strip_tags( stripslashes( $widget_reloaded_bookmarks['order'] ) );

			$show_description = strip_tags( stripslashes( $widget_reloaded_bookmarks['show_description'] ) );

			$between = stripslashes( $widget_reloaded_bookmarks['between'] );

			$hide_invisible = strip_tags( stripslashes( $widget_reloaded_bookmarks['hide_invisible'] ) );

			$show_private = strip_tags(stripslashes($widget_reloaded_bookmarks['show_private'] ) );

			$show_rating = strip_tags( stripslashes( $widget_reloaded_bookmarks['show_rating'] ) );

			$show_updated = strip_tags( stripslashes( $widget_reloaded_bookmarks['show_updated'] ) );

			$show_images = strip_tags( stripslashes( $widget_reloaded_bookmarks['show_images'] ) );

			$options[$widget_number] = compact( 'categorize', 'category', 'category_name', 'exclude_category', 'category_order', 'category_orderby', 'limit', 'order', 'orderby', 'show_description', 'between', 'hide_invisible', 'show_private', 'include', 'exclude', 'show_rating', 'show_updated', 'show_images' );

		endforeach;

		update_option( 'widget_reloaded_bookmarks', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$limit = '';
		$include = '';
		$exclude = '';
		$categorize = '';
		$category = '';
		$category_name = '';
		$category_order = '';
		$category_orderby = '';
		$exclude_category = '';
		$order = '';
		$orderby = '';
		$show_description = '';
		$between = '';
		$hide_invisible = '';
		$show_private = '';
		$show_rating = '';
		$show_updated = '';
		$show_images = '';
		$number = '%i%';
	else :
		$limit = attribute_escape( $options[$number]['limit'] );
		$include = attribute_escape( $options[$number]['include'] );
		$exclude = attribute_escape( $options[$number]['exclude'] );
		$categorize = attribute_escape( $options[$number]['categorize'] );
		$category = attribute_escape( $options[$number]['category'] );
		$category_name = attribute_escape( $options[$number]['category_name'] );
		$category_order = attribute_escape( $options[$number]['category_order'] );
		$category_orderby = attribute_escape( $options[$number]['category_orderby'] );
		$exclude_category = attribute_escape( $options[$number]['exclude_category'] );
		$order = attribute_escape( $options[$number]['order'] );
		$orderby = attribute_escape( $options[$number]['orderby'] );
		$show_description = attribute_escape( $options[$number]['show_description'] );
		$between = attribute_escape( $options[$number]['between'] );
		$hide_invisible = attribute_escape( $options[$number]['hide_invisible'] );
		$show_private = attribute_escape( $options[$number]['show_private'] );
		$show_rating = attribute_escape( $options[$number]['show_rating'] );
		$show_updated = attribute_escape( $options[$number]['show_updated'] );
		$show_images = attribute_escape( $options[$number]['show_images'] );
	endif;

?>

	<div style="float:left;width:31%;">

	<p>
		<label for="reloaded-bookmarks-category-<?php echo $number; ?>">
			<?php _e('Categories:','reloaded'); ?> <code>category</code>
		</label>
		<input id="reloaded-bookmarks-category-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][category]" type="text" value="<?php echo $category; ?>" style="width:100%;" />
	</p>

	<p>
		<label for="reloaded-bookmarks-exclude_category-<?php echo $number; ?>">
			<?php _e('Exclude Categories:','reloaded'); ?> <code>exclude_category</code>
		</label>
		<input id="reloaded-bookmarks-exclude_category-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][exclude_category]" type="text" value="<?php echo $exclude_category; ?>" style="width:100%;" />
	</p>

	<p>
		<?php
			$cats = get_categories( array( 'type' => 'link' ) );
			$cats[] = false;
		?>
		<label for="reloaded-bookmarks-category_name-<?php echo $number; ?>">
			<?php _e('Category:','reloaded'); ?> <code>category_name</code>
		</label>

		<select id="reloaded-bookmarks-category_name-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][category_name]" class="widefat" style="width:100%;">
		<?php
			foreach ( $cats as $cat ) :
				echo '<option '; if ( $cat->name == $category_name ) echo ' selected="selected" '; echo '>' . $cat->name . '</option>';
			endforeach;
		?>
		</select>
	</p>

	<p>
		<label for="reloaded-bookmarks-category_order-<?php echo $number; ?>">
			<?php _e('Category Order:','reloaded'); ?> <code>category_order</code>
		</label>

		<select id="reloaded-bookmarks-category_order-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][category_order]" class="widefat" style="width:100%;">
			<option <?php if ( 'ASC' == $category_order ) echo 'selected="selected"'; ?>>ASC</option>
			<option <?php if ( 'DESC' == $category_order ) echo 'selected="selected"'; ?>>DESC</option>
		</select>
	</p>

	<p>
		<label for="reloaded-bookmarks-category_orderby-<?php echo $number; ?>">
			<?php _e('Order Categories By:','reloaded'); ?> <code>category_orderby</code>
		</label>

		<select id="reloaded-bookmarks-category_orderby-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][category_orderby]" class="widefat" style="width:100%;">
			<option <?php if ( 'name' == $category_orderby ) echo 'selected="selected"'; ?>>name</option>
			<option <?php if ( 'id' == $category_orderby ) echo 'selected="selected"'; ?>>id</option>
			<option <?php if ( 'slug' == $category_orderby ) echo 'selected="selected"'; ?>>slug</option>
			<option <?php if ( 'count' == $category_orderby ) echo 'selected="selected"'; ?>>count</option>
		</select>
	</p>

	</div>
	<div style="float:left;width:31%;margin-left:3.5%;">

	<p>
		<label for="reloaded-bookmarks-limit-<?php echo $number; ?>">
			<?php _e('Limit:','reloaded'); ?> <code>limit</code>
		</label>
		<input id="reloaded-bookmarks-limit-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][limit]" type="text" value="<?php echo $limit; ?>" style="width:100%;" />
	</p>

	<p>
		<label for="reloaded-bookmarks-include-<?php echo $number; ?>">
			<?php _e('Include Bookmarks:','reloaded'); ?> <code>include</code>
		</label>
		<input id="reloaded-bookmarks-include-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][include]" type="text" value="<?php echo $include; ?>" style="width:100%;" />
	</p>

	<p>
		<label for="reloaded-bookmarks-exclude-<?php echo $number; ?>">
			<?php _e('Exclude Bookmarks:','reloaded'); ?> <code>exclude</code>
		</label>
		<input id="reloaded-bookmarks-exclude-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][exclude]" type="text" value="<?php echo $exclude; ?>" style="width:100%;" />
	</p>

	<p>
		<label for="reloaded-bookmarks-order-<?php echo $number; ?>">
			<?php _e('Bookmarks Order:','reloaded'); ?> <code>order</code>
		</label>

		<select id="reloaded-bookmarks-order-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][order]" class="widefat" style="width:100%;">
			<option <?php if ( 'ASC' == $order ) echo 'selected="selected"'; ?>>ASC</option>
			<option <?php if ( 'DESC' == $order ) echo 'selected="selected"'; ?>>DESC</option>
		</select>
	</p>

	<p>
		<label for="reloaded-bookmarks-orderby-<?php echo $number; ?>">
			<?php _e('Order Bookmarks By:','reloaded'); ?> <code>orderby</code>
		</label>

		<select id="reloaded-bookmarks-orderby-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][orderby]" class="widefat" style="width:100%;">
			<option <?php if ( 'name' == $orderby ) echo 'selected="selected"'; ?>>name</option>
			<option <?php if ( 'id' == $orderby ) echo 'selected="selected"'; ?>>id</option>
			<option <?php if ( 'url' == $orderby ) echo 'selected="selected"'; ?>>url</option>
			<option <?php if ( 'target' == $orderby ) echo 'selected="selected"'; ?>>target</option>
			<option <?php if ( 'description' == $orderby ) echo 'selected="selected"'; ?>>description</option>
			<option <?php if ( 'owner' == $orderby ) echo 'selected="selected"'; ?>>owner</option>
			<option <?php if ( 'rating' == $orderby ) echo 'selected="selected"'; ?>>rating</option>
			<option <?php if ( 'updated' == $orderby ) echo 'selected="selected"'; ?>>updated</option>
			<option <?php if ( 'rel' == $orderby ) echo 'selected="selected"'; ?>>rel</option>
			<option <?php if ( 'notes' == $orderby ) echo 'selected="selected"'; ?>>notes</option>
			<option <?php if ( 'rss' == $orderby ) echo 'selected="selected"'; ?>>rss</option>
			<option <?php if ( 'length' == $orderby ) echo 'selected="selected"'; ?>>length</option>
			<option <?php if ( 'rand' == $orderby ) echo 'selected="selected"'; ?>>rand</option>
		</select>
	</p>

	</div>
	<div style="float:right;width:31%;margin-left:3.5%;">

	<p>
		<label for="reloaded-bookmarks-between-<?php echo $number; ?>">
			<?php _e('Between:','reloaded'); ?> <code>between</code>
		</label>
		<input id="reloaded-bookmarks-between-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][between]" type="text" value="<?php echo $between; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-categorize-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][categorize]" <?php if($categorize == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-categorize-<?php echo $number; ?>">
			<?php _e('Categorize?','reloaded'); ?> <code>categorize</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-show_description-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][show_description]" <?php if($show_description == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-show_description-<?php echo $number; ?>">
			<?php _e('Show description?','reloaded'); ?> <code>show_description</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-hide_invisible-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][hide_invisible]" <?php if($hide_invisible == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-hide_invisible-<?php echo $number; ?>">
			<?php _e('Hide invisible?','reloaded'); ?> <code>hide_invisible</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-show_private-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][show_private]" <?php if($show_private == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-show_private-<?php echo $number; ?>">
			<?php _e('Show private?','reloaded'); ?> <code>show_private</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-show_rating-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][show_rating]" <?php if($show_rating == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-show_rating-<?php echo $number; ?>">
			<?php _e('Show rating?','reloaded'); ?> <code>show_rating</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-show_updated-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][show_updated]" <?php if($show_updated == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-show_updated-<?php echo $number; ?>">
			<?php _e('Show updated?','reloaded'); ?> <code>show_updated</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-bookmarks-show_images-<?php echo $number; ?>" name="widget-reloaded-bookmarks[<?php echo $number; ?>][show_images]" <?php if($show_images == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-bookmarks-show_images-<?php echo $number; ?>">
			<?php _e('Show images?','reloaded'); ?> <code>show_images</code>
		</label>
	</p>


	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-bookmarks-submit-<?php echo $number; ?>" name="reloaded-bookmarks-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
 * Register the bookmarks widget
 * Register the bookmarks widget controls
 *
 * @since 0.1
 */
function widget_reloaded_bookmarks_register() {

	if ( !$options = get_option( 'widget_reloaded_bookmarks' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'bookmarks',
		'description' => __('An advanced widget that gives you total control over the output of your bookmarks (links).','reloaded'),
	);

	$control_ops = array(
		'width' => 800,
		'height' => 350,
		'id_base' => 'reloaded-bookmarks',
	);

	$name = __('Bookmarks','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset($options[$o]['category'] ) || !isset( $options[$o]['category_order'] ) || !isset( $options[$o]['category_orderby'] ) )
			continue;

		$id = 'reloaded-bookmarks-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_bookmarks', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_bookmarks_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-bookmarks-1', $name, 'widget_reloaded_bookmarks', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-bookmarks-1', $name, 'widget_reloaded_bookmarks_control', $control_ops, array( 'number' => -1 ) );

	endif;

}

?>