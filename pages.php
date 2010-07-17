<?php
/**
 * Pages Widget
 * Replaces the default WordPress Pages widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the Pages widget
 * Arguments are parameters of the wp_list_pages() function
 * @link http://codex.wordpress.org/Template_Tags/wp_list_pages
 *
 * @since 0.1
 */
function widget_reloaded_pages( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_pages' );

	if ( !isset( $options[$number] ) )
		return;

	$title = apply_filters( 'widget_title', $options[$number]['title'] );
	$sort_column = $options[$number]['sort_column'];
	$sort_order = $options[$number]['sort_order'];
	$exclude = $options[$number]['exclude'];
	$include = $options[$number]['include'];
	$depth = (int)$options[$number]['depth'];
	$child_of = $options[$number]['child_of'];
	$meta_key = $options[$number]['meta_key'];
	$meta_value = $options[$number]['meta_value'];
	$authors = $options[$number]['authors'];

	$exclude_tree = $options[$number]['exclude_tree']; // WP 2.7
	$link_before = $options[$number]['link_before'];
	$link_after = $options[$number]['link_after'];

	$show_home = $options[$number]['show_home'] ? '1' : '0';
	$show_date = $options[$number]['show_date'] ? '1' : '0';
	$hierarchical = $options[$number]['hierarchical'] ? '1' : '0';

	$pages = array(
		'depth' => $depth,
		'sort_column' => $sort_column,
		'sort_order' => $sort_order,
		'show_date' => $show_date,
		'date_format' => get_option( 'date_format' ),
		'child_of' => $child_of,
		'exclude' => $exclude,
		'include' => $include,
		'hierarchical' => $hierarchical,
		'meta_key' => $meta_key,
		'meta_value' => $meta_value,
		'authors' => $authors,
		'title_li' => '',
		'echo' => 0,
		'exclude_tree' => $exclude_tree,
		'link_before' => $link_before,
		'link_after' => $link_after,
	);

	echo $before_widget;
	if ( $title )
		echo $before_title . $title . $after_title;
	echo '<ul class="xoxo pages">';

	if ( $show_home ) :
		echo '<li class="page_item';
		if ( is_home() ) echo ' current_page_item';
		echo '"><a href="' . get_bloginfo( 'url' ) . '" title="' . get_bloginfo( 'name' ) . '" rel="home"><span>' . __('Home','reloaded') . '</span></a></li>';
	endif;

	echo str_replace( array( "\r", "\n", "\t" ), '', wp_list_pages( $pages ) );

	echo '</ul>' . $after_widget;
}

/**
 * Widget controls for the pages widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1
 */
