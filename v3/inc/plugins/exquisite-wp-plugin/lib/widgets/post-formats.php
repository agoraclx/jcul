<?php
// thb Post Format Widget
class thb_Post_Formats extends WP_Widget {

	function thb_Post_Formats() {
		/* Widget settings. */
		$widget_ops = array( 'description' => __('A list or dropdown of Post Formats',THB_THEME_NAME) );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'post_formats' );
	
		/* Create the widget. */
		$this->WP_Widget( 'post_formats', __('Exquisite - Post Formats',THB_THEME_NAME), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? 'Post Formats' : $instance['title'], $instance, $this->id_base);
		$c = $instance['count'] ? '1' : '0';
		$d = $instance['dropdown'] ? '1' : '0';
		$f = $instance['format_id'] ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $d ) { ?>

		<select id="post-format-dropdown" class="postform" name="post-format-dropdown">
		<option value="null">Select Post Format</option>
<?php			
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					echo '<option class="level-0" value="' . $slug . '">' . $string . $count . $format_id . '</option>';
				}
			}
		} ?>
		</select>

<script type='text/javascript'>
/* <![CDATA[ */
	var pfDropdown = document.getElementById("post-format-dropdown");
	function onFormatChange() {
		if ( pfDropdown.options[pfDropdown.selectedIndex].value != 'null' ) {
			location.href = "<?php echo home_url(); ?>/?post_format="+pfDropdown.options[pfDropdown.selectedIndex].value;
		}
	}
	pfDropdown.onchange = onFormatChange;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$tooltip = empty( $instance['tooltip'] ) ? 'View all %format posts' : esc_attr($instance['tooltip']);
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					echo '<li class="post-format-item"><a title="' . str_replace('%format', $string, $tooltip) . '" href="' . get_post_format_link($slug) . '">' . $string . ' ' . $count . '</a></li>';
				}
			}
		}
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tooltip'] = strip_tags($new_instance['tooltip']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['format_id'] = !empty($new_instance['format_id']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = !empty($instance['title']) ? esc_attr( $instance['title'] ) : 'Post Formats';
		$tooltip = !empty($instance['tooltip']) ? esc_attr( $instance['tooltip'] ) : 'View all %format posts';
		$count = isset($instance['count']) ? (bool) $instance['count'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$format_id = isset( $instance['format_id'] ) ? (bool) $instance['format_id'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('tooltip'); ?>">Tooltip:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('tooltip'); ?>" name="<?php echo $this->get_field_name('tooltip'); ?>" type="text" value="<?php echo $tooltip; ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>">Display as dropdown</label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>">Show post counts</label></p>
<?php
	}

}

function thb_Post_Formats_init() {
	register_widget('thb_Post_Formats');
}

add_action('widgets_init', 'thb_Post_Formats_init');

?>