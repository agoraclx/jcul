<?php

function load_more_posts()
{
  $count = $_POST['count'];
  $page  = $_POST['page'];

  $offset = (($page - 1) * $count) + 10;

  $args = [
    'offset'              => $offset,
    'posts_per_page'      => $count,
    'orderby'             => 'post_date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => '1'
  ];

  $query = new WP_Query($args);

  if($query->have_posts())
  {
    while($query->have_posts())
    {
      $query->the_post();
      ?>
      <article class="post agoraclx_1">
        <div class="row">
          <div class="five columns">
            <div class="post-gallery">
              <?php
              the_post_thumbnail('recent');
              echo thb_DisplayImageTag(get_the_ID());
              ?>
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
    }
  }

  die();
}

function load_more_posts_type2()
{
  $count = $_POST['count'];
  $page  = $_POST['page'];

  $offset = (($page - 1) * $count) + 14;

  $args = [
    'offset'              => $offset,
    'posts_per_page'      => $count,
    'orderby'             => 'post_date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => '1'
  ];

  $query = new WP_Query($args);

  if($query->have_posts())
  {
    while($query->have_posts())
    {
      $query->the_post();
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
              <aside><?php echo thb_DisplaySingleCategory(false); ?></aside>
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
    }
  }

  die();
}