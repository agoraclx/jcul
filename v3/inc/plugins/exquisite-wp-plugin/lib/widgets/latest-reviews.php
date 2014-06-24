<?php
// thb latest Posts w/ Images
class widget_latestreviews extends WP_Widget {
       function widget_latestreviews() {
               /* Widget settings. */
               $widget_ops = array( 'description' => __('Display Latest Reviews',THB_THEME_NAME) );

               /* Widget control settings. */
               $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'latestreviews' );

               /* Create the widget. */
               $this->WP_Widget( 'latestreviews', __('Exquisite - Latest Reviews',THB_THEME_NAME), $widget_ops, $control_ops );
       }

       function widget($args, $instance) {
               extract($args);
               $title = apply_filters('widget_title', $instance['title']);
               $show = $instance['show'];
               
               $args = array(
             			'posts_per_page' => $show,
             			'meta_query' => array(
             				array(
             					'key' => 'is_review',
             					'value' => 'yes'
             				)
             			)
             	 );
               $pop = new WP_Query($args);
               
               echo $before_widget;
               echo $before_title;
               echo $title;
               echo $after_title;
               echo '<ul>';
               while  ($pop->have_posts()) : $pop->the_post(); $id = get_the_ID();?>

	           <li style="background: <?php echo thb_GetAverageRatingColor($id); ?>;">
	           		<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php echo ShortenText(get_the_title(), 60); ?></a>
	           		<?php echo thb_GetAverageRating($id); ?>
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
               $defaults = array( 'title' => 'Latest Reviews', 'show' => '5' );
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
function widget_latestreviews_init()
{
       register_widget('widget_latestreviews');
}
add_action('widgets_init', 'widget_latestreviews_init');

?>