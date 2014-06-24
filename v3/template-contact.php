<?php
/*
  Template Name: Contact
 */
get_header(); ?>
<div class="row">
  <section class="nine columns">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">
          <div class="post-gallery cf">
            <div id="contact-map" class="google_map twelve columns" data-map-zoom="<?php echo ot_get_option('contact_zoom', 15); ?>" data-map-center-lat="<?php echo ot_get_option('map_center_lat', '53.381129'); ?>" data-map-center-long="<?php echo ot_get_option('map_center_long', '-1.470085'); ?>" data-pin-info="<?php echo ot_get_option('map_pin_info'); ?>" data-pin-image="<?php echo ot_get_option('map_pin_image', get_template_directory_uri().'/assets/img/pin.png'); ?>"></div>
          </div>
          <div class="post-content">
            <?php
            if(is_search())
            {
              the_excerpt();
            }
            else
            {
              the_content('<span>Continue Reading &rarr;</span>');
            }
            if(is_single())
            {
              wp_link_pages();
            }
            ?>
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