function widget_reloaded_pages_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_pages' );

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

			if ( 'widget_reloaded_pages' == $wp_registered_widgets[$_widget_id]['callback'] && isset( $wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-pages'] as $widget_number => $widget_reloaded_pages ) :

			$depth = strip_tags( stripslashes( $widget_reloaded_pages['depth'] ) );

			$sort_column = strip_tags( stripslashes( $widget_reloaded_pages['sort_column'] ) );

			$sort_order = strip_tags( stripslashes( $widget_reloaded_pages['sort_order'] ) );

			$show_date = strip_tags( stripslashes( $widget_reloaded_pages['show_date'] ) );

			$child_of = strip_tags( stripslashes( $widget_reloaded_pages['child_of'] ) );

			$exclude = strip_tags( stripslashes( $widget_reloaded_pages['exclude'] ) );

			$include = strip_tags( stripslashes( $widget_reloaded_pages['include'] ) );

			$meta_key = strip_tags( stripslashes( $widget_reloaded_pages['meta_key'] ) );

			$meta_value = strip_tags( stripslashes( $widget_reloaded_pages['meta_value'] ) );

			$hierarchical = strip_tags( stripslashes( $widget_reloaded_pages['hierarchical'] ) );

			$title = strip_tags( stripslashes( $widget_reloaded_pages['title'] ) );

			$authors = strip_tags( stripslashes( $widget_reloaded_pages['authors'] ) );

			$show_home = strip_tags( stripslashes( $widget_reloaded_pages['show_home'] ) );

			$exclude_tree = strip_tags( stripslashes( $widget_reloaded_pages['exclude_tree'] ) );

			$link_before = stripslashes( $widget_reloaded_bookmarks['link_before'] );

			$linke_after = stripslashes( $widget_reloaded_bookmarks['link_after'] );

			$options[$widget_number] = compact( 'title', 'show_home', 'depth', 'sort_column', 'sort_order', 'show_date', 'child_of', 'exclude', 'include', 'meta_key', 'meta_value', 'hierarchical', 'authors', 'link_before', 'link_after', 'exclude_tree' );

		endforeach;

		update_option( 'widget_reloaded_pages', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$title = '';
		$depth = '';
		$sort_column = '';
		$sort_order = '';
		$show_date = '';
		$child_of = '';
		$exclude = '';
		$include = '';
		$meta_key = '';
		$meta_value = '';
		$hierarchical = '';
		$show_home = '';
		$authors = '';
		$link_before = '';
		$link_after = '';
		$exclude_tree = '';
		$number = '%i%';
	else :
		$title = attribute_escape( $options[$number]['title'] );
		$depth = attribute_escape( $options[$number]['depth'] );
		$sort_column = attribute_escape( $options[$number]['sort_column'] );
		$sort_order = attribute_escape( $options[$number]['sort_order'] );
		$show_date = attribute_escape( $options[$number]['show_date'] );
		$child_of = attribute_escape( $options[$number]['child_of'] );
		$exclude = attribute_escape( $options[$number]['exclude'] );
		$include = attribute_escape( $options[$number]['include'] );
		$meta_key = attribute_escape( $options[$number]['meta_key'] );
		$meta_value = attribute_escape( $options[$number]['meta_value'] );
		$authors = attribute_escape( $options[$number]['authors'] );
		$hierarchical = attribute_escape( $options[$number]['hierarchical'] );
		$show_home = attribute_escape( $options[$number]['show_home'] );
		$link_before = attribute_escape( $options[$number]['link_before'] );
		$link_after = attribute_escape( $options[$number]['link_after'] );
		$exclude_tree = attribute_escape( $options[$number]['exclude_tree'] );
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-pages-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-pages-title-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-sort_column-<?php echo $number; ?>">
			<?php _e('Order By:','reloaded'); ?> <code>sort_column</code>
		</label>

		<select id="reloaded-pages-sort_column-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][sort_column]" class="widefat" style="width:100%;">
			<option <?php if ( 'post_title' == $sort_column ) echo 'selected="selected"'; ?>>post_title</option>
			<option <?php if ( 'menu_order' == $sort_column ) echo 'selected="selected"'; ?>>menu_order</option>
			<option <?php if ( 'post_date' == $sort_column ) echo 'selected="selected"'; ?>>post_date</option>
			<option <?php if ( 'post_modified' == $sort_column ) echo 'selected="selected"'; ?>>post_modified</option>
			<option <?php if ( 'ID' == $sort_column ) echo 'selected="selected"'; ?>>ID</option>
			<option <?php if ( 'post_author' == $sort_column ) echo 'selected="selected"'; ?>>post_author</option>
			<option <?php if ( 'post_name' == $sort_column ) echo 'selected="selected"'; ?>>post_name</option>
		</select>
	</p>

	<p>
		<label for="reloaded-pages-sort_order-<?php echo $number; ?>">
			<?php _e('Sort Order:','reloaded'); ?> <code>sort_order</code>
		</label>

		<select id="reloaded-pages-sort_order-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][sort_order]" class="widefat" style="width:100%;">
			<option <?php if ( 'ASC' == $sort_order ) echo 'selected="selected"'; ?>>ASC</option>
			<option <?php if ( 'DESC' == $sort_order ) echo 'selected="selected"'; ?>>DESC</option>
		</select>
	</p>
	<p>
		<label for="reloaded-pages-depth-<?php echo $number; ?>">
			<?php _e('Depth:','reloaded'); ?> <code>depth</code>
		</label>
		<input id="reloaded-pages-depth-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][depth]" type="text" value="<?php echo $depth; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-exclude-<?php echo $number; ?>">
			<?php _e('Exclude:','reloaded'); ?> <code>exclude</code>
		</label>
		<input id="reloaded-pages-exclude-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][exclude]" type="text" value="<?php echo $exclude; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-exclude_tree-<?php echo $number; ?>">
			<?php _e('Exclude Tree:','reloaded'); ?> <em><?php _e('(WP 2.7+)', 'reloaded'); ?></em> <code>exclude_tree</code>
		</label>
		<input id="reloaded-pages-exclude_tree-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][exclude_tree]" type="text" value="<?php echo $exclude_tree; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-include-<?php echo $number; ?>">
			<?php _e('Include:','reloaded'); ?> <code>include</code>
		</label>
		<input id="reloaded-pages-include-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][include]" type="text" value="<?php echo $include; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-child_of-<?php echo $number; ?>">
			<?php _e('Child Of:','reloaded'); ?> <code>child_of</code>
		</label>
		<input id="reloaded-pages-child_of-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][child_of]" type="text" value="<?php echo $child_of; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<label for="reloaded-pages-authors-<?php echo $number; ?>">
			<?php _e('Authors:','reloaded'); ?> <code>authors</code>
		</label>
		<input id="reloaded-pages-authors-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][authors]" type="text" value="<?php echo $authors; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-meta_key-<?php echo $number; ?>">
			<?php _e('Meta Key:','reloaded'); ?> <code>meta_key</code>
		</label>
		<input id="reloaded-pages-meta_key-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][meta_key]" type="text" value="<?php echo $meta_key; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-meta_value-<?php echo $number; ?>">
			<?php _e('Meta Value:','reloaded'); ?> <code>meta_value</code>
		</label>
		<input id="reloaded-pages-meta_value-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][meta_value]" type="text" value="<?php echo $meta_value; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-link_before-<?php echo $number; ?>">
			<?php _e('Before Link:','reloaded'); ?> <em><?php _e('(WP 2.7+)', 'reloaded'); ?></em> <code>link_before</code>
		</label>
		<input id="reloaded-pages-link_before-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][link_before]" type="text" value="<?php echo $link_before; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-pages-link_after-<?php echo $number; ?>">
			<?php _e('After Link:','reloaded'); ?> <em><?php _e('(WP 2.7+)', 'reloaded'); ?></em> <code>link_after</code>
		</label>
		<input id="reloaded-pages-link_after-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][link_after]" type="text" value="<?php echo $link_after; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-pages-hierarchical-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][hierarchical]" <?php if($hierarchical == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-pages-hierarchical-<?php echo $number; ?>">
			<?php _e('Hierarchical?','reloaded'); ?> <code>hierarchichal</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-pages-show_home-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][show_home]" <?php if($show_home == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-pages-show_home-<?php echo $number; ?>">
			<?php _e('Show home?','reloaded'); ?> <code>show_home</code>
		</label>
	</p>
	<p>
		<input type="checkbox" id="reloaded-pages-show_date-<?php echo $number; ?>" name="widget-reloaded-pages[<?php echo $number; ?>][show_date]" <?php if($show_date == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-pages-show_date-<?php echo $number; ?>">
			<?php _e('Show date?','reloaded'); ?> <code>show_date</code>
		</label>
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-pages-submit-<?php echo $number; ?>" name="reloaded-pages-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
 * Register the pages widget
 * Register the pages widget controls
 *
 * @since 0.1
 */
function widget_reloaded_pages_register() {

	if ( !$options = get_option( 'widget_reloaded_pages' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'pages',
		'description' => __('An advanced widget that gives you total control over the output of your page links.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-pages',
	);

	$name = __('Pages','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset( $options[$o]['sort_column'] ) )
			continue;

		$id = 'reloaded-pages-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_pages', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_pages_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-pages-1', $name, 'widget_reloaded_pages', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-pages-1', $name, 'widget_reloaded_pages_control', $control_ops, array( 'number' => -1 ) );

	endif;

}

?>