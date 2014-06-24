<?php
if(false === ( $special_query_results = get_transient('special_query_results') ))
{
// It wasn't there, so regenerate the data and save the transient
$randargs              = array (
'orderby'     => 'rand',
'numberposts' => 100
);
$special_query_results = get_posts($randargs);
   set_transient('special_query_results', $special_query_results, 60*60*12);
}
$randomposts = get_transient('special_query_results');
$randkey     = array_rand($randomposts, 12 );

for($index = 0; $index < 12; $index++)
{
$runposts[$index] = $randomposts[$randkey[$index]];
}

global $post;
$n = 1;
foreach($runposts as $post) : setup_postdata($post);
?>
<div class="article-container sharable">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded">
<?php /* ?>  <img src="<?php echo agoraclx_featured_img_url('large'); ?>" /><?php */ ?>
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
<a class="article-category" href="<?php echo $url; ?>" title="<?php echo $name; ?>"><?php echo $name; ?> </a>
<?php endforeach; ?>
<h1 class="article-title">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
</h1>
<div class="article-byline"><?php the_author(); ?></div>
<p class="article-excerpt"><?php echo strip_tags(get_the_excerpt('')); ?></p>
</div>
<footer>
<time class="article-date" datetime="<?php echo get_the_date('c'); ?>"><?php echo nicetime(get_the_date()); ?></time>
</footer>
</div>
</article>
</div>
<?php
$n++;
endforeach;