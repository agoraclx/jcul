<?php

// thb Sponsors
class widget_sponsors extends WP_Widget
{

  function widget_sponsors()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('4 sponsor banners (160*160) with links', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'sponsors');

    /* Create the widget. */
    $this->WP_Widget('sponsors', __('Exquisite - Sponsor Banners', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $title         = $instance['title'];
    $sponsor1image = $instance['sponsor1image'];
    $sponsor1url   = $instance['sponsor1url'];
    $sponsor2image = $instance['sponsor2image'];
    $sponsor2url   = $instance['sponsor2url'];
    $sponsor3image = $instance['sponsor3image'];
    $sponsor3url   = $instance['sponsor3url'];
    $sponsor4image = $instance['sponsor4image'];
    $sponsor4url   = $instance['sponsor4url'];

    echo $before_widget;
    echo $before_title;
    echo $title;
    echo $after_title;
    echo '<div class="sponsors">';
    echo '<a href="'.$sponsor1url.'" target="_blank">';
    if(empty($sponsor1image))
    {
//      echo '<div class="placeholder">'.__('Advertise', THB_THEME_NAME).'</div>';
    }
    else
    {
      echo '<img src="'.$sponsor1image.'" />';
    }
    echo '</a>';

    echo '<a href="'.$sponsor2url.'" target="_blank">';
    if(empty($sponsor2image))
    {
//      echo '<div class="placeholder">'.__('Advertise', THB_THEME_NAME).'</div>';
    }
    else
    {
      echo '<img src="'.$sponsor2image.'" />';
    }
    echo '</a>';

    echo '<a href="'.$sponsor3url.'" target="_blank">';
    if(empty($sponsor3image))
    {
//      echo '<div class="placeholder">'.__('Advertise', THB_THEME_NAME).'</div>';
    }
    else
    {
      echo '<img src="'.$sponsor3image.'" />';
    }
    echo '</a>';

    echo '<a href="'.$sponsor4url.'" target="_blank">';
    if(empty($sponsor4image))
    {
//      echo '<div class="placeholder">'.__('Advertise', THB_THEME_NAME).'</div>';
    }
    else
    {
      echo '<img src="'.$sponsor4image.'" />';
    }
    echo '</a>';
    echo '</div>';
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    /* Strip tags (if needed) and update the widget settings. */
    $instance['title']         = strip_tags($new_instance['title']);
    $instance['sponsor1image'] = strip_tags($new_instance['sponsor1image']);
    $instance['sponsor1url']   = strip_tags($new_instance['sponsor1url']);
    $instance['sponsor2image'] = strip_tags($new_instance['sponsor2image']);
    $instance['sponsor2url']   = strip_tags($new_instance['sponsor2url']);
    $instance['sponsor3image'] = strip_tags($new_instance['sponsor3image']);
    $instance['sponsor3url']   = strip_tags($new_instance['sponsor3url']);
    $instance['sponsor4image'] = strip_tags($new_instance['sponsor4image']);
    $instance['sponsor4url']   = strip_tags($new_instance['sponsor4url']);

    return $instance;
  }

  function form($instance)
  {
    $defaults   = array(
      'title'         => '',
      'sponsor1image' => '',
      'sponsor1url'   => '',
      'sponsor2image' => '',
      'sponsor2url'   => '',
      'sponsor3image' => '',
      'sponsor3url'   => '',
      'sponsor4image' => '',
      'sponsor4url'   => '',
    );
    $instance   = wp_parse_args((array) $instance, $defaults);
    $categories = get_categories();
    ?>

    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor1image'); ?>">Sponsor 1 Image:</label>
      <input id="<?php echo $this->get_field_id('sponsor1image'); ?>" name="<?php echo $this->get_field_name('sponsor1image'); ?>" value="<?php echo $instance['sponsor1image']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor1url'); ?>">Sponsor 1 URL:</label>
      <input id="<?php echo $this->get_field_id('sponsor1url'); ?>" name="<?php echo $this->get_field_name('sponsor1url'); ?>" value="<?php echo $instance['sponsor1url']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor2image'); ?>">Sponsor 2 Image:</label>
      <input id="<?php echo $this->get_field_id('sponsor2image'); ?>" name="<?php echo $this->get_field_name('sponsor2image'); ?>" value="<?php echo $instance['sponsor2image']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor2url'); ?>">Sponsor 2 URL:</label>
      <input id="<?php echo $this->get_field_id('sponsor2url'); ?>" name="<?php echo $this->get_field_name('sponsor2url'); ?>" value="<?php echo $instance['sponsor2url']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor3image'); ?>">Sponsor 3 Image:</label>
      <input id="<?php echo $this->get_field_id('sponsor3image'); ?>" name="<?php echo $this->get_field_name('sponsor3image'); ?>" value="<?php echo $instance['sponsor3image']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor3url'); ?>">Sponsor 3 URL:</label>
      <input id="<?php echo $this->get_field_id('sponsor3url'); ?>" name="<?php echo $this->get_field_name('sponsor3url'); ?>" value="<?php echo $instance['sponsor3url']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor4image'); ?>">Sponsor 4 Image:</label>
      <input id="<?php echo $this->get_field_id('sponsor4image'); ?>" name="<?php echo $this->get_field_name('sponsor4image'); ?>" value="<?php echo $instance['sponsor4image']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsor4url'); ?>">Sponsor 4 URL:</label>
      <input id="<?php echo $this->get_field_id('sponsor4url'); ?>" name="<?php echo $this->get_field_name('sponsor4url'); ?>" value="<?php echo $instance['sponsor4url']; ?>" style="width:100%;" />
    </p>
    <?php
  }

}

function widget_sponsors_init()
{
  register_widget('widget_sponsors');
}

add_action('widgets_init', 'widget_sponsors_init');