<?php get_header(); ?>
<div class='posts index' id='body-container'>
<div id='main'>
<div class='page-container'>
<div id='body'>
<div id='body-content'>
<div class='fixable-wrapper' id='column-headers'>
<div class='column-headers'>
<div class='headers'>
<div class='header header3'><a id="rising" data-short='berita terbaru' href='#'><em>BERITA TERBARU</em> </a></div>
<div class='header header2'><a id="new" data-short='terkomentari' href='#'><em>komentar terkini</em> </a></div>
<div class='header header1'><a style="background: #D11010;" id="hot" data-short='Staff Picks' href='#'><em>Be Social</em> </a></div>
</div>
</div>
</div>
<div class='slide-window'>
<div class='flex-box columns'>
<section class='column box-cell hot-stories waypoint column3' data-fixselector='header.fixable' data-key='terbaru' id='column-new'>
<h1 class='semantic'>NEW ARTIKEL</h1>
<div class="column-content" id="infinite">
<?php
query_posts(array (
"post__not_in" => get_option("sticky_posts"),
'paged'        => get_query_var('paged')
));
if(have_posts()) :
while(have_posts()) : the_post();
?>
<div class="article-container sharable article bignew">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded"><img src="<?php echo agoraclx_featured_img_url('large'); ?>"/></span>
</a>
</div>
<div class="article-content-wrapper">
<div class="article-content">
<a class="article-category"><?php echo get_the_date(); ?></a>
<?php
$categories = get_the_category();
foreach($categories as $category) :
$name = $category->name;
$url  = get_category_link($category->cat_ID);
?>
<a class="article-category" href="<?php echo $url; ?>" title="<?php echo $name; ?>"> <span style="color:red;"><?php echo $name; ?></span> </a>
<?php endforeach; ?>
<h1 class="article-title" id="h1big">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h1>
<div class="article-byline"><?php the_author(); ?></div>
<p class="article-excerpt"><?php echo strip_tags(get_the_excerpt()); ?> <a href="<?php echo esc_url(get_permalink()); ?>">&hellip; Baca Selanjutnya &rarr;</a></p>
</div>
<footer>
<div class="article-shares-stub">
<a class="num" href="<?php the_permalink(); ?>">
<b><?php echo get_the_date(); ?></b>
</a>
</div>
</footer>
</div>
</article>
</div>
<?php
endwhile;
agoraclx_content_nav('nav-below');
else :
wp_reset_postdata();
?>
<div class="article-container sharable">
<article class="post short" id="post-404">
<div class="article-content-wrapper">
<div class="article-content">
<h1 class="article-title"><a data-turbo-target="post-slider" href="">Tidak ada artikel silahkan cari di search box brow</a></h1>
</div>
</div>
</article>
</div>
<?php endif; ?>
</div>
</section>
<section class='column box-cell new-stories waypoint column2' data-fixselector='header.fixable' data-key='terkomentari' id='column-new'>
<h1 class='semantic'>Komentar terkini</h1>
<div class="column-content middle">
<?php
get_template_part('template/most_commented');
?>
</div>
<?php get_template_part('template/widget/middle1'); ?>
<?php get_template_part('template/widget/middle2'); //id="infini_mid"?>
<div class="column-content middle" style="margin:20px 0 0 0;border-top: 1px solid #e3e3e3;padding: 0;">
<div class="header header2 terkomentari" style="display: block;margin: 0px 20px;font-weight: bold;font-size: 1.5rem; line-height: 40px;padding: 0 0 30px;">
<a data-short="TERKOMENTARI"><p style="color:red;">POPULAR</p></a>
</div>
<?php get_template_part('template/contentMiddle'); ?>
</div>
</section>
<section class='column box-cell big-stories waypoint column1' data-fixselector='header.fixable' data-key='Artikel Pilihan' id='column-hot'>
<h1 class='semantic'>Staff pick</h1>
<div class="column-content">
<?php
get_template_part('template/beSocial');
echo '<div class="header leak" style="background: #7fc04c;font-size: 1.5rem;font-weight: bold;color: white;text-transform: uppercase;line-height: 40px;padding: 0 20px;margin: 0 20px 25px;">PR</div>';
get_template_part('template/iklan');
get_template_part('template/widget/right1');
echo '<div class="header leak" style="background: #7fc04c;font-size: 1.5rem;font-weight: bold;color: white;text-transform: uppercase;line-height: 40px;padding: 0 20px;margin: 0 20px 30px;">Staff pick</div>';
$sticky = get_option('sticky_posts');
if(!empty($sticky)) :
$featured_args = array (
'showposts'        => 15,
'post__in'         => $sticky,
'orderby'          => 'rand',
'caller_get_posts' => 1
);

$featured = new WP_Query($featured_args);
if($featured->have_posts()) :
while($featured->have_posts()) : $featured->the_post();
?>
<div class="article-container sharable">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded">
<img class="lazy" src="<?php echo get_template_directory_uri() ?>/images/null.gif" data-original="<?php echo agoraclx_featured_img_url('large'); ?>" />
</span>
</a>
</div>
<div class="article-content-wrapper">
<div class="article-content">
<a class="article-category"><?php echo get_the_date(); ?></a>
<?php
$categories = get_the_category();
foreach($categories as $category) :
$name = $category->name;
$url  = get_category_link($category->cat_ID);
?>
<a class="article-category" href="<?php echo $url; ?>" title="<?php echo $name; ?>"> <span style="color:red;"><?php echo $name; ?></span> </a>
<?php endforeach; ?>
<h1 class="article-title">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h1>
<div class="article-byline"><?php the_author(); ?></div>
<p class="article-excerpt"><?php echo strip_tags(get_the_excerpt('')); ?></p>
</div>
</div>
</article>
</div>
<?php
endwhile;
endif;
endif;
wp_reset_postdata();
get_template_part('template/widget/right2');
?>
</div>
</section>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php
get_footer();