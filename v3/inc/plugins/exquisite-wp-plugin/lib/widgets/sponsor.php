<?php

// thb Sponsors
class widget_sponsor extends WP_Widget
{

  function widget_sponsor()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('Single Sponsor banner with link', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'sponsor');

    /* Create the widget. */
    $this->WP_Widget('sponsor', __('Exquisite - Single Sponsor Banner', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $title        = $instance['title'];
    $sponsorimage = $instance['sponsorimage'];
    $sponsorurl   = $instance['sponsorurl'];

    echo $before_widget;
    if($title)
    {
      echo $before_title;
      echo $title;
      echo $after_title;
    }
    echo '<a href="'.$sponsorurl.'" target="_blank">';
    if(empty($sponsorimage))
    {
      echo '<div class="placeholder">'.__('Advertise', THB_THEME_NAME).'</div>';
    }
    else
    {
      echo '<img src="'.$sponsorimage.'" />';
    }
    echo '</a>';
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    /* Strip tags (if needed) and update the widget settings. */
    $instance['title']        = strip_tags($new_instance['title']);
    $instance['sponsorimage'] = strip_tags($new_instance['sponsorimage']);
    $instance['sponsorurl']   = strip_tags($new_instance['sponsorurl']);

    return $instance;
  }

  function form($instance)
  {
    $defaults   = array(
      'title'        => '',
      'sponsorimage' => '',
      'sponsorurl'   => ''
    );
    $instance   = wp_parse_args((array) $instance, $defaults);
    $categories = get_categories();
    ?>

    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsorimage'); ?>">Sponsor Image:</label>
      <input id="<?php echo $this->get_field_id('sponsorimage'); ?>" name="<?php echo $this->get_field_name('sponsorimage'); ?>" value="<?php echo $instance['sponsorimage']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('sponsorurl'); ?>">Sponsor URL:</label>
      <input id="<?php echo $this->get_field_id('sponsorurl'); ?>" name="<?php echo $this->get_field_name('sponsorurl'); ?>" value="<?php echo $instance['sponsorurl']; ?>" style="width:100%;" />
    </p>
    <?php
  }

}

function widget_sponsor_init()
{
  register_widget('widget_sponsor');
}

add_action('widgets_init', 'widget_sponsor_init');
?>