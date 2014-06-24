<?php

function jculers_widgets_init ()
{
   register_sidebar(array (
       'name'          => 'Middle Sidebar Atas',
       'id'            => 'middle-1',
       'description'   => 'Sidebar tengah atas COCOK UNTUK FACEBOOK PAGE ATAU IKLAN',
       'before_widget' => '<div class="widget_container">',
       'after_widget'  => '</div>',
       'before_title'  => '<h2 class="semantic">',
       'after_title'   => '</h2>',
   ));
   register_sidebar(array (
       'name'          => 'Middle Sidebar Bawah',
       'id'            => 'middle-2',
       'description'   => 'Sidebar tengah atas untuj apa saja boleh',
       'before_widget' => '<div class="widget_container">',
       'after_widget'  => '</div>',
       'before_title'  => '<h2 class="semantic">',
       'after_title'   => '</h2>',
   ));

   register_sidebar(array (
       'name'          => 'Sidebar kanan Atas',
       'id'            => 'kanan-1',
       'description'   => 'Sidebar sebelah kanan ATAS yang isinya apa saja',
       'before_widget' => '<article class="post" style="margin:0 0 20px 0">',
       'after_widget'  => '</article>',
       'before_title'  => '<h2 class="semantic">',
       'after_title'   => '</h2>',
   ));
   register_sidebar(array (
       'name'          => 'Sidebar kanan bawah',
       'id'            => 'kanan-2',
       'description'   => 'Sidebar sebelah kanan BAWAH yang isinya apa saja',
       'before_widget' => '<div class="widget_container">',
       'after_widget'  => '</div>',
       'before_title'  => '<h2 class="semantic">',
       'after_title'   => '</h2>',
   ));
}

add_action('widgets_init', 'jculers_widgets_init');



//add_theme_support( 'post-formats', array(
//		'image'
//	) );

/**
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function mog_wp_title ($title, $sep)
{
   global $paged, $page;

   if(is_feed())
   {
      return $title;
   }

   // Add the site name.
   $title .= get_bloginfo('name');

   // Add the site description for the home/front page.
   $site_description = get_bloginfo('description', 'display');
   if($site_description && ( is_home() || is_front_page() ))
   {
      $title = "$title $sep $site_description";
   }

   // Add a page number if necessary.
   if($paged >= 2 || $page >= 2)
   {
      $title = "$title $sep ".sprintf(__('Page %s'), max($paged, $page));
   }

   return $title;
}

add_filter('wp_title', 'mog_wp_title', 10, 2);

/**
 *
 * @param string $dir
 * @param type $no_more
 * @throws Exception
 */
function include_files_in_dir ($dir, $no_more = FALSE)
{
   $dir_init = $dir;
   $dir      = dirname(__FILE__).$dir;
   if(!file_exists($dir))
   {
      throw new Exception("Folder $dir does not exist");
   }
   $files  = array ();
   if($handle = opendir($dir))
   {
      while(false !== ($file = @readdir($handle)))
      {
         if(is_dir($dir.$file) && !preg_match('/^\./', $file) && !$no_more)
         {
            include_files_in_dir($dir_init.$file."/", TRUE);
         }
         else
         {
            if(preg_match('/^[^~]{1}.*\.php$/', $file))
            {
               $files[] = $dir.$file;
            }
         }
      }
      @closedir($handle);
   }
   sort($files);
   foreach($files as $file)
   {
      include_once $file;
   }
}

/**
 * script
 */
function agoraclx_scripts_styles ()
{
   wp_deregister_script('jquery');
//   wp_register_script('jquery', "http".($_SERVER['SERVER_PORT'] == 443 ? "s" : "")."://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js", array (), NULL, false);
//   wp_enqueue_script('jquery');
   wp_enqueue_style('appcss', get_template_directory_uri().'/app.css', '', NULL);
//   wp_enqueue_style('appscss', get_template_directory_uri().'/devapp.css', '', NULL); //for localhostONLY
   wp_enqueue_script('appjs', get_template_directory_uri().'/js/app.js', array (), NULL, false);
}

add_action('wp_enqueue_scripts', 'agoraclx_scripts_styles');

/**
 *
 * @global type $wp_widget_factory
 * @cleaning the header for the sake of default
 *
 * return void
 */
