<?php
/*
  Template Name: Home Page - Style 1
 */
get_header();

//plugins shits
include_once( ABSPATH.'wp-admin/includes/plugin.php' );
?>
<div class="row is_white">
  <section class="seven columns">
    <section id="recentnews">
      <div class="headline"><h2>Saikin no nyūsu</h2></div>

      <?php
      //**********plugin shits *******************
      if(is_plugin_active("nolrotater/index.php") && function_exists("run_AgoRacLX"))
      {
        echo run_AgoRacLX();
      }

      $args  = [
        'posts_per_page'      => '5',
        'ignore_sticky_posts' => '1'
      ];
      $query = new WP_Query($args);
      if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
          ?>
          <article class="post">
            <div class="row">
              <div class="five columns">
                <div class="post-gallery">
                  <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('recent'); ?></a>
                  <?php echo thb_DisplayImageTag(get_the_ID()); ?>
                </div>
              </div>
              <div class="seven columns">
                <div class="post-title">
                  <aside><?php echo thb_DisplaySingleCategory(false); ?></aside>
                  <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                </div>
                <div class="post-content">
                  <p><?php echo ShortenText(get_the_excerpt(), 150); ?></p>
                  <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
                </div>
              </div>
            </div>
          </article>
          <?php
        endwhile;
      else:
        ?>
        <article>
          <?php _e('Sumimasen.......', THB_THEME_NAME); ?>
        </article>
      <?php endif; ?>
      <a title="Load More" id="loadmore" href="#" data-loading="Chotto Matte ne..." data-nomore="Nain desu. Sumimasen..." data-count="5" data-action="thb_ajax_home">sarani yomikomu</a>
    </section>
    <section class="categorynews">
      <?php
      $categories = thb_HomePageCategories();

      foreach($categories as $category)
      {
        $color = GetCategoryColor($category);
        ?>
        <div class="categoryholder cf">
          <div class="categoryheadline" style="border-color:<?php echo $color; ?>">
            <h2 style="color:<?php echo $color; ?>"><?php echo get_category($category)->name; ?></h2>
            <span><a href="<?php echo get_category_link($category); ?>" style="color:<?php echo $color; ?>" title="lihat semua artikel"><i class="icon-long-arrow-right"></i> subeteno kijiwo yomu</a></span>
          </div>
          <?php
          $args  = [
            'posts_per_page' => '5',
            'category__in'   => $category,
            'no_found_rows'  => true
          ];
          $query = new WP_Query($args);
          $i     = 0;
          ?>
          <div class="row">
            <?php
            if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
                if($i == 0)
                {
                  ?>
                  <article class="post five columns">
                    <div class="post-gallery">
                      <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('recent'); ?></a>
                      <?php echo thb_DisplayImageTag(get_the_ID()); ?>
                    </div>
                    <div class="post-title">
                      <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                    </div>
                    <div class="post-content">
                      <p><?php echo ShortenText(get_the_excerpt(), 150); ?></p>
                      <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
                    </div>
                  </article>
                  <?php
                }
                else
                {
                  ?>
                  <div class="seven columns">
                    <article class="post cf side">
                      <div class="post-gallery mobile-one left">
                        <a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail(); ?></a>
                      </div>
                      <div class="post-title mobile-three left">
                        <h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                        <?php echo thb_DisplayPostMeta(true, true, true, false); ?>
                      </div>
                    </article>
                  </div>
                  <?php
                }
                $i++;
              endwhile;
            else: endif;
            ?>
          </div>
        </div>
      <?php } ?>
    </section>
  </section>
  <?php
  get_sidebar('left');
  get_sidebar('right');
  ?>
</div>
<?php
get_footer();
