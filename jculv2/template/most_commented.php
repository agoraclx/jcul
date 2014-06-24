<?php
global $wpdb;
$sql      = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url, SUBSTRING(comment_content,1,30) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 10";
$comments = $wpdb->get_results($sql);
foreach($comments as $comment):
   ?>
<div class="article-container sharable">
<article class="post short" id="post-<?php echo $comment->ID; ?>">
<div class="article-img-container">
<a data-turbo-target="post-slider" href="<?php echo get_permalink($comment->ID)."#comment-".$comment->comment_ID ?>"><span class="_ppf loaded">
<?php
//echo get_the_post_thumbnail($comment->ID, 'large');
$large_image_url = wp_get_attachment_image_src(get_post_thumbnail_id($comment->ID), 'large');
$attr            = array (
'src'           => get_template_directory_uri().'/images/null.gif',
'class'         => "lazy",
'alt'           => trim(strip_tags($comment->post_excerpt)),
'title'         => trim(strip_tags($comment->post_title)),
'data-original' => $large_image_url[0]
);
echo get_the_post_thumbnail($comment->ID, 'large', $attr);
?>
</span></a>
</div>
<div class="article-content-wrapper">
<div class="article-content">
<a class="article-category" href="<?php echo get_permalink($comment->ID)."#comment-".$comment->comment_ID ?>" title="title">oleh <?php echo strip_tags($comment->comment_author); ?> </a>
<h1 class="article-title"><a data-turbo-target="post-slider" href="<?php echo get_permalink($comment->ID)."#comment-".$comment->comment_ID ?>"><?php echo $comment->post_title; ?></a></h1>
<div class="article-byline"><?php echo strip_tags($comment->comment_author); ?></div>
<p class="article-excerpt"><?php echo strip_tags($comment->com_excerpt); ?></p>
</div>
</div>
</article>
</div>
   <?php
endforeach;