<?php
$sticky = get_option('sticky_posts');
//if(!empty($sticky)) :
$featured_args = array (
'showposts'        => 15,
'post__in'         => $sticky,
'orderby'          => 'rand',
'caller_get_posts' => 1
);
$featured      = new WP_Query($featured_args);
if($featured->have_posts()) :
while($featured->have_posts()) : $featured->the_post();
?>
<div class="article-container sharable">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded">
<img alt="<?php the_title(); ?>" class="lazy microcontent" src="<?php echo get_template_directory_uri() ?>/images/null.gif" data-original="<?php echo agoraclx_featured_img_url('large'); ?>" />
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
<a style="color:red" class="article-category" href="<?php echo $url; ?>" title="<?php echo $name; ?>"><?php echo $name; ?> </a>
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
//endif;
wp_reset_postdata();