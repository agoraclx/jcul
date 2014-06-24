<?php get_header(); ?>
<div class="row is_white">
  <section class="nine columns">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
          <div class="post-content">
            <?php the_content(); ?>
          </div>
        </article>
        <?php
      endwhile;
    else : endif;
    ?>
  </section>
  <?php get_sidebar(); ?>
</div>
<?php get_footer();