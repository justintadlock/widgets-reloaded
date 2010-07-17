<?php
/**
 * Search Widget
 *
 * Replaces the default WordPress Search widget.
 *
 * In 0.2, converted functions to a class that extends WP 2.8's widget class.
 *
 * @package WidgetsReloaded
 */

/**
 * Output of the search widget.
 * The option to use the theme's search form is based on get_search_form()
 * get_search_form() is only available in WP 2.7+ and can only be used once per page.
 *
 * @since 0.2
 */
class Widgets_Reloaded_Widget_Search extends WP_Widget {

	function Widgets_Reloaded_Widget_Search() {
		$widget_ops = array( 'classname' => 'search', 'description' => __('An advanced widget that gives you total control over the output of your search form.', 'widgets-reloaded') );
		$control_ops = array( 'width' => 700, 'height' => 350, 'id_base' => 'widgets-reloaded-search' );
		$this->WP_Widget( 'widgets-reloaded-search', __('Search', 'widgets-reloaded'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', $instance['title'] );
		$search_label = $instance['search_label'];
		$search_text = $instance['search_text'];
		$search_submit = $instance['search_submit'];
		$theme_search = isset( $instance['theme_search'] ) ? $instance['theme_search'] : false;

		echo $before_widget;

		if ( $title )
			echo "\n\t\t\t" . $before_title . $title . $after_title;

		if ( $theme_search ) :

			get_search_form();

		else :
			global $search_form_num;

			if ( !$search_form_num ) :
				$search_num = false;
			else :
				$search_num = '-' . $search_form_num;
			endif;

			if ( is_search() )
				$search_text = esc_attr( get_search_query() );

			$search = "\n\t\t\t" . '<form method="get" class="search-form" id="search-form' . $search_num . '" action="' . get_bloginfo("home") . '/">';
			$search .= '<div>';
			if ( $search_label )
				$search .= '<label for="search-text' . $search_num . '">' . $search_label . '</label>';
			$search .= '<input class="search-text" type="text" name="s" id="search-text' . $search_num . '" value="' . $search_text . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';
			if ( $search_submit )
				$search .= '<input class="search-submit button" name="submit" type="submit" id="search-submit' . $search_num . '" value="' . $search_submit . '" />';
			$search .= '</div>';
			$search .= '</form><!-- .search-form -->';

			echo $search;

			$search_form_num++;

		endif;

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search_label'] = strip_tags( $new_instance['search_label'] );
		$instance['search_text'] = strip_tags( $new_instance['search_text'] );
		$instance['search_submit'] = strip_tags( $new_instance['search_submit'] );
		$instance['theme_search'] = $new_instance['theme_search'];

		return $instance;
	}

	function form( $instance ) {

		//Defaults
		$defaults = array( 'title' => __('Search', 'widgets-reloaded'), 'theme_search' => false );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<div style="float:left;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_label' ); ?>"><?php _e('Search Label:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'search_label' ); ?>" name="<?php echo $this->get_field_name( 'search_label' ); ?>" value="<?php echo $instance['search_label']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'search_text' ); ?>"><?php _e('Search Text:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'search_text' ); ?>" name="<?php echo $this->get_field_name( 'search_text' ); ?>" value="<?php echo $instance['search_text']; ?>" style="width:100%;" />
		</p>
		</div>

		<div style="float:right;width:48%;">
		<p>
			<label for="<?php echo $this->get_field_id( 'search_submit' ); ?>"><?php _e('Search Submit:', 'widgets-reloaded'); ?></label>
			<input id="<?php echo $this->get_field_id( 'search_submit' ); ?>" name="<?php echo $this->get_field_name( 'search_submit' ); ?>" value="<?php echo $instance['search_submit']; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'theme_search' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['theme_search'], true ); ?> id="<?php echo $this->get_field_id( 'theme_search' ); ?>" name="<?php echo $this->get_field_name( 'theme_search' ); ?>" /> <?php _e('Use theme\'s <code>searchform.php</code>?', 'widgets-reloaded'); ?></label>
		</p>
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
}

?>