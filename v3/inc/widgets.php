<?php

// thb Tag Cloud Size
function tag_cloud_filter($args = array())
{
  $args['smallest'] = 11;
  $args['largest']  = 11;
  $args['unit']     = 'px';
  $args['format']   = 'list';
  return $args;
}

add_filter('widget_tag_cloud_args', 'tag_cloud_filter', 90);
