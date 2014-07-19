<?php

/* -----------------------------------------------------------------------------------

  Here we have all the custom functions for the theme
  Please be extremely cautious editing this file.
  You have been warned!

  ------------------------------------------------------------------------------------- */
// Define Theme Name for localization
if(!defined('THB_THEME_NAME'))
{
  define('THB_THEME_NAME', 'exquisite');
}

// Translation
add_action('after_setup_theme', 'lang_setup');

function lang_setup()
{
  load_theme_textdomain(THB_THEME_NAME, get_template_directory().'/inc/languages');
}

// Option-Tree Theme Mode
add_filter('ot_show_pages', '__return_false');
add_filter('ot_show_new_layout', '__return_false');
add_filter('ot_theme_mode', '__return_true');
include_once( 'inc/ot-fonts.php' );
include_once( 'inc/ot-radioimages.php' );
include_once( 'inc/ot-metaboxes.php' );
include_once( 'inc/ot-themeoptions.php' );

if(!class_exists('OT_Loader'))
{
  include_once( 'admin/ot-loader.php' );
}
// Script Calls
require_once('inc/script-calls.php');

// Breadcrumbs
require_once('inc/breadcrumbs.php');

// Excerpts
require_once('inc/excerpts.php');

// Custom Titles
require_once('inc/wptitle.php');

// Pagination
require_once('inc/wp-pagenavi.php');

// Post Formats
add_theme_support('post-formats', array('video', 'image', 'gallery'));

// Masonry Load More
require_once('inc/masonry-ajax.php');
add_action("wp_ajax_nopriv_thb_ajax_home", "load_more_posts");
add_action("wp_ajax_thb_ajax_home", "load_more_posts");
add_action("wp_ajax_nopriv_thb_ajax_home2", "load_more_posts_type2");
add_action("wp_ajax_thb_ajax_home2", "load_more_posts_type2");

// TGM Plugin Activation Class
require_once('inc/class-tgm-plugin-activation.php');
require_once('inc/plugins.php');

// Enable Featured Images
require_once('inc/postthumbs.php');

// Activate WP3 Menu Support
require_once('inc/wp3menu.php');

// Enable Sidebars
require_once('inc/sidebar.php');

// Custom Comments
require_once('inc/comments.php');

// Widgets
require_once('inc/widgets.php');

// Like functionality
require_once('inc/themelike.php');

// Related Posts
require_once('inc/related.php');

// Weather
require_once('inc/weather.php');

// Custom Login Logo
require_once('inc/customloginlogo.php');

// Do Shortcodes inside Widgets
add_filter('widget_text', 'do_shortcode');

// Twitter oAuth
require_once('inc/twitter_oauth.php');
require_once('inc/twitter_gettweets.php');

// Misc
require_once('inc/misc.php');
/*
 * adding agent shit to facebook
 */

function agent()
{
  $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
  $ipad   = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
  $iPod   = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");

  if($iphone == true)
  {
    echo '278';
  }
  elseif($ipad == true)
  {
    echo '600';
  }
  elseif($iPod == true)
  {
    echo '278';
  }
  else
  {
    echo '900';
  }
}

/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */
function agora_time_diff($from, $to = '')
{
  if(empty($to))
    $to = time();

  $diff = (int) abs($to - $from);

  if($diff < HOUR_IN_SECONDS)
  {
    $mins  = round($diff / MINUTE_IN_SECONDS);
    if($mins <= 1)
      $mins  = 1;
    /* translators: min=minute */
    $since = sprintf(_n('%s menit', '%s menit', $mins), $mins);
  } elseif($diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS)
  {
    $hours = round($diff / HOUR_IN_SECONDS);
    if($hours <= 1)
      $hours = 1;
    $since = sprintf(_n('%s jam', '%s jam', $hours), $hours);
  } elseif($diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS)
  {
    $days  = round($diff / DAY_IN_SECONDS);
    if($days <= 1)
      $days  = 1;
    $since = sprintf(_n('%s hari', '%s hari', $days), $days);
  } elseif($diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS)
  {
    $weeks = round($diff / WEEK_IN_SECONDS);
    if($weeks <= 1)
      $weeks = 1;
    $since = sprintf(_n('%s minggu', '%s minggu', $weeks), $weeks);
  } elseif($diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS)
  {
    $months = round($diff / ( 30 * DAY_IN_SECONDS ));
    if($months <= 1)
      $months = 1;
    $since  = sprintf(_n('%s bulan', '%s bulan', $months), $months);
  } elseif($diff >= YEAR_IN_SECONDS)
  {
    $years = round($diff / YEAR_IN_SECONDS);
    if($years <= 1)
      $years = 1;
    $since = sprintf(_n('%s tahun', '%s tahun', $years), $years);
  }

  return $since;
}

function agoraclx_dash()
{
  echo "<ul>
	<li>Release Date: April 2014</li>
	<li>Author: Agora CLx.</li>
	<li>j-cul.com</li>
	<li>".base64_decode(base64_encode('UlcxaGFXd2dlVzkxY2lCeVpYTjFiV1VnZEc4Z1oyVnVZbWw2UUhkdmNtUm1aVzVqWlM1amIyMGdkMmwwYUNCMGFHVWdjM1ZpYW1WamRDQWlTbTlpSUdGd2NHeHBZMkYwYVc5dUlpNGdWMlVuWkNCc2IzWmxJSFJ2SUdobFlYSWdabkp2YlNCNWIzVXU='))."</li>
	</ul>";
}

function information()
{
  wp_add_dashboard_widget('author_information', 'Technical Information', 'agoraclx_dash');
}

add_action('wp_dashboard_setup', 'information');

/*
 * WordPress _transient buildup
 */
add_action('wp_scheduled_delete', 'delete_expired_db_transients');

function delete_expired_db_transients()
{

  global $wpdb, $_wp_using_ext_object_cache;

  if($_wp_using_ext_object_cache)
    return;

  $time    = isset($_SERVER['REQUEST_TIME']) ? (int) $_SERVER['REQUEST_TIME'] : time();
  $expired = $wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout%' AND option_value < {$time};");

  foreach($expired as $transient)
  {

    $key = str_replace('_transient_timeout_', '', $transient);
    delete_transient($key);
  }
}

// Add Shortcode for single content and place it anyware in content
// untuk post gen dude
function leak_custom($atts)
{
  ob_start();
  extract(shortcode_atts(
      array(
    'slug' => '',
      ), $atts)
  );

  // WP_Query arguments
  $kleng = array(
    'name'                   => $slug,
    'posts_per_page'         => 1,
    'cache_results'          => true,
    'update_post_meta_cache' => true,
    'update_post_term_cache' => true,
  );

  $kuluk = new WP_Query($kleng);
  while($kuluk->have_posts()): $kuluk->the_post();
    $output = the_content();
  endwhile;
  wp_reset_postdata();
  return $output.ob_get_clean();
}

add_shortcode('loopcontent', 'leak_custom');
