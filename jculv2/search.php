<?php get_header(); ?>
<div class="search search" id="body-container">
<div id="main">
<div class="page-container">
<div id="body">
<div class="flex-box">
<div class="box-cell">
<header class="search-header">
<hgroup class="page-header">
<h1>
<?php printf('Hasil Pencarian: %s', '<em>'.get_search_query().'</em>'); ?>
</h1>
</hgroup>
</header>
<div class="tabs-below">
<div class="searchform tab-content">
<form accept-charset="UTF-8" action="<?php echo esc_url(home_url('/')); ?>" method="get">
<div style="margin:0;padding:0;display:inline">
<input name="utf8" type="hidden" value="<?php echo esc_attr__('Cari'); ?>"></div>
<input id="s" name="s" type="search" value="<?php printf(get_search_query()); ?>">
<input class="btn" name="commit" type="submit" value="Cari">
</form>
</div>
</div>
<div id="search-results">
<div class="story-stream" id="search-posts">
<?php if(have_posts()) : ?>
<?php while(have_posts()) : the_post(); ?>
<div class="article-container sharable">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post_slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded">
<span data-q="true" data-s="<?php echo agoraclx_featured_img_url('large'); ?>" data-z="356x205#"></span><span data-q="(min-resolution: 1.5dppx)" data-s="<?php echo agoraclx_featured_img_url('large'); ?>" data-z="712x410#"></span>
<img src="<?php echo agoraclx_featured_img_url('large'); ?>">
</span>
</a>
</div>
<div class="article-content-wrapper">
<div class="article-content">
<?php
$categories = get_the_category();
foreach($categories as $category) :
$name = $category->name;
$url  = get_category_link($category->cat_ID);
?>
<a class="article-category" href="<?php echo $url; ?>" title="<?php echo $name; ?>"><?php echo $name; ?> </a>
<?php endforeach; ?>
<h1 class="article-title">
<a data-turbo-target="post_slider" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h1>
<div class="article-byline"><?php the_author(); ?></div>
<time class="article-date" datetime="<?php echo get_the_date('c'); ?>"><?php echo nicetime(get_the_date()); ?></time>
<p class="article-excerpt"><?php echo strip_tags(get_the_excerpt('')); ?></p>
</div>
</div>
</article>
</div>
<?php
endwhile;
agoraclx_content_nav( 'nav-below' );
else :
?>
<div class="article-container sharable">
<article class="post short" id="post-404">
<div class="article-img-container">
<a data-turbo-target="post_slider" href="#">
<span class="_ppf loaded">
<img src="<?php echo get_template_directory_uri(); ?>/images/404.jpg">
</span>
</a>
</div>
<div class="article-content-wrapper">
<div class="article-content">
<h1 class="article-title">
<a data-turbo-target="post_slider" href="#">Maaf Silahkan ulangi sekali lagi untuk pencarian "<?php printf(get_search_query()); ?>" karena tidak ketemu di dompet</a>
</h1>
<div class="article-byline"><?php printf(get_search_query()); ?></div>
<p class="article-excerpt">tidak ada mahkluk <?php printf(get_search_query()); ?></p>
</div>
</div>
</article>
</div>
<?php endif; ?>
</div>
</div>
</div>
<div class="box-cell sidebar shadow-left">
<h1 class="ribbon big">Staff Pick</h1>
<div class="sidebar-inset" id="hot-stories">
<?php get_template_part('template/sticky'); ?>
</div>
<h1 class="ribbon hot">Next Artikel</h1>
<div class="sidebar-inset" id="big-stories">
<?php get_template_part('template/singleRandom'); ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php get_footer();