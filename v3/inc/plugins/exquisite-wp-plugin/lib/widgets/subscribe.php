<?php

// thb Subscribe Widget
class thb_subscribe_widget extends WP_Widget
{

  function thb_subscribe_widget()
  {

    /* Widget settings. */
    $widget_ops = array('description' => __('A widget that gathers email addresses.', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'subscribe_widget');

    /* Create the widget. */
    $this->WP_Widget('subscribe_widget', __('Exquisite - Subscribe Widget', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);

    /* Our variables from the widget settings. */
    $title = apply_filters('widget_title', $instance['title']);
    $desc  = $instance['desc'];

    echo $before_widget;
    ?>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $("#newsletter").submit(function() {
          $('.result').load('<?php echo get_template_directory_uri(); ?>/inc/subscribe_save.php', {email: $('#widget_subscribe').val()},
          function() {
            $(this).fadeIn(200).delay(3000).fadeOut(200);
          });
          return false;
        });
      });
    </script>


    <?php
    echo $before_title.$title.$after_title;
    ?>

    <p><?php echo $desc; ?></p>

    <form id="newsletter" action="#" method="post">
      <p><input placeholder="<?php _e("Enter your email address", THB_THEME_NAME); ?>" type="text" class="span4" name="widget_subscribe" id="widget_subscribe" x-webkit-speech></p>
      <p><button type="submit" name="submit" class="btn blue"><?php _e("Subscribe", THB_THEME_NAME); ?></button></p>
    </form>
    <div class="result" style="font-size: 12px;"></div>

    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    $instance['title'] = strip_tags($new_instance['title']);

    $instance['desc'] = stripslashes($new_instance['desc']);

    return $instance;
  }

  function form($instance)
  {

    /* Set up some default widget settings. */
    $defaults = array(
      'title' => '',
      'desc'  => '',
    );
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('desc'); ?>">Short Description: </label>
      <input type="text" class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" value="<?php echo stripslashes(htmlspecialchars(( $instance['desc']), ENT_QUOTES)); ?>" />
    </p>

    <?php
  }

}
add_action('widgets_init', 'thb_subscribe_widgets');

function thb_subscribe_widgets()
{
  register_widget('thb_subscribe_widget');
}
