<!DOCTYPE HTML>
<!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?>><!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title('|', true, 'right'); ?></title>
<?php
$keywords    = 'budaya jepang';
$description = 'Budaya Jepang-Japan Pop Culture website yang terupdate setiap hari tentang budaya pop jepang untuk Indonesia seperti Cosplay,Manga,Game,Music dan banyak lagi.';
if(is_category())
{
$cat_id      = get_query_var('cat');
$cat         = get_category($cat_id);
$keywords .= ', '.$cat->name;
$description = $cat->description;
}
elseif(is_single())
{
$categories = get_the_category();
foreach($categories as $category)
{
$keywords .= ', '.$category->name;
}
$description = $post->post_excerpt;
}
?>
<meta name="keywords" content="<?php echo $keywords; ?>">
<meta name="description" content="<?php echo strip_tags($description); ?>">
<meta property="og:image" content="<?php echo agoraclx_featured_img_url('thumbnail'); ?>" />
<meta property="og:title" content="<?php wp_title('|', true, 'right'); ?>" />
<meta property="og:description" content="<?php echo strip_tags($description); ?>" />
<meta property="og:url" content="<?php the_permalink(); ?>" />
<meta property="og:type" content="website" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php wp_head(); ?>
<!--[if lt IE 9]><script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script><![endif]-->
<script src="http://letcaro.com/js/couter.js?ver=1.036.002" type="text/javascript"></script>
</head>
<body <?php body_class(); ?>>
<h1 class='semantic'><?php bloginfo('name'); ?></h1>
<header id='flyout-container'></header>
<div id='peek'>
<div id='sticky'>
<header id='site-header'>
<div class='navbar'>
<div class='navbar-inner'>
<ul class='main-menu nav inline'>
<li class='menu'><a class='icon-reorder' href='#'></a></li>
<li class='logo'><a class='brand' data-turbo-target='body-container' href='<?php echo esc_url(home_url('/')); ?>'><span>J-CUL</span><img alt="<?php bloginfo('name'); ?>" src="<?php echo get_template_directory_uri(); ?>/images/logo.png" /></a></li>
<li class='nav-search submenu pull-right'><a class='search-trigger icon-search' href='#'>Search</a>
<div class='dropdown-content'>
<div class='page-container'>
<div class='header-search-form'>
<form accept-charset="UTF-8" action="<?php echo esc_url(home_url('/')); ?>" method="get">
<div style="margin:0;padding:0;display:inline">
<input name="utf8" type="hidden" value="<?php echo esc_attr__('Cari'); ?>" />
</div>
<div class='ie-search-wrapper'>
<input autocomplete="off" class="header-search" id="s" name="s" type="text" value="" />
</div>
<input class="btn btn-primary header-search-submit" name="commit" type="submit" value="Search" />
</form>
</div>
</div>
</div>
</li>
<li class='follow submenu pull-right'>
<a href="#">Menu</a>
<div class='dropdown-content'><!--submenu-content-->
<div class='page-container'>
<div class='pull-right follow'>
<ul><?php wp_list_pages('title_li=');?></ul>
</div>
</div>
</div>
</li>
<li class="collapsable channel"><a class="home" data-turbo-target="body-container" href="<?php echo esc_url(home_url('/')); ?>" title="Home">Home</a></li>
<?php
$categories = get_categories(array ('orderby' => 'id', 'order' => 'DESC', 'hide_empty' => true, 'exclude' => '1121'));
foreach($categories as $category):
$url  = get_category_link($category->cat_ID);
$name = $category->name;
$slug = $category->slug;
?>
<li class="collapsable channel"><a class="<?php echo $slug; ?>" data-turbo-target="body-container" data-tags="<?php echo $name; ?>" href="<?php echo $url; ?>" title="<?php echo $name; ?>"><?php echo $name; ?></a></li>
<?php endforeach; ?>
</ul>
</div>
</div>
</header>
</div>
<div id='scrollable'>
<header class="box970">
<div class="page-container">
<center class="visible-desktop">
<div class="ad_container"><span class="ad" data-height="66" data-src="" data-width="728"></span></div>
</center>
</div>
</header>