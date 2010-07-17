<?php
/**
 * Search Widget
 * Replaces the default WordPress Search widget
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the search widget
 * The option to use the theme's search form is based on get_search_form()
 * get_search_form() is only available in WP 2.7+ and can only be used once per page
 *
 * @since 0.1
 */
function widget_reloaded_search( $args, $widget_args = 1 ) {

	extract( $args, EXTR_SKIP );

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_search' );

	if ( !isset( $options[$number] ) )
		return;

	$title = apply_filters( 'widget_title', $options[$number]['title'] );
	$search_label = $options[$number]['search_label'];
	$search_text = $options[$number]['search_text'];
	$search_submit = $options[$number]['search_submit'];
	$theme_search = $options[$number]['theme_search'] ? '1' : '0';

	echo $before_widget;

	if ( $title )
		echo $before_title . $title . $after_title;

	if ( $theme_search == 1 && function_exists( 'get_search_form' ) ) :

		get_search_form();

	else :
		global $search_form_num;

		if ( !$search_form_num ) :
			$search_num = false;
		else :
			$search_num = '-' . $search_form_num;
		endif;

		if ( is_search() ) :
			$search_text = attribute_escape( get_search_query() );
		endif;

		$search = '<form method="get" class="search-form" id="search-form' . $search_num . '" action="' . get_bloginfo("home") . '/">';
		$search .= '<div>';
		if ( $search_label ) :
			$search .= '<label for="search-text' . $search_num . '">' . $search_label . '</label>';
		endif;
		$search .= '<input class="search-text" type="text" name="s" id="search-text' . $search_num . '" value="' . $search_text . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';
		if ( $search_submit ) :
			$search .= '<input class="search-submit button" name="submit" type="submit" id="search-submit' . $search_num . '" value="' . $search_submit . '" />';
		endif;
		$search .= '</div>';
		$search .= '</form>';

		echo $search;

		$search_form_num++;

	endif;

	echo $after_widget;
}

/**
 * Widget controls for the search widget
 * Options are chosen from user input from the widget panel
 *
 * @since 0.1
 */
function widget_reloaded_search_control( $widget_args ) {

	global $wp_registered_widgets;

	static $updated = false;

	if ( is_numeric( $widget_args ) )
		$widget_args = array( 'number' => $widget_args );

	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );

	extract( $widget_args, EXTR_SKIP );

	$options = get_option( 'widget_reloaded_search' );

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

			if ( 'widget_reloaded_search' == $wp_registered_widgets[$_widget_id]['callback'] && isset( $wp_registered_widgets[$_widget_id]['params'][0]['number'] ) ) :

				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];

				unset( $options[$widget_number] );

			endif;

		endforeach;

		foreach ( (array)$_POST['widget-reloaded-search'] as $widget_number => $widget_reloaded_search ) :

			$title = strip_tags( stripslashes( $widget_reloaded_search['title'] ) );

			$search_label = strip_tags( stripslashes( $widget_reloaded_search['search_label'] ) );

			$search_text = strip_tags( stripslashes( $widget_reloaded_search['search_text'] ) );

			$search_submit = strip_tags( stripslashes( $widget_reloaded_search['search_submit'] ) );

			$theme_search = strip_tags( stripslashes( $widget_reloaded_search['theme_search'] ) );

			$options[$widget_number] = compact( 'title', 'search_label', 'search_text', 'search_submit', 'theme_search' );

		endforeach;

		update_option( 'widget_reloaded_search', $options );

		$updated = true;

	endif;

	if ( $number == -1 ) :
		$title = '';
		$search_label = '';
		$search_text = '';
		$search_submit = '';
		$theme_search = '';
		$number = '%i%';
	else :
		$title = attribute_escape( $options[$number]['title'] );
		$search_label = attribute_escape( $options[$number]['search_label'] );
		$search_text = attribute_escape( $options[$number]['search_text'] );
		$search_submit = attribute_escape( $options[$number]['search_submit'] );
		$theme_search = attribute_escape( $options[$number]['theme_search'] );
	endif;

?>

	<div style="float:left;width:48%;">
	<p>
		<label for="reloaded-search-title-<?php echo $number; ?>">
			<?php _e('Title:','reloaded'); ?>
		</label>
		<input id="reloaded-search-title-<?php echo $number; ?>" name="widget-reloaded-search[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-search-search_label-<?php echo $number; ?>">
			<?php _e('Search Label:','reloaded'); ?>
		</label>
		<input id="reloaded-search-search_label-<?php echo $number; ?>" name="widget-reloaded-search[<?php echo $number; ?>][search_label]" type="text" value="<?php echo $search_label; ?>" style="width:100%;" />
	</p>
	<p>
		<label for="reloaded-search-search_text-<?php echo $number; ?>">
			<?php _e('Search Text:','reloaded'); ?>
		</label>
		<input id="reloaded-search-search_text-<?php echo $number; ?>" name="widget-reloaded-search[<?php echo $number; ?>][search_text]" type="text" value="<?php echo $search_text; ?>" style="width:100%;" />
	</p>
	</div>
	<div style="float:right;width:48%;">
	<p>
		<label for="reloaded-search-search_submit-<?php echo $number; ?>">
			<?php _e('Submit Text:','reloaded'); ?>
		</label>
		<input id="reloaded-search-search_submit-<?php echo $number; ?>" name="widget-reloaded-search[<?php echo $number; ?>][search_submit]" type="text" value="<?php echo $search_submit; ?>" style="width:100%;" />
	</p>
	<p>
		<input type="checkbox" id="reloaded-search-theme_search-<?php echo $number; ?>" name="widget-reloaded-search[<?php echo $number; ?>][theme_search]" <?php if($theme_search == 'on') echo ' checked="checked"'; ?> />
		<label for="reloaded-search-theme_search-<?php echo $number; ?>">
			<?php _e('Use theme\'s <code>searchform.php</code>?','reloaded'); ?> <em><?php _e('(WP 2.7+)', 'reloaded'); ?></em>
		</label>
	</p>
	</div>

	<p style="clear:both;">
		<input type="hidden" id="reloaded-search-submit-<?php echo $number; ?>" name="reloaded-search-submit-<?php echo $number; ?>" value="1" />
	</p>
<?php
}

/**
* Register the search widget
* Register the search widget controls
*
* @since 0.1
*/
function widget_reloaded_search_register() {

	if ( !$options = get_option( 'widget_reloaded_search' ) )
		$options = array();

	$widget_ops = array(
		'classname' => 'search',
		'description' => __('An advanced widget that gives you total control over the output of your search form.','reloaded'),
	);

	$control_ops = array(
		'width' => 700,
		'height' => 350,
		'id_base' => 'reloaded-search',
	);

	$name = __('Search','reloaded');

	$id = false;

	foreach ( array_keys( $options ) as $o ) :

		if ( !isset($options[$o]['title'] ) )
			continue;

		$id = 'reloaded-search-' . $o;

		wp_register_sidebar_widget( $id, $name, 'widget_reloaded_search', $widget_ops, array( 'number' => $o ) );

		wp_register_widget_control( $id, $name, 'widget_reloaded_search_control', $control_ops, array( 'number' => $o ) );

	endforeach;

	if ( !$id ) :

		wp_register_sidebar_widget( 'reloaded-search-1', $name, 'widget_reloaded_search', $widget_ops, array( 'number' => -1 ) );

		wp_register_widget_control( 'reloaded-search-1', $name, 'widget_reloaded_search_control', $control_ops, array( 'number' => -1 ) );

	endif;

}

?>