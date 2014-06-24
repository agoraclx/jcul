<?php
get_header();
if(have_posts()) :
while(have_posts()) : the_post();
?>
<div class='posts show' id='body-container'>
<div id='main'>
<div class='page-container'>
<div id="body">
<hgroup class="page-header channel" data-channel="<?php single_category(); ?>" id="post-head">
<h2><?php single_category(); ?></h2>
<div class="follow-channel">
<div class="follow-buttons">
<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FJapan-Pop-Culture%2F421141184598981&amp;width=115&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=184062871755685" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:115px; height:21px;" allowTransparency="true"></iframe>
<a class='twitter-follow-button' data-dnt='true' data-show-count='true' data-show-screen-name='false' href='https://twitter.com/JepangCulture'> Follow @JepangCulture </a>
<div class="g-plusone" data-size="medium" data-href="https://plus.google.com/u/0/111448305303480341179"></div>
</div>
</div>
</hgroup>
<div class="flex-box">
<div class="box-cell">
<div id="post-content">
<div id="post-slider">
<article class="full post" data-channel="<?php single_category() ?>" data-id="<?php the_ID(); ?>" data-subchannels="" data-topic="<?php the_title(); ?>" id="story">
<header class="article-header">
<h1 class="title"><?php the_title(); ?></h1>
<aside class="shares social">
<div class="share-buttons">
<div class="share-button-boxy"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-via="JepangCulture" data-lang="en" data-counturl="<?php the_permalink(); ?>" data-count="vertical">Tweet</a>
<script>!function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (!d.getElementById(id)) {
js = d.createElement(s);
js.id = id;
js.src = "https://platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js, fjs);
}
}(document, "script", "twitter-wjs");</script>
</div>
<div class="share-button-boxy"><iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;width=59&amp;layout=box_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=65&amp;appId=184062871755685" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:59px; height:65px;" allowTransparency="true"></iframe></div>
<div class="share-button-boxy">
<div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div>
</div>
</div>
</aside>
<figure class="article-image">
<div class="microcontent-wrapper">
<?php  ?><img alt="<?php the_title(); ?>" class="microcontent" data-fragment="lead-image" data-image="<?php echo agoraclx_featured_img_url('large'); ?>" data-micro="1" data-url="null" src="<?php echo agoraclx_featured_img_url('large'); ?>"><?php  ?>
</div>
</figure>
<div class="article-info">
<a class="byline" href="<?php printf(esc_url(get_author_posts_url(get_the_author_meta("ID")))); ?>" title="<?php printf(get_the_author()); ?>">
<?php echo admin_avatar($avatar, get_the_author_meta('ID'), 100); ?>
<div class="author_and_date"><span class="author_name"><?php printf('Oleh %s', get_the_author()); ?></span></div>
</a>
</div>
</header>
<section class="article-content">
<?php the_content(); ?>
<?php edit_post_link('Edit nyong', '<p class="edit-link">', '</p>'); ?>
</section>
<footer class="article-topics">
Kategori lainnya: <?php categories_link(); ?>
<aside class="shares social">
<div class="share-buttons">
<div class="share-button-boxy"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-via="JepangCulture" data-lang="en" data-counturl="<?php the_permalink(); ?>" data-count="vertical">Tweet</a>
<script>!function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (!d.getElementById(id)) {
js = d.createElement(s);
js.id = id;
js.src = "https://platform.twitter.com/widgets.js";
fjs.parentNode.insertBefore(js, fjs);
}
}(document, "script", "twitter-wjs");</script>
</div>
<div class="share-button-boxy"><iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;width=59&amp;layout=box_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=65&amp;appId=184062871755685" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:59px; height:65px;" allowTransparency="true"></iframe></div>
<div class="share-button-boxy"><div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div></div>
</div>
</aside>
</footer>
</article>
</div>
<div class="article-comments">
<?php if(post_password_required()) : ?>
<p class="nopassword">This post is password protected. Blalbllabla lalbvlablalblb.</p>
<?php
return;
endif;
?>
<div class="relations">
<h2>Facebook</h2>
<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FJapan-Pop-Culture%2F421141184598981&amp;width=<?php agent(); ?>&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23fff&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: <?php agent(); ?>px; height:258px;" allowTransparency="true"></iframe>
<div id="fb-root"></div>
<script>(function(d, s, id) {
var js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id))
return;
js = d.createElement(s);
js.id = id;
js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="10" data-width="<?php agent(); ?>"></div>
</div>
<?php comments_template('', true); ?>
</div>
</div>
</div>
<?php
endwhile;
endif;
?>
<aside class="box-cell sidebar">
<h1 class="ribbon hot">PR</h1>
<div class="sidebar-inset" id="big-stories">
<?php
get_template_part('template/iklan');
get_template_part('template/widget/right1');
echo '<div class="header leak" style="background: #7fc04c;font-size: 1.5rem;font-weight: bold;color: white;text-transform: uppercase;line-height: 40px;padding: 0 20px;margin: 0 -17px 30px;">Staff pick</div>';
get_template_part('template/sticky');
?>
</div>
</aside>
</div>
<div id="more-in-channel">
<div class="page-header" data-channel="<?php single_category(); ?>">
<h1 class="semantic">Kategori <?php single_category(); ?> lainnya</h1>
</div>
<div class="fixable-wrapper" id="column-headers">
<div class="column-headers">
<div class="headers">
<div class='header header3'><a id="rising" data-short='Kategori <?php single_category(); ?>' href='#'><em>Kategori <?php single_category(); ?></em> </a></div>
<div class='header header2'><a id="new" data-short='Next Artikel' href='#'><em>Next Artikel</em> </a></div>
<div class='header header1'><a id="hot" data-short='artikel terbaru' href='#'><em>artikel terbaru </em> </a></div>
</div>
</div>
</div>
<div class="slide-window">
<div class="flex-box columns">
<section class="column box-cell hot-stories" id="column-rising">
<h1 class="semantic">Kategori <?php single_category(); ?> lainnya</h1>
<div class="column-content" id="infinite">
<?php
if(false === ( $special_query_results = get_transient('special_query_results') ))
{
$randargs              = array (
'orderby'     => 'rand',
'numberposts' => 100,
'category'    => single_category_id(),
);
$special_query_results = get_posts($randargs);
set_transient('special_query_results', $special_query_results, 60 * 60 * 6);
}

$randomposts = get_transient('special_query_results');
$randkey     = array_rand($randomposts, 10);
for($index = 0; $index < 10; $index++)
{
$sixposts[$index] = $randomposts[$randkey[$index]];
}

global $post;
$n = 1;
foreach($sixposts as $post) : setup_postdata($post);
?>
<div class="article-container sharable" id="big-<?php echo $n ?>">
<article class="post short" id="post-<?php the_ID(); ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php the_permalink(); ?>">
<span class="_ppf loaded">
<?php /* ?><img src="<?php echo agoraclx_featured_img_url('large'); ?>" /><?php */ ?>
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
?>
</div>
</section>
<section class="column box-cell new-stories" id="column-new">
<h1 class="semantic">The New Stuff</h1>
<div class="column-content middle">
<?php get_template_part('template/singleRandom68'); ?>
</div>
</section>
<section class="column box-cell big-stories" id="column-hot">
<h1 class="semantic">What's Hot</h1>
<div class="column-content">
<?php get_template_part('template/singleRandom'); ?>
</div>
</section>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php get_footer(); ?>
<?php /* ?>
<script type="text/javascript">var switchTo5x = false;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "364c4c9c-f96a-40e2-bc2f-20129c46667b", doNotHash: false, doNotCopy: false, hashAddressBar: true});</script><?php */