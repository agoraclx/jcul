<?php

/**
 * Plugin Name: () Exquisite - Shortcodes & Widgets
 * Description: This contains all the widgets and shortcodes used in the demo
 * Version: 1.0.0
 * Author: fuelthemes
 * Author URI: http://themeforest.net/user/fuelthemes
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 */
if(!defined('THB_THEME_NAME'))
{
  define('THB_THEME_NAME', 'exquisite');
}
// Plugin Directory
define('BE_DIR', dirname(__FILE__));
// Shortcode Generator & Shortcodes
require_once(BE_DIR.'/lib/tinymce/tinymce-class.php');
require_once(BE_DIR.'/lib/tinymce/shortcode-processing.php');
// Widgets
include_once(BE_DIR.'/lib/widgets/featured-video.php');
include_once(BE_DIR.'/lib/widgets/flickr.php');
include_once(BE_DIR.'/lib/widgets/fb-likebox.php');
include_once(BE_DIR.'/lib/widgets/post-formats.php');
include_once(BE_DIR.'/lib/widgets/social-counter.php');
include_once(BE_DIR.'/lib/widgets/subscribe.php');
include_once(BE_DIR.'/lib/widgets/tabbed-posts.php');
include_once(BE_DIR.'/lib/widgets/latest-reviews.php');
include_once(BE_DIR.'/lib/widgets/topnews-category.php');
include_once(BE_DIR.'/lib/widgets/latest-category.php');
include_once(BE_DIR.'/lib/widgets/latest-images.php');
include_once(BE_DIR.'/lib/widgets/sponsor.php');
include_once(BE_DIR.'/lib/widgets/sponsors.php');
