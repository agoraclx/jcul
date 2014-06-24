<?php

// thb Popular Posts by Likes
class widget_topnewsbycategory extends WP_Widget
{

  function widget_topnewsbycategory()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('Display popular posts by category', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'topnewsbycategory');

    /* Create the widget. */
    $this->WP_Widget('topnewsbycategory', __('Exquisite - Popular Posts by Category', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $title       = apply_filters('widget_title', $instance['title']);
    $paginate    = $instance['paginate'];
    $args        = array(
      'type'         => 'post',
      'child_of'     => 0,
      'parent'       => 0,
      'orderby'      => 'name',
      'order'        => 'ASC',
      'hide_empty'   => 1,
      'hierarchical' => 1,
      'exclude'      => '',
      'include'      => '',
      'number'       => '',
      'taxonomy'     => 'category',
      'pad_counts'   => false
    );
    $_categories = get_categories($args);
    $_count      = count($_categories);
    $counter     = range(0, 100, $paginate);
    $i           = 0;
    echo $before_widget;
    echo $before_title;
    echo $title;
    echo $after_title;

    echo '<ul class="owl" data-columns="1" data-autoplay="false" data-pagination="true">';

    foreach($_categories as $category)
    {
      if($i % $paginate == 0)
      {
        echo '<li>';
      }
      $pop = new WP_Query(array('post_type' => 'post', 'order' => 'DESC', 'orderby' => 'meta_value_num', 'meta_key' => '_likes', 'posts_per_page' => '1', 'cat' => $category->term_id));
      while($pop->have_posts()) : $pop->the_post();
        ?>

        <article class="post">
          <div class="post-title">
            <aside><?php echo thb_DisplaySingleCategory(false); ?></aside>
            <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></h4>
          </div>
          <div class="post-content">
            <p><?php echo ShortenText(get_the_excerpt(), 70); ?></p>
            <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
          </div>
        </article>
        <?php
      endwhile;

      if(!in_array($i, $counter) && $i % $paginate == 0)
      {
        echo '</li>';
      }

      $i++;
    }

    echo '</ul>';
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    /* Strip tags (if needed) and update the widget settings. */
    $instance['title']    = strip_tags($new_instance['title']);
    $instance['paginate'] = strip_tags($new_instance['paginate']);
    return $instance;
  }

  function form($instance)
  {
    $defaults = array('title' => 'Top News', 'paginate' => '4');
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label>
      <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('paginate'); ?>">Paginate every nth category:</label>
      <input id="<?php echo $this->get_field_id('paginate'); ?>" name="<?php echo $this->get_field_name('paginate'); ?>" value="<?php echo $instance['paginate']; ?>" style="width:100%;" />
    </p>
    <?php
  }

}

function widget_topnewsbycategory_init()
{
  register_widget('widget_topnewsbycategory');
}

add_action('widgets_init', 'widget_topnewsbycategory_init');
