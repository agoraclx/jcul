<?php get_header(); ?>
<div class="row is_white">
  <section class="nine columns">
    <?php /*
    if(!is_paged())
    {
      ?>
      <div id="slider" class="flex slider flex-start categoryslider" data-bullets="true" data-controls="true">
        <ul class="slides">
          <?php
          global $query_string; // required
          $args  = array_merge($wp_query->query_vars, array('posts_per_page' => '5'));
          $query = new WP_Query($args);

          if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
              ?>
              <li class="post">
                <div class="post-gallery">
                  <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('category-slider'); ?></a>
                  <div class="overlay"></div>
                </div>
                <div class="post-title">
                  <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                  <?php echo thb_DisplayPostMeta(true, true, true, true); ?>
                </div>
              </li>
              <?php
            endwhile;
          else: endif;
          ?>
        </ul>
      </div>
    <?php } */ ?>
    <section id="recentnews">
      <?php
//      global $query_string; // required
//      $offset = (is_paged() ? '12' : '5');
//      $args   = array_merge($wp_query->query_vars, array('posts_per_page' => '7', 'offset' => $offset));
//      $args   = array('posts_per_page' => '7');
//      $query  = new WP_Query($args);
//      if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
      if(have_posts()) : while(have_posts()) : the_post();
          ?>
          <article class="post">
            <div class="row">
              <div class="four columns">
                <div class="post-gallery">
                  <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('widget'); ?></a>
                  <?php echo thb_DisplayImageTag(get_the_ID()); ?>
                </div>
              </div>
              <div class="eight columns">
                <div class="post-title">
                  <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                </div>
                <div class="post-content">
                  <p><?php echo ShortenText(get_the_excerpt(), 250); ?></p>
                  <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
                </div>
              </div>
            </div>
          </article>
          <?php
        endwhile;
      else: endif;
      ?>
      <?php theme_pagination($query->max_num_pages, 3, true); ?>
    </section>
  </section>
  <?php get_sidebar('category'); ?>
</div>
<?php
get_footer();
