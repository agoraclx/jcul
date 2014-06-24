<?php

add_theme_support('nav-menus');
add_action('init', 'register_my_menus');

function register_my_menus()
{
  register_nav_menus(
    array(
      'top-menu'    => __('Top Bar Menu', THB_THEME_NAME),
      'footer-menu' => __('Sub Footer Menu', THB_THEME_NAME)
    )
  );
}