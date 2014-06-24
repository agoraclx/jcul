<?php
if(!is_404())
{
  ?>
  <div class="row is_white" id="breakingcontainer">
    <div class="twelve columns">
      <div id="breaking" <?php
      if(is_page_template('template-home-style2.php') || is_page_template('template-home-style3.php'))
      {
        ?>class="margin"<?php } ?>>
        <div class="row">
          <h3><?php _e('Nyūsu sokuhō', THB_THEME_NAME) ?></h3>
          <div class="marquee-wrapper">
            <div class="marquee" id="marquee">
              <?php
              $sticky = get_option('sticky_posts');
              $args   = [
                'orderby'        => 'menu_order',
                'order'          => 'DESC',
                'posts_per_page' => '100',
                'post__in'       => $sticky
                // 'tag__in'        => ot_get_option('breaking_news')
              ];
              $query  = new WP_Query($args);
              if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
                  $format = get_post_format();
                  ?>
                  <div class="item">
                    <span class="dot">&bull;</span>
                    <?php
                    if($format == 'image' || $format == 'gallery' || $format == 'video')
                    {
                      ?>
                      <span class="type"><?php echo $format; ?></span>
                    <?php } ?>
                    <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                  </div>
                  <?php
                endwhile;
              else:
                ?>
                <div class="item">&bull; <?php _e('No Posts Found', THB_THEME_NAME); ?></div>
              <?php
              endif;
              wp_reset_query();
              ?>
            </div>
          </div>
          <a class="close" href="#" onclick="jQuery('#breaking').animate({height: 0}); return false;"><i class="fa fa-times-circle"></i></a>
        </div>
      </div>
    </div>
  </div>
  <?php
}