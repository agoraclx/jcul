<?php

// thb latest Posts w/ Images
class widget_fblikebox extends WP_Widget
{

  function widget_fblikebox()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('Display Facebook like box for your page', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'fblikebox');

    /* Create the widget. */
    $this->WP_Widget('fblikebox', __('Exquisite - Facebook - Like Box', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $title  = apply_filters('widget_title', $instance['title']);
    $width  = $instance['width'];
    $height = $instance['height'];
    $page   = $instance['page'];

    echo $before_widget;
    echo $before_title;
    echo $title;
    echo $after_title;
    ?>
    <div class="like_box">
      <iframe src="//www.facebook.com/plugins/likebox.php?href=<?php echo $page; ?>&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=<?php echo $height; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: 100% !important; height:<?php echo $height; ?>px;" allowTransparency="false"></iframe>
    </div><!--like_box_footer-->

    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    /* Strip tags (if needed) and update the widget settings. */
    $instance['title']  = strip_tags($new_instance['title']);
    $instance['width']  = $new_instance['width'];
    $instance['height'] = $new_instance['height'];
    $instance['page']   = $new_instance['page'];

    return $instance;
  }

  function form($instance)
  {
    $defaults = array(
      'title'   => 'Facebook',
      'page'    => 'http://www.facebook.com/fuelthemes',
      'width'   => 283,
      'height'  => 258,
      'borderc' => 'acb7d1',
    );
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'theme') ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'theme') ?></label>
      <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>"  class="widefat" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'theme') ?></label>
      <input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>"  class="widefat" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('page'); ?>"><?php _e('Facebook Page URL:', 'theme') ?></label>
      <input id="<?php echo $this->get_field_id('page'); ?>" name="<?php echo $this->get_field_name('page'); ?>" value="<?php echo $instance['page']; ?>"  class="widefat" />
    </p>
    <?php
  }

}

function widget_fblikebox_init()
{
  register_widget('widget_fblikebox');
}

add_action('widgets_init', 'widget_fblikebox_init');
