<?php

// thb Flickr Widget
class widget_thbflickr extends WP_Widget
{

  function widget_thbflickr()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('Display Your Flickr Images', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'flickr');

    /* Create the widget. */
    $this->WP_Widget('flickr', __('Exquisite - Flickr', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $title   = apply_filters('widget_title', $instance['title']);
    $account = $instance['account'];
    $show    = $instance['show'];

    // Output
    echo $before_widget;
    echo $before_title.$title.$after_title;

    if($account && $show)
    {
      $api_key = '0388a3691d9cbddb7969e1297b8424a5';

      @$person = wp_remote_get('http://api.flickr.com/services/rest/?method=flickr.people.findByUsername&api_key='.$api_key.'&username='.urlencode($account).'&format=json');
      @$person = trim($person['body'], 'jsonFlickrApi()');
      @$person = json_decode($person);

      if($person->user->id)
      {
        $photos_url = wp_remote_get('http://api.flickr.com/services/rest/?method=flickr.urls.getUserPhotos&api_key='.$api_key.'&user_id='.$person->user->id.'&format=json');
        $photos_url = trim($photos_url['body'], 'jsonFlickrApi()');
        $photos_url = json_decode($photos_url);

        $photos = wp_remote_get('http://api.flickr.com/services/rest/?method=flickr.people.getPublicPhotos&api_key='.$api_key.'&user_id='.$person->user->id.'&per_page='.$show.'&format=json');
        $photos = trim($photos['body'], 'jsonFlickrApi()');
        $photos = json_decode($photos);
        ?>
        <div class="flickrcontainer">
          <?php foreach($photos->photos->photo as $photo): $photo = (array) $photo; ?>
            <div class="fresco">
              <img src='<?php $url   = "http://farm".$photo['farm'].".static.flickr.com/".$photo['server']."/".$photo['id']."_".$photo['secret'].'_s'.".jpg";
          echo $url; ?>' alt='<?php echo $photo['title']; ?>' width="41" height="41" />
              <div class="overlay">
                <a href='<?php echo $photos_url->user->url; ?><?php echo $photo['id']; ?>' class="static" target='_blank'><i class="fa-link"></i></a>
              </div>
            </div>
        <?php endforeach; ?>
        </div>
        <?php
      } else
      {
        echo '<p>Invalid flickr username.</p>';
      }
    }
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    $instance['title']   = strip_tags($new_instance['title']);
    $instance['show']    = strip_tags($new_instance['show']);
    $instance['account'] = strip_tags($new_instance['account']);

    return $instance;
  }

  // Settings form
  function form($instance)
  {
    $defaults = array('title' => 'Flickr', 'show' => '10', 'account' => 'eyetwist');
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label>
      <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('account'); ?>">Flickr Username:</label>
      <input id="<?php echo $this->get_field_id('account'); ?>" name="<?php echo $this->get_field_name('account'); ?>" value="<?php echo $instance['account']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('show'); ?>">show of Images:</label>
      <input id="<?php echo $this->get_field_id('show'); ?>" name="<?php echo $this->get_field_name('show'); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
    </p>
    <?php
  }

}

function widget_thbflickr_init()
{
  register_widget('widget_thbflickr');
}

add_action('widgets_init', 'widget_thbflickr_init');
