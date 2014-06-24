<?php

// thb latest Posts w/ Images
class widget_tabbedposts extends WP_Widget
{

  function widget_tabbedposts()
  {
    /* Widget settings. */
    $widget_ops = array('description' => __('Tabbed widget showing popular & recent posts and comments', THB_THEME_NAME));

    /* Widget control settings. */
    $control_ops = array('width' => 300, 'height' => 350, 'id_base' => 'tabbedposts');

    /* Create the widget. */
    $this->WP_Widget('tabbedposts', __('Exquisite - Tabbed Widget', THB_THEME_NAME), $widget_ops, $control_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $show      = $instance['show'];
    global $post, $wpdb;
    $themePath = get_template_directory_uri();
    $pop       = new WP_Query();
    $pop->query('showposts='.$show.'');

    echo $before_widget;
    ?>
    <dl class="tabs"><dd class="active"><a href="#tab-0" class="hint--top hint--rounded" data-hint="<?php _e('Most Liked', THB_THEME_NAME); ?>"><i class="fa fa-star"></i></a></dd><dd><a href="#tab-1" class="hint--top hint--rounded" data-hint="<?php _e('Recent', THB_THEME_NAME); ?>"><i class="fa fa-file-text"></i></a></dd><dd><a href="#tab-2" class="hint--top hint--rounded" data-hint="<?php _e('Comments', THB_THEME_NAME); ?>"><i class="fa fa-comments"></i></a></dd></dl>
    <ul class="tabs-content">
      <li id="tab-0Tab" class="active">
        <?php
        $pop = new WP_Query();
        $pop = new WP_Query(array('post_type' => 'post', 'order' => 'DESC', 'orderby' => 'meta_value_num', 'meta_key' => '_likes', 'posts_per_page' => $show, 'no_found_rows' => true));
        while($pop->have_posts()) : $pop->the_post();
          ?>
          <div class="rowcontainer">
            <div class="row post">

              <div class="post-gallery four mobile-one columns">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
              </div>
              <div class="eight mobile-three columns post">
                <div class="post-title">
                  <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></h4>
                </div>
                <div class="post-content">
                  <?php echo thb_DisplayPostMeta(true, true, false, false); ?>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </li>
      <li id="tab-1Tab" style="display:none;">
        <?php
        $pop2 = new WP_Query();
        $pop2->query('showposts='.$show.'&no_found_rows=true');
        while($pop2->have_posts()) : $pop2->the_post();
          ?>
          <div class="rowcontainer">
            <div class="row post">

              <div class="post-gallery four mobile-one columns">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_post_thumbnail(); ?></a>
              </div>
              <div class="eight mobile-three columns post">
                <div class="post-title">
                  <h4><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>" class="postlink"><?php the_title(); ?></a></h4>
                </div>
                <div class="post-content">
                  <?php echo thb_DisplayPostMeta(true, true, false, false); ?>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </li>
      <li id="tab-2Tab" style="display:none;">
        <?php $recent_comments = get_comments(array('number' => $show, 'status' => 'approve')); ?>
        <?php
        foreach($recent_comments as $recent_comment)
        {
          $comment_permalink = get_permalink($recent_comment->comment_post_ID).'#comment-'.$recent_comment->comment_ID;
          ?>
          <div class="rowcontainer">
            <div class="row post">
              <div class="post-gallery four mobile-one columns">
                <a href="<?php echo $comment_permalink; ?>">
                  <?php echo get_avatar($recent_comment->user_id, 70); ?>
                </a>
              </div>
              <div class="eight mobile-three columns post">
                <strong><a href="<?php echo $comment_permalink; ?>"><?php echo $recent_comment->comment_author; ?></a></strong> on <strong><a href="<?php echo get_permalink($recent_comment->comment_post_ID); ?>"><?php echo get_the_title($recent_comment->comment_post_ID); ?></a></strong>
              </div>
            </div>
          </div>
        <?php } ?>
      </li>
    </ul>
    <?php
    echo $after_widget;
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;

    /* Strip tags (if needed) and update the widget settings. */
    $instance['show'] = strip_tags($new_instance['show']);

    return $instance;
  }

  function form($instance)
  {
    $defaults = array('show' => '5');
    $instance = wp_parse_args((array) $instance, $defaults);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('name'); ?>">Number of Posts:</label>
      <input id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('show'); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
    </p>
    <?php
  }

}

function widget_tabbedposts_init()
{
  register_widget('widget_tabbedposts');
}

add_action('widgets_init', 'widget_tabbedposts_init');