function head_cleanup ()
{
   remove_action('wp_head', 'feed_links', 2);
   remove_action('wp_head', 'feed_links_extra', 3);
   remove_action('wp_head', 'rsd_link');
   remove_action('wp_head', 'wlwmanifest_link');
   remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
   remove_action('wp_head', 'wp_generator');
   remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

   global $wp_widget_factory;
   remove_action('wp_head', array ($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));

   add_filter('use_default_gallery_style', '__return_null');

   if(!class_exists('WPSEO_Frontend'))
   {
      remove_action('wp_head', 'rel_canonical');
   }
}

add_action('init', 'head_cleanup');

add_theme_support('post-thumbnails');

/**
 *
 * @param type $date
 * @return string
 *
 * get date for nicely readeable by muggle
 * sample 1 bulan yang lalu;
 */
function nicetime ($date)
{
   if(empty($date))
   {
      return "No date provided";
   }

   $periods = array ("detik", "menit", "jam", "hari", "minggu", "bulan", "tahun", "dekade");
   $lengths = array ("60", "60", "24", "7", "4.35", "12", "10");

   $now       = time();
   $unix_date = strtotime($date);

   // check validity of date
   if(empty($unix_date))
   {
      return "Bad date";
   }

   // is it future date or past date
   if($now > $unix_date)
   {
      $difference = $now - $unix_date;
      $tense      = "yang lalu";
   }
   else
   {
      $difference = $unix_date - $now;
      $tense      = "dari sekarang";
   }

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
   {
      $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1)
   {
      $periods[$j].= "";
   }

   return "$difference $periods[$j] {$tense}";
}

/**
 *
 * @param type $agoraclx_featured_img_size
 * @return string
 *
 * sample " agoraclx_featured_img_url('large')" //medium, small
 */
function agoraclx_featured_img_url ($agoraclx_featured_img_size)
{
   $agoraclx_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $agoraclx_featured_img_size);
   $agoraclx_image_url = $agoraclx_image_url[0];
   return $agoraclx_image_url;
}

/**
 * categories_link with link
 * @return string
 */
function categories_link ()
{
   $categories = get_categories(array ('orderby' => 'id', 'order' => 'DESC', 'hide_empty' => 0));
   foreach($categories as $category)
   {
      $url  = get_category_link($category->cat_ID);
      $name = ', '.$category->name;
      $html .= '<a style="display:inline-block" href="'.$url.'" title="lihat kategori'.$name.'">'.$name.'</a>';
   }
   echo $html;
}

/**
 *
 * @return string
 */
function categories_without_link ()
{
   $categories = get_categories(array (
       'orderby' => 'name'
   ));
   foreach($categories as $category)
   {
      $name = $category->name;
      $html .= $name.',';
   }
   return $html;
}

/**
 * get categories with link
 */
function category_list_single ()
{
   $categories = get_categories(array ('orderby' => 'name', 'order' => 'DESC', 'hide_empty' => 0));
   foreach($categories as $category)
   {
      $url  = get_category_link($category->cat_ID);
      $name = $category->name;
      $html = '<a class="article-category" href="'.$url.'" title="'.$name.'">'.$name.'</a>';
   }
   return $html;
}

/**
 * show only one category names
 */
function single_category ()
{
   $categories = get_the_category();
   foreach($categories as $category)
   {
      $name = $category->name;
   }
   echo "{$name}";
}

/**
 * show only one category ids
 */
function single_category_id ()
{
   $categories = get_the_category();
   foreach($categories as $category)
   {
      $name = $category->term_id;
   }
   echo $name;
}

/**
 *
 * @param string $avatar
 * @param type $id_or_email
 * @param type $size
 * @return string
 */
function admin_avatar ($avatar, $id_or_email, $size)
{
   $avatar = '<img class="avatar avatar-100 photo" src="'.get_template_directory_uri().'/images/authors/'.$id_or_email.'.jpg" alt="'.get_the_author().'" width="'.$size.'px" height="'.$size.'px" />';
   return $avatar;
}

if(!function_exists('jcul_agora_comment')) :

   /**
    * Template for comments and pingbacks.
    * To override this walker in a child theme without modifying the comments template
    * Used as a callback by wp_list_comments() for displaying the comments.
    */
   function jcul_agora_comment ($comment, $args, $depth)
   {
      $GLOBALS['comment'] = $comment;
      switch($comment->comment_type) :
         case 'pingback' :
         case 'trackback' :
            ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
<div class="comment-text"><?php _e('Pingback:'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'twentytwelve'), '<span class="edit-link">', '</span>'); ?></div></li>
            <?php
            break;
         default :
            global $post;
            ?>
<li <?php comment_class(); ?>>
<div class="comment" id="comment-<?php comment_ID(); ?>">
<div class="comment-author">
<?php echo ( $comment->user_id === $post->post_author ) ? admin_avatar($avatar, get_the_author_meta('ID'), 44) : get_avatar($comment, 44) ?>
<div class="authormeta">
<?php
printf('<h3 class="fn">%1$s %2$s</h3>', get_comment_author_link(),
// If current post author is also comment author, make it known visually.
( $comment->user_id === $post->post_author ) ? '<em>author</em>' : ''
);
printf(' <span class="datetime"><a href="%1$s"><time datetime="%2$s">%3$s</time></a></span>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'), sprintf(__('%2$s'), get_comment_date(), human_time_diff(get_comment_time('U'), current_time('timestamp')).' ago'));
?>
</div>
</div>
<div class="comment-text">
<?php if('0' == $comment->comment_approved) : ?>
<p class="comment-awaiting-moderation">komentar anda menunggu di moderasi.</p>
<?php endif; ?>
<?php comment_text(); ?>
<?php edit_comment_link(__('di Edit'), '<p class="edit-link">', '</p>'); ?>
</div>
<div class="commentmeta">
<div class="reply">
<?php comment_reply_link(array_merge($args, array ('reply_text' => __('Reply'), 'respond_id' => 'komen_respond', 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
</div>
</div>
</div>
</li>
            <?php
            break;
      endswitch;
   }

endif;

/**
 * Outputs a complete commenting form for use within a template.
 * Most strings and form fields may be controlled through the $args array passed
 * into the function, while you may also choose to use the comment_form_default_fields
 * filter to modify the array of default fields if you'd just like to add a new
 * one or remove a single field. All fields are also individually passed through
 * a filter of the form comment_form_field_$name where $name is the key used
 * in the array of fields.
 *
 * @since 3.0.0
 * @param array $args Options for strings, fields etc in the form
 * @param mixed $post_id Post ID to generate the form for, uses the current post if null
 * @return void
 */
if(!function_exists('comment_form_jcul')) :

   function comment_form_jcul ($args = array (), $post_id = null)
   {
      global $id;

      if(null === $post_id)
      {
         $post_id = $id;
      }
      else
      {
         $id = $post_id;
      }
      $commenter     = wp_get_current_commenter();
      $user          = wp_get_current_user();
      $user_identity = $user->exists() ? $user->display_name : '';

      $req      = get_option('require_name_email');
      $aria_req = ( $req ? " aria-required='true'" : '' );
      $fields   = array (
          'author' => '<p class="comment-form-author">'.'<label for="author">'.__('Nama Jculers*').( $req ? ' <span class="required">*</span>' : '' ).'</label> '.
          '<input id="author" name="author" type="text" value="'.esc_attr($commenter['comment_author']).'" size="30"'.$aria_req.' /></p>',
          'email'  => '<p class="comment-form-email"><label for="email">'.__('Email Jculers*').( $req ? ' <span class="required">*</span>' : '' ).'</label> '.
          '<input id="email" name="email" type="text" value="'.esc_attr($commenter['comment_author_email']).'" size="30"'.$aria_req.' /></p>',
          'url'    => '<p class="comment-form-url"><label for="url">'.__('Website jculers').'</label>'.
          '<input id="url" name="url" type="text" value="'.esc_attr($commenter['comment_author_url']).'" size="30" /></p>',
      );

      $required_text = sprintf(' '.__('Required fields are marked %s'), '<span class="required">*</span>');
      $defaults      = array (
          'fields'               => apply_filters('comment_form_default_fields', $fields),
          'comment_field'        => '<p class="comment-form-comment"><label for="comment">'._x('Comment', 'noun').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
          'must_log_in'          => '<p class="must-log-in">'.sprintf(__('You must be <a href="%s">logged in</a> to post a comment.'), wp_login_url(apply_filters('the_permalink', get_permalink($post_id)))).'</p>',
          'logged_in_as'         => '<p class="logged-in-as">'.sprintf(__('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>'), get_edit_user_link(), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink($post_id)))).'</p>',
          'comment_notes_after'  => '<p class="form-allowed-tags">'.__('email jculers tidak akan pernah dipublikasikan.').( $req ? $required_text : '' ).'</p>',
          'comment_notes_before' => '<p class="comment-notes">'.sprintf(__('terimakasih atas komentar kalian Jculers..... setiap komentar kalian yang masuk, akan dimoderasi terlebih dahulu. Hindari mennggunakan kata yang mengandung spamy keywords karena akan langsung terdelete dan tak lupa terima kasih sudah berkunjung ke j-cul.com')).'</p>',
          'id_form'              => 'commentform',
          'id_submit'            => 'submit',
          'title_reply'          => __('Silahkan tinggalkan komentar jculers'),
          'title_reply_to'       => __('tinggalkan komentar %s'),
          'cancel_reply_link'    => __('Batal untuk mereplay'),
          'label_submit'         => __('Post Komentar'),
      );

      $args = wp_parse_args($args, apply_filters('comment_form_defaults', $defaults));
      if(comments_open($post_id)) : do_action('comment_form_before');
         ?>
<div id="komen_respond">
<h3 id="reply-title"><?php comment_form_title($args['title_reply'], $args['title_reply_to']); ?> <small><?php cancel_comment_reply_link($args['cancel_reply_link']); ?></small></h3>
<?php
if(get_option('comment_registration') && !is_user_logged_in()) :
echo $args['must_log_in'];
do_action('comment_form_must_log_in_after');
else :
?>
<form action="<?php echo site_url('/wp-comments-post.php'); ?>" method="post" id="<?php echo esc_attr($args['id_form']); ?>">
<?php
do_action('comment_form_top');
if(is_user_logged_in()) :
echo apply_filters('comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity);
do_action('comment_form_logged_in_after', $commenter, $user_identity);
else :
echo $args['comment_notes_before'];
do_action('comment_form_before_fields');
foreach((array) $args['fields'] as $name => $field)
{
echo apply_filters("comment_form_field_{$name}", $field)."\n";
}
do_action('comment_form_after_fields');
endif;
echo apply_filters('comment_form_field_comment', $args['comment_field']);
echo $args['comment_notes_after'];
?>
<p class="form-submit">
<input class="btn btn-primary" name="submit" type="submit" id="<?php echo esc_attr($args['id_submit']); ?>" value="<?php echo esc_attr($args['label_submit']); ?>" />
<?php comment_id_fields($post_id); ?>
</p>
<?php do_action('comment_form', $post_id); ?>
</form>
<?php endif; ?>
</div>
         <?php
         do_action('comment_form_after');
      else : do_action('comment_form_comments_closed');
      endif;
   }

endif;

/**
 * Infinite Scroll
 */
function custom_infinite_scroll_js ()
{
   if(!is_singular())
   {
      ?>
<script>$('#infinite, #search-posts').infinitescroll({animate: false, nextSelector: "#nav-below .nav-previous a", navSelector: "#nav-below", itemSelector: "#infinite .article-container, #search-posts .article-container"});
jQuery("#column-new article.post .article-img-container span._ppf.loaded img").each(function() {
jQuery(this).removeAttr("width");
jQuery(this).removeAttr("height");
});
$(function() {
var scrollBtn = $('#backTop');
$(window).scroll(function() {
if ($(this).scrollTop() > 400) {
scrollBtn.fadeIn(200);
} else {
scrollBtn.fadeOut(200);
}
});
scrollBtn.click(function() {
$('body,html').animate({scrollTop: 0}, 600);
return false;
});
});
$('body').append('<div id="backTop"><i class="icon-arrow-up"></i></div>');
</script>
      <?php
   }
   else
   {
      ?>
<script>$(".attachment-thumbnail.thumb").fancybox({openEffect:'none',closeEffect:'none',padding:0,aspectRatio:true,beforeShow:function(){var alt=this.element.find('img').attr('alt');this.inner.find('img').attr('alt',alt);this.title=alt;}});$("a[href$='.jpg'],a[href$='.png'],a[href$='.gif']").attr('rel','attachment').fancybox();jQuery(".interactive .thumbs-inner img").each(function(){jQuery(this).removeAttr("width");jQuery(this).removeAttr("height");});$('aside.sidebar .dxitems').css('display','none');$('.dxitem a, .dxitem-left a').click(function(){$.ajax({url:$(this).attr('data-url'),async:false});return true;});window.___gcfg={lang:'id'};(function(){var po=document.createElement('script');po.type='text/javascript';po.async=true;po.src='https://apis.google.com/js/platform.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(po,s);})();</script>
      <?php
   }
}

add_action('wp_footer', 'custom_infinite_scroll_js', 100);

if(!function_exists('agoraclx_content_nav')) :

   function agoraclx_content_nav ($html_id)
   {
      global $wp_query;
      $html_id = esc_attr($html_id);
      if($wp_query->max_num_pages > 1) :
         ?>
<nav id="<?php echo $html_id; ?>" class="navigation" role="navigation" style="display:none;">
<h3 class="assistive-text"><?php _e('Post navigation'); ?></h3>
<div class="nav-previous pull-left"><?php next_posts_link('<span class="meta-nav">&larr;</span> Older posts'); ?></div>
<div class="nav-next pull-right"><?php previous_posts_link('Newer posts <span class="meta-nav">&rarr;</span>'); ?></div>
</nav>
         <?php
      endif;
   }

endif;

/**
 *
 * @global int $paged
 * @global type $wp_query
 * @param type $pages
 * @param type $range
 *
 * function popular_pagination ($pages = '', $range = 2)
  {
  $showitems = ($range * 2) + 1;

  global $paged;
  if(empty($paged))
  $paged = 1;

  if($pages == '')
  {
  global $wp_query;
  $pages = $wp_query->max_num_pages;
  if(!$pages)
  {
  $pages = 1;
  }
  }

  if(1 != $pages)
  {
  echo "<div class='pagination'>";
  if($paged > 2 && $paged > $range + 1 && $showitems < $pages)
  echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
  if($paged > 1 && $showitems < $pages)
  echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

  for($i = 1; $i <= $pages; $i++)
  {
  if(1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems ))
  {
  echo ($paged == $i) ? "<span class='current'>".$i."</span>" : "<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
  }
  }

  if($paged < $pages && $showitems < $pages)
  echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";
  if($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
  echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
  echo "</div>";
  }
  }
 *
 */
//=======================================
function wps_admin_bar ()
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

add_action('wp_before_admin_bar_render', 'wps_admin_bar');

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'agoraclxgallery_shortcode');

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function agoraclxgallery_shortcode ($attr)
{
   $post = get_post();

   static $instance = 0;
   $instance++;

   if(!empty($attr['ids']))
   {
      // 'ids' is explicitly ordered, unless you specify otherwise.
      if(empty($attr['orderby']))
      {
         $attr['orderby'] = 'post__in';
      }
      $attr['include'] = $attr['ids'];
   }

   // Allow plugins/themes to override the default gallery template.
   $output = apply_filters('post_gallery', '', $attr);
   if($output != '')
   {
      return $output;
   }

   // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
   if(isset($attr['orderby']))
   {
      $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
      if(!$attr['orderby'])
      {
         unset($attr['orderby']);
      }
   }

   extract(shortcode_atts(array (
       'order'      => 'ASC',
       'orderby'    => 'menu_order ID',
       'id'         => $post->ID,
       'itemtag'    => '',
       'icontag'    => '',
       'captiontag' => '',
       'columns'    => '',
       'size'       => 'thumbnail thumb',
       'include'    => '',
       'exclude'    => ''
           ), $attr));

   $id = intval($id);
   if('RAND' == $order)
   {
      $orderby = 'RAND';
   }

   if(!empty($include))
   {
      $_attachments = get_posts(array (
          'include'        => $include,
          'post_status'    => 'inherit',
          'post_type'      => 'attachment',
          'post_mime_type' => 'image',
          'order'          => $order,
          'orderby'        => $orderby
      ));

      $attachments = array ();
      foreach($_attachments as $key => $val)
      {
         $attachments[$val->ID] = $_attachments[$key];
      }
   }
   elseif(!empty($exclude))
   {
      $attachments = get_children(array (
          'post_parent'    => $id,
          'exclude'        => $exclude,
          'post_status'    => 'inherit',
          'post_type'      => 'attachment',
          'post_mime_type' => 'image',
          'order'          => $order,
          'orderby'        => $orderby)
      );
   }
   else
   {
      $attachments = get_children(array (
          'post_parent'    => $id,
          'post_status'    => 'inherit',
          'post_type'      => 'attachment',
          'post_mime_type' => 'image',
          'order'          => $order,
          'orderby'        => $orderby));
   }

   if(empty($attachments))
   {
      return '';
   }

   if(is_feed())
   {
      $output = "\n";
      foreach($attachments as $att_id => $attachment)
      {
         $output .= wp_get_attachment_link($att_id, $size, true)."\n";
      }
      return $output;
   }

   $itemtag       = tag_escape($itemtag);
   $captiontag    = tag_escape($captiontag);
   $icontag       = tag_escape($icontag);
   $valid_tags    = wp_kses_allowed_html('post');
   $selector      = "gallery-{$instance}";
   $gallery_style = $gallery_div   = '';
   $size_class    = sanitize_html_class($size);
   $gallery_div   = "<div class='popout'><section class='gallery interactive width-exempt no-close show-info' data-id='".get_the_title()."'><header class='gallery-header'><h1 class='title'>Gallery ".get_the_title()."</h1></header>\n
   <div class='content'><div class='gallery-content'><div class='slide-container'><div class='slides transitional'><div class='slide' style='width: 850px; height: 500px'></div></div>\n
   <div class='thumbs transparent'><div class='thumbs-inner'>";
   $output        = apply_filters('gallery_style', $gallery_style."\n\t\t".$gallery_div);

   $i = 0;
   foreach($attachments as $id => $attachment)
   {
      $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
      $output .= "$link";
      if($captiontag && trim($attachment->post_excerpt))
      {
         $output .= "<div class='meta'>
				 <div class='caption'>
				".wptexturize($attachment->post_excerpt)."
				</div></div>";
      }
   }

   $output .= "</div></div></div></div></section></div>";

   return $output;
}

function custom_login ()
{
   echo '<style>
body.login { margin:0; padding:0;background: url("'.get_template_directory_uri().'/images/body.jpg") repeat-x scroll center top transparent; }
#login { width: 550px;}
.login h1 a {
background: url("'.get_template_directory_uri().'/images/404.jpg") no-repeat scroll -155px -7px transparent;
display: block;
height: 242px;
margin: 0 auto;
padding-bottom: 15px;
text-indent: -9999px;
width: 562px;}
.login #nav, .login #backtoblog {
text-shadow: #c0c0c0 0 1px 0;
margin: 0 0 0 16px;
padding: 16px 16px 0;}
</style>';
}

add_action('login_head', 'custom_login');

function change_wp_login_url ()
{
   return esc_url(home_url('/'));
}

add_filter('login_headerurl', 'change_wp_login_url');

function change_wp_login_title ()
{
   return 'Powered by Nolgraphic.com';
}

add_filter('login_headertitle', 'change_wp_login_title');

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyeleven_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
//function auto_excerpt_more( $more ) {
//	return ' <a href="'. esc_url( get_permalink() ) . '">&hellip; Baca Selanjutnya &rarr;</a>';
//}
//add_filter( 'excerpt_more', 'auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
//function custom_excerpt_more( $output ) {
//	if ( has_excerpt() && ! is_attachment() ) {
//		$output .= auto_excerpt_more($more);
//	}
//	return $output;
//}
//add_filter( 'get_the_excerpt', 'custom_excerpt_more' );


include_files_in_dir("/acme/");



add_action('wp_scheduled_delete', 'delete_expired_db_transients');

/*
 * Delete expired db trancience
 */

function delete_expired_db_transients ()
{

   global $wpdb, $_wp_using_ext_object_cache;

   if($_wp_using_ext_object_cache)
   {
      return;
   }

   $time    = isset($_SERVER['REQUEST_TIME']) ? (int) $_SERVER['REQUEST_TIME'] : time();
   $expired = $wpdb->get_col("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout%' AND option_value < {$time};");

   foreach($expired as $transient)
   {

      $key = str_replace('_transient_timeout_', '', $transient);
      delete_transient($key);
   }
}