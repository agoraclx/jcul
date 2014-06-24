<aside class="sidebar three columns">
  <?php
  ##############################################################################
  # Display the asigned sidebar
  ##############################################################################
  ?>
  <?php
  if(is_page())
  {
    $sidebar = get_post_meta($post->ID, 'sidebar_set', true);
    if(is_active_sidebar($sidebar))
    {
      dynamic_sidebar($sidebar);
    }
  }
  else
  {
    dynamic_sidebar('blog');
  }
  ?>
</aside>