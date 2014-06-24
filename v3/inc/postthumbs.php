<?php

// Enable WP Post Thumbnails
if(function_exists('add_theme_support'))
{
  add_theme_support('post-thumbnails');
  set_post_thumbnail_size(110, 80, true);
  add_image_size('featured', 312, 375, true);
  add_image_size('slider', 752, 535, true);
  add_image_size('category-slider', 938, 580, true);
  add_image_size('recent', 284, 190, true);
  add_image_size('widget', 293, 170, true);
  add_image_size('category-home3', 534, 265, true);
  add_image_size('single', 938, 535, true);
  add_image_size('blog', 938, 465, true);
}
