<?php get_header(); ?>
<div class="row is_white">
  <section class="fullwidth archivepage twelve columns">
    <div class="row masonry" data-columns="4">
      <?php
      global $query_string; // required
      $args  = array_merge($wp_query->query_vars, array('posts_per_page' => '12'));
      $query = new WP_Query($args);
      if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
          ?>
          <article class="post item three columns">
            <div class="post-gallery">
              <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('recent'); ?></a>
              <?php echo thb_DisplayImageTag(get_the_ID()); ?>
            </div>
            <div class="post-title">
              <h4><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h4>
            </div>
            <div class="post-content">
              <p><?php echo ShortenText(get_the_excerpt(), 70); ?></p>
              <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
            </div>
          </article>
          <?php
        endwhile;
      else:
        ?>
        <article>
          <?php _e('Please select tags from your Theme Options Page', THB_THEME_NAME); ?>
        </article>
      <?php endif; ?>
    </div>
    <?php theme_pagination($query->max_num_pages, 3); ?>
  </section>
</div>
<?php get_footer();