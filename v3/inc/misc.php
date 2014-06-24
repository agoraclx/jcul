<?php

function admin_avatar($id_or_email, $size)
{
  return '<img class="avatar avatar-'.$size.' photo" src="'.get_template_directory_uri().'/images/authors/'.$id_or_email.'.jpg" alt="'.get_the_author().'" style="width:'.$size.'px; height:'.$size.'px;" />';
}

/* Custom Background Support */
$args = array(
  'default-color' => 'fff'
);
add_theme_support('custom-background', $args);


/* Add SoundCloud oEmbed */

function add_oembed_soundcloud()
{
  wp_oembed_add_provider('http://soundcloud.com/*', 'http://soundcloud.com/oembed');
}

add_action('init', 'add_oembed_soundcloud');

/* Get Portfolio Page Link */

function get_portfolio_page_link($post_id)
{
  global $wpdb;

  $results = $wpdb->get_results("SELECT post_id FROM $wpdb->postmeta
    WHERE meta_key='_wp_page_template' AND meta_value='template-portfolio.php' OR meta_value='template-portfolio-shapes.php' OR meta_value='template-portfolio-paginated.php'");

  foreach($results as $result)
  {
    $page_id = $result->post_id;
  }

  return get_page_link($page_id);
}

/* Required Settings */
if(!isset($content_width))
  $content_width = 1170;
add_theme_support('automatic-feed-links');

/* Read More class */
add_filter('the_content_more_link', 'add_morelink_classes');

function add_morelink_classes($more_link_html)
{
  // Example - else this var has no scope inside the function
  global $var_declared_outside_function;

  $new_classes    = array('btn');
  $more_link_html = str_replace('class="more-link', 'class="'.implode(' ', $new_classes).' more-link', $more_link_html);

  return $more_link_html;
}

/* Remove WP default inline CSS for ".recentcomments a" from header */
add_action('widgets_init', 'my_remove_recent_comments_style');

function my_remove_recent_comments_style()
{
  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

/* Remove Unwanted Tags */

function remove_invalid_tags($str, $tags)
{
  foreach($tags as $tag)
  {
    $str = preg_replace('#^<\/'.$tag.'>|<'.$tag.'>$#', '', $str);
  }

  return $str;
}

/* Category Rel Fix */

function remove_category_list_rel($output)
{
  return str_replace(' rel="category tag"', '', $output);
}

add_filter('wp_list_categories', 'remove_category_list_rel');
add_filter('the_category', 'remove_category_list_rel');

/* Editor Styling */
add_editor_style();

/* Add Twitter oEmbed */
add_filter('oembed_result', 'twitter_no_width', 10, 3);

function twitter_no_width($html, $url, $args)
{
  if(false !== strpos($url, 'twitter.com'))
  {
    $html = str_replace('width="550"', '', $html);
  }
  return $html;
}

// Remove default styling for Gallery Shortcode
add_filter('gallery_style', create_function(
    '$css', 'return preg_replace("#<style type=\'text/css\'>(.*?)</style>#s", "", $css);'
  )
);

/* Fix Image Margins */

class fixImageMargins
{

  public $xs = 0; //change this to change the amount of extra spacing

  public function __construct()
  {
    add_filter('img_caption_shortcode', array(&$this, 'fixme'), 10, 3);
  }

  public function fixme($x = null, $attr, $content)
  {

    extract(shortcode_atts(array(
      'id'      => '',
      'align'   => 'alignnone',
      'width'   => '',
      'caption' => ''
        ), $attr));

    if(1 > (int) $width || empty($caption))
    {
      return $content;
    }

    if($id)
      $id = 'id="'.$id.'" ';

    return '<div '.$id.'class="wp-caption '.$align.'" style="width: '.((int) $width + $this->xs).'px">'
      .$content.'<p class="wp-caption-text"><i class="fa fa-picture"></i> '.$caption.'</p></div>';
  }

}
$fixImageMargins = new fixImageMargins();

/* Different Sub Category Templates */

function sub_category_template()
{

  // Get the category id from global query variables
  $cat = get_query_var('cat');

  if(!empty($cat))
  {

    // Get the detailed category object
    $category = get_category($cat);

    // Check if it is sub-category and having a parent, also check if the template file exists
    if($category->parent != '0')
    {

      // Include the template for sub-catgeory
      get_template_part('csub-category');
      exit;
    }
    return;
  }
  return;
}

add_action('template_redirect', 'sub_category_template');

/* Author FB, TW & G+ Links */

function my_new_contactmethods($contactmethods)
{
// Add Twitter
  $contactmethods['twitter']    = 'Twitter URL';
// Add Facebook
  $contactmethods['facebook']   = 'Facebook URL';
// Add Google+
  $contactmethods['googleplus'] = 'Google Plus URL';

  return $contactmethods;
}

add_filter('user_contactmethods', 'my_new_contactmethods', 10, 1);


/* Get Homepage Categories & Order */

function thb_DisplayStars($count)
{
  $output = '';
  for($x = 1; $x <= $count; $x++)
  {
    $output .= '<i class="fa fa-star"></i>';
  }
  if(strpos($count, '.'))
  {
    $output .= '<i class="fa fa-star-half-full"></i>';
    $x++;
  }
  while($x <= 5)
  {
    $output .= '<i class="fa fa-star-o"></i>';
    $x++;
  }

  return '<span class="stars">'.$output.'</span>';
}

/* Display Post Meta */

function thb_DisplayPostMeta($date = true, $likes = true, $comments = true, $author = true)
{
  $output = '<aside class="post-meta">';
  $output .= '<ul>';
  if($date)
  {
//    $output .= '<li><a href="'.get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')).'">'.get_the_date('M j, Y').'</a></li>';
    $output .= '<li><a href="'.get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')).'">'.agora_time_diff(get_the_time('U'), current_time('timestamp')).' yang lalu'.'</a></li>';
  }
  if($likes)
  {
    if(ot_get_option('disablelike') == 'no')
    {
      $output .= '<li>&bull;  '.thb_printLikes(get_the_ID()).'</li>';
    }
  }
  if($comments)
  {
    $output .= '<li>&bull; '.get_comments_popup_link('<i class="fa fa-comment-o"></i> 0', '<i class="fa fa-comment-o"></i> 1', '<i class="fa fa-comment-o"></i> %', 'postcommentcount', '<i class="fa fa-comment-o"></i> -').'</li>';
  }
  if($author)
  {
//    $output .= '<li class="right"><a href="'.get_author_posts_url(get_the_author_meta("ID")).'">'.get_the_author_meta("display_name").'</a><a href="'.get_author_posts_url(get_the_author_meta("ID")).'">'.get_avatar(get_the_author_meta("ID"), "26").'</a></li>';
    $output .= '<li class="right"><a href="'.get_author_posts_url(get_the_author_meta("ID")).'">'.get_the_author_meta("display_name").'</a><a href="'.get_author_posts_url(get_the_author_meta("ID")).'">'.admin_avatar(get_the_author_meta("ID"), "26").'</a></li>';
  }
  $output .= '</ul>';
  $output .= '</aside>';

  return $output;
}

/* Get Homepage Categories & Order */

function thb_HomePageCategories()
{
  $categories = ot_get_option('home_categories');
  $order      = ot_get_option('home_category_order');

  if(!empty($categories) && !empty($order))
  {

    function flatten_array($array, &$out = array())
    {
      foreach($array as $key => $child)
      {
        array_push($out, $child['id']);
      }
      return $out;
    }

    $out2   = flatten_array($order);
    $result = array_intersect($out2, $categories);
  }
  else
  {
    $result = '';
  }

  return $result;
}

/* Get Single Category */

function thb_GetSingleCategory($id = false)
{
  if((int) $id)
  {
    $cat = get_category($id);
    if(!empty($cat))
    {
      $backup = $cat->term_id;
      if($cat->parent == 0)
        $id     = $cat->term_id;
    }
  }
  if(empty($id))
  {
    $id         = '';
    $categories = get_the_category();

    if(empty($categories))
      return;
    while(empty($id) && !empty($categories))
    {
      $cat = array_shift($categories);
      if($cat->parent == 0)
        $id  = $cat->term_id;
    }
  }
  // if no parent cat found, but a not-parent cat id passed to function use that
  if(!(int) $id && isset($backup))
    $id = $backup;
  if(!(int) $id)
    return;
  return $id;
}

/* Display Single Category */

function thb_DisplaySingleCategory($boxed = true, $id = false)
{
  if((int) $id)
  {
    $cat = get_category($id);
    if(!empty($cat))
    {
      $backup = $cat->term_id;
      if($cat->parent == 0)
        $id     = $cat->term_id;
    }
  }
  if(empty($id))
  {
    $id         = '';
    $categories = get_the_category();

    if(empty($categories))
      return;
    while(empty($id) && !empty($categories))
    {
      $cat = array_shift($categories);
      if($cat->parent == 0)
        $id  = $cat->term_id;
    }
  }
  // if no parent cat found, but a not-parent cat id passed to function use that
  if(!(int) $id && isset($backup))
    $id     = $backup;
  if(!(int) $id)
    return;
  $name   = get_cat_name($id);
  $url    = esc_url(get_category_link($id));
  $wcolor = $boxed ? 'background' : 'color';
  $class  = $boxed ? ' class="boxed"' : '';
  $color  = GetCategoryColor($id);
  $frmt   = '<a href="%s"%s title="%s" style="%s:%s;">%s</a>';
  return sprintf($frmt, $url, $class, esc_attr($name), $wcolor, $color, esc_html($name));
}

/* Get Rating Color */

function thb_GetAverageRatingColor($id)
{

  $ratingType = get_post_meta($id, 'rating_type', TRUE);

  if($ratingType == 'star')
  {
    $features = get_post_meta($id, 'post_ratings', TRUE);
    $count    = count($features);
    $total    = '0';
    foreach($features as $feature)
    {
      $total += $feature['feature_rating'];
    }

    $num = round($total / $count, 1);

    switch($num)
    {
      case ($num >= 0 && $num <= 1):
        return '#d10000';
        break;
      case ($num > 1 && $num <= 2):
        return '#d96b00';
        break;
      case ($num > 2 && $num <= 3):
        return '#d9c100';
        break;
      case ($num > 3 && $num <= 4):
        return '#92cd03';
        break;
      case ($num > 4):
        return '#04bc02';
        break;
    }
  }
  else if($ratingType == 'percentage')
  {
    $features = get_post_meta($id, 'post_ratings_percentage', TRUE);
    $count    = count($features);
    $total    = '0';
    foreach($features as $feature)
    {
      $total += $feature['feature_percentage'];
    }

    $num = round($total / $count, 0);

    switch($num)
    {
      case ($num >= 0 && $num <= 20):
        return '#d10000';
        break;
      case ($num > 20 && $num <= 40):
        return '#d96b00';
        break;
      case ($num > 40 && $num <= 60):
        return '#d9c100';
        break;
      case ($num > 60 && $num <= 80):
        return '#92cd03';
        break;
      case ($num > 80):
        return '#04bc02';
        break;
    }
  }
}

/* Get Average Rating */

function thb_GetAverageRating($id)
{
  $ratingType = get_post_meta($id, 'rating_type', TRUE);

  if($ratingType == 'star')
  {
    $features = get_post_meta($id, 'post_ratings', TRUE);
    $count    = count($features);
    $total    = '0';
    foreach($features as $feature)
    {
      $total += $feature['feature_rating'];
    }

    return '<aside class="imagetag">'.round($total / $count, 1).'</aside>';
  }
  else if($ratingType == 'percentage')
  {
    $features = get_post_meta($id, 'post_ratings_percentage', TRUE);
    $count    = count($features);
    $total    = '0';
    foreach($features as $feature)
    {
      $total += $feature['feature_percentage'];
    }

    return '<aside class="imagetag rating">'.round($total / $count, 0).'%</aside>';
  }
}

/* Display Average Rating or Video Icon */

function thb_DisplayImageTag($id)
{
  $postformat = get_post_format($id);
  if(get_post_meta($id, 'is_review', TRUE) == 'yes')
  {
    return thb_GetAverageRating($id);
  }
  else if($postformat == 'video')
  {
    return '<aside class="imagetag" title="Video"><i class="fa fa-play"></i></aside>';
  }
  else if($postformat == 'gallery')
  {
    return '<aside class="imagetag" title="Gallery"><i class="fa fa-picture-o"></i></aside>';
  }
  else if($postformat == 'image')
  {
    return '<aside class="imagetag" title="Image"><i class="fa fa-camera"></i></aside>';
  }
  else
  {
    return false;
  }
}

/* Get Category Color */

function GetCategoryColor($id)
{
  $color = ot_get_option('category_colors_'.$id, '#222');
  return $color;
}

/* Add Colors to Category */

class CategoryColors_Walker extends Walker_Category
{

  private $in_sub_menu = 0;

  function start_lvl(&$output, $depth = 0, $args = array())
  {
    $output .= '<div class="category-holder"><ul>';
  }

  function end_lvl(&$output, $depth = 0, $args = array())
  {
    $output .= '</ul>';
  }

  function start_el(&$output, $category, $depth = 0, $args = array(), $id = 0)
  {

    $cat_name  = esc_attr($category->name);
    $cat_id    = esc_attr($category->term_id);
    $cat_color = GetCategoryColor($cat_id);
    $cat_name  = apply_filters('list_cats', $cat_name, $category);

    $termchildren = get_term_children($category->term_id, $category->taxonomy);

    // Detect first child of submenu then add class active
    $aclass    = '';
    $datacolor = '';
    if($depth == 1)
    {
      if(!$this->in_sub_menu)
      {
        $aclass            = 'class="active"';
        $this->in_sub_menu = 1;
      }
    }
    if($depth == 0)
    {
      $datacolor         = 'data-color="'.$cat_color.'"';
      $this->in_sub_menu = 0;
    }

    $link = '<a '.$aclass.' href="'.esc_url(get_term_link($category)).'" '.$datacolor.' title="'.esc_attr(sprintf(__('View all posts filed under %s'), $cat_name)).'">'.$cat_name.'</a>';

    $output .= '<li>'.$link;
  }

  function end_el(&$output, $category, $depth = 0, $args = array())
  {
    $termchildren = get_terms($category->taxonomy, array('child_of' => $category->term_id, 'hide_empty' => false,));

    if($depth == 0 && $termchildren)
    {
      $output .= '<div class="category-children">';

      $i = 0;
      foreach($termchildren as $child)
      {
        $output .= '<div'.(($i === 0) ? ' class="active"' : '').'>';
        $query = new WP_Query(array(
          'posts_per_page' => 4,
          'no_found_rows'  => true,
          'post_status'    => 'publish',
          'cat'            => $child->term_id
        ));

        if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
            $output.= '
				<div class="row post">
					<div class="three columns post-gallery"><a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_post_thumbnail().'</a></div>
					<div class="nine columns post">
						<div class="post-title">
							<h4><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'" class="postlink">'.get_the_title().'</a></h4>
						</div>
						<div class="post-content">
							'.thb_DisplayPostMeta(true, true, true, false).'
						</div>
					</div>
				</div>';

            wp_reset_postdata();

          endwhile;
        else: endif;


        $output .= '<a href="'.get_category_link($child->term_id).'" class="gotocategory" title="'.$child->name.'">View all</a>';
        $output .= "</div>";
        $i++;
      }

      $output .= "</div></div>";
    } else
    {

    }
    $output .= "</li>";
  }

}


function agoraclx_cleanup()
{
  // Originally from http://wpengineer.com/1438/wordpress-header/
  remove_action('wp_head', 'feed_links', 2);
  remove_action('wp_head', 'feed_links_extra', 3);
  remove_action('wp_head', 'rsd_link');
  remove_action('wp_head', 'wlwmanifest_link');
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
  remove_action('wp_head', 'wp_generator');
  remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}

add_action('init', 'agoraclx_cleanup');

/**
 * Remove the WordPress version from RSS feeds
 */
add_filter('the_generator', '__return_false');

/**
 * Clean up output of stylesheet <link> tags
 */
function agoraclx_clean_style_tag($input)
{
  preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
  $media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="'.$matches[3][0].'"' : '';
  return '<link rel="stylesheet" href="'.$matches[2][0].'"'.$media.'>'."\n";
}

add_filter('style_loader_tag', 'agoraclx_clean_style_tag');

function agoraclx_remove_dashboard_widgets()
{
  remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
  remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
  remove_meta_box('dashboard_primary', 'dashboard', 'normal');
  remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}

add_action('admin_init', 'agoraclx_remove_dashboard_widgets');

function agoraclx_admin_bar()
{
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu('wp-logo');
  $wp_admin_bar->remove_menu('about');
  $wp_admin_bar->remove_menu('wporg');
  $wp_admin_bar->remove_menu('documentation');
  $wp_admin_bar->remove_menu('support-forums');
  $wp_admin_bar->remove_menu('feedback');
  $wp_admin_bar->remove_menu('view-site');
}

add_action('wp_before_admin_bar_render', 'agoraclx_admin_bar');