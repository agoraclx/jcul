<?php
// thb latest Category with Single Image
class widget_latestcategory extends WP_Widget {
       function widget_latestcategory() {
               /* Widget settings. */
               $widget_ops = array( 'description' => __('Latest Category Posts with Single Image on top',THB_THEME_NAME) );

               /* Widget control settings. */
               $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'latestcategory' );

               /* Create the widget. */
               $this->WP_Widget( 'latestcategory', __('Exquisite - Latest Category Posts with Single Image on top',THB_THEME_NAME), $widget_ops, $control_ops );
       }

       function widget($args, $instance) {
               extract($args);
               $show = $instance['show'];
               $category = $instance['category'];
               $args = array(
                 	   'posts_per_page' => $show,
                 	   'category__in' => $category,
                 	   'no_found_rows' => true
               	  	);
               $pop = new WP_Query($args);
							 $cat = get_category($category);
							 $color = GetCategoryColor($category);
							 $isfirst = false;
               echo $before_widget; ?>
               
               <h6 style="border-color: <?php echo $color; ?>"><?php echo $cat->name; ?></h6>
               <?php while  ($pop->have_posts()) : $pop->the_post(); ?>
	               <div class="post">
	               	<figure>
		               	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
		               		<?php the_post_thumbnail('widget'); ?>
		               	</a>
	               	</figure>
	               	<h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></h4>
	               </div>
	               <?php 
	               	echo '<ul class="iconlist">';
	               		while ($pop->have_posts()) : $pop->the_post(); ?>
	               			<li><i class="fa fa-long-arrow-right"></i> <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></li>
	               		<?php endwhile;
	               		echo '</ul>';
	               		
	                break; ?>
               <?php endwhile;

               echo $after_widget;
       }
       function update( $new_instance, $old_instance ) {
               $instance = $old_instance;

               /* Strip tags (if needed) and update the widget settings. */
               $instance['show'] = strip_tags( $new_instance['show'] );
							 $instance['category'] = strip_tags( $new_instance['category'] );
							 
               return $instance;
       }
       function form($instance) {
               $defaults = array( 'show' => '3', 'category' => '' );
               $instance = wp_parse_args( (array) $instance, $defaults ); 
               $categories = get_categories(); ?>
								
							 <p>
							         <label for="<?php echo $this->get_field_id( 'category' ); ?>">Category:</label>
							         <select id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" style="width:100%;">
							         	<?php foreach( $categories as $category) { ?>
							         	<option value="<?php echo $category->cat_ID; ?>" <?php if ($category->cat_ID == $instance['category']) { echo 'selected="selected"';} ?>><?php echo $category->cat_name; ?></option>
							         	<?php } ?>
							         </select>
							 </p>
							 
               <p>
                       <label for="<?php echo $this->get_field_id( 'name' ); ?>">Number of Posts:</label>
                       <input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
               </p>
   <?php
       }
}
function widget_latestcategory_init()
{
       register_widget('widget_latestcategory');
}
add_action('widgets_init', 'widget_latestcategory_init');

?>