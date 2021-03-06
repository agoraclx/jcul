<?php
/*
  Template Name: Full-Width
 */
get_header(); ?>
<div class="row is_white">
  <section class="twelve columns">
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
</div>
<?php get_footer();