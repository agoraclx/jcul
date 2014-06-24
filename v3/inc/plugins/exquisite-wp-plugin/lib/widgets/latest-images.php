<?php
// thb latest Posts w/ Images
class widget_latestimages extends WP_Widget {
       function widget_latestimages() {
               /* Widget settings. */
               $widget_ops = array( 'description' => __('Display latest posts with images',THB_THEME_NAME) );

               /* Widget control settings. */
               $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'latestimages' );

               /* Create the widget. */
               $this->WP_Widget( 'latestimages', __('Exquisite - Latest Posts with Images',THB_THEME_NAME), $widget_ops, $control_ops );
       }

       function widget($args, $instance) {
               extract($args);
               $title = apply_filters('widget_title', $instance['title']);
               $show = $instance['show'];
               global $post, $wpdb;
               $themePath = get_template_directory_uri();
               $pop = new WP_Query();
               $pop->query('showposts='.$show.'&no_found_rows=true');

               echo $before_widget;
               echo $before_title;
               echo $title;
               echo $after_title;
               echo '<ul>';
               while  ($pop->have_posts()) : $pop->the_post(); ?>
	           <li class="post">
	           	   <div class="post-gallery">
		               <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
		               <?php the_post_thumbnail('widget'); ?>
		               </a>
	               </div>
	               <div class="post-title"><h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></h4></div>
	               <?php echo thb_DisplayPostMeta(true,true,false,false); ?>
	           </li>
	           <?php endwhile;
               echo '</ul>';
               echo $after_widget;
       }
       function update( $new_instance, $old_instance ) {
               $instance = $old_instance;

               /* Strip tags (if needed) and update the widget settings. */
               $instance['title'] = strip_tags( $new_instance['title'] );
               $instance['show'] = strip_tags( $new_instance['show'] );

               return $instance;
       }
       function form($instance) {
               $defaults = array( 'title' => 'Latest Posts', 'show' => '3' );
               $instance = wp_parse_args( (array) $instance, $defaults ); ?>

               <p>
                       <label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
                       <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
               </p>

               <p>
                       <label for="<?php echo $this->get_field_id( 'name' ); ?>">Number of Posts:</label>
                       <input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
               </p>
   <?php
       }
}
function widget_latestimages_init()
{
       register_widget('widget_latestimages');
}
add_action('widgets_init', 'widget_latestimages_init');

?>