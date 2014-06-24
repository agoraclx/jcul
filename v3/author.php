<?php get_header(); ?>
<div class="row is_white">
  <section class="nine columns">
    <section id="author-page">
      <div class="row">
        <div class="two columns">
          <?php echo admin_avatar(get_the_author_meta('ID'), '124'); ?>
        </div>
        <div class="ten columns">
          <strong><?php the_author_posts_link(); ?></strong>
          <p><?php the_author_meta('description'); ?></p>
          <?php
          if(get_the_author_meta('url') != '')
          {
            ?>
            <a href="<?php echo get_the_author_meta('url'); ?>" class="boxed-icon rounded"><i class="fa fa-link icon-1x"></i></a>
            <?php
          }

          if(get_the_author_meta('twitter') != '')
          {
            ?>
            <a href="<?php echo get_the_author_meta('twitter'); ?>" class="boxed-icon rounded twitter"><i class="fa fa-twitter icon-1x"></i></a>
            <?php
          }

          if(get_the_author_meta('facebook') != '')
          {
            ?>
            <a href="<?php echo get_the_author_meta('facebook'); ?>" class="boxed-icon rounded facebook"><i class="fa fa-facebook icon-1x"></i></a>
            <?php
          }

          if(get_the_author_meta('googleplus') != '')
          {
            ?>
            <a href="<?php echo get_the_author_meta('googleplus'); ?>" class="boxed-icon rounded google-plus"><i class="fa fa-google-plus icon-1x"></i></a>
          <?php } ?>
        </div>
      </div>
    </section>
    <section id="recentnews">
      <?php
      $args  = array(
        'posts_per_page'      => '5',
        'ignore_sticky_posts' => '1'
      );
      $query = new WP_Query($args);
      if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
          ?>
          <article class="post">
            <div class="row">
              <div class="four columns">
                <div class="post-gallery">
                  <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('recent'); ?></a>
                </div>
              </div>
              <div class="eight columns">
                <div class="post-title">
                  <aside><?php echo thb_DisplaySingleCategory(false); ?></aside>
                  <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                </div>
                <div class="post-content">
                  <p><?php echo ShortenText(get_the_excerpt(), 200); ?></p>
                  <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
                </div>
              </div>
            </div>
          </article>
          <?php
        endwhile;
        theme_pagination();
      else:
        ?>
        <article>
          <?php _e('Please select tags from your Theme Options Page', THB_THEME_NAME); ?>
        </article>
      <?php endif; ?>
    </section>
  </section>
  <?php get_sidebar('author'); ?>
</div>
<?php get_footer();
