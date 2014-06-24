<?php get_header(); ?>
<div class="row">
<section class="nine columns">
  <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
	  <article <?php post_class('post blog-post'); ?> id="post-<?php the_ID(); ?>">

	    <?php
	      // The following determines what the post format is and shows the correct file accordingly
	      $format = get_post_format();
	      if ($format) {
	      get_template_part( 'inc/postformats/'.$format );
	      } else {
	      get_template_part( 'inc/postformats/standard' );
	      }
	    ?>
	    <div class="post-title">
	    	<aside><?php echo thb_DisplaySingleCategory(true); ?></aside>
	    	<h1><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h1>
	    </div>
	    <aside class="post-meta">
	    	<ul>
	    		<li><?php _e( 'By', THB_THEME_NAME ); ?> <strong><?php the_author_posts_link(); ?></strong></li>
	    		<li>&bull;  &nbsp; <?php echo get_the_date('F j, Y'); ?></li>
	    		<?php if (ot_get_option('disablelike') == 'no') { echo '<li>&bull; &nbsp; '.thb_printLikes(get_the_ID()).'</li>'; } ?>
	    		<li>&bull;  &nbsp; <?php echo get_comments_popup_link('<i class="fa fa-comment-o"></i> 0', '<i class="fa fa-comment-o"></i> 1', '<i class="fa fa-comment-o"></i> %', 'postcommentcount', '<i class="fa fa-comment-o"></i> -'); ?></li>
	    	</ul>
	    </aside>
	    <div class="post-content">
	    	<?php the_content('Read More'); ?>
	    </div>

	  </article>
  <?php endwhile; ?>
      <?php theme_pagination(); ?>
  <?php else : ?>
    <p><?php _e( 'Please add posts from your WordPress admin page.', THB_THEME_NAME ); ?></p>
  <?php endif; ?>
</section>
  <?php get_sidebar(); ?>
</div>
<?php get_footer();