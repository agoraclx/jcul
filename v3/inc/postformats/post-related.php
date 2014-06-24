<!-- Start Related Posts -->
<?php
global $post;
$postId = $post->ID;
$query  = get_blog_posts_related_by_category($post->ID);
if($query->have_posts()) :
  ?>
  <div class="headline hide-on-print"><h2>Artikel lainnya</h2></div>
  <div class="row relatedposts hide-on-print">
    <?php while($query->have_posts()) : $query->the_post(); ?>
      <div class="four columns">
        <article class="post" id="post-<?php the_ID(); ?>">
          <div class="post-gallery">
            <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('recent'); ?></a>
            <?php echo thb_DisplayImageTag(get_the_ID()); ?>
          </div>
          <div class="post-title"><h4><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4></div>
        </article>
      </div>
    <?php endwhile; ?>
  </div>
  <?php
endif;
wp_reset_query();
