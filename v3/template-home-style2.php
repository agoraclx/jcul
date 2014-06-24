<?php
/*
Template Name: Home Page - Style 2
*/
?>
<?php get_header(); ?>
<div class="row">
	<section class="nine columns">
		<?php if(!is_paged()) { ?>
			<div id="slider" class="flex slider flex-start categoryslider" data-bullets="true" data-controls="true">
			<ul class="slides">
				<?php $args = array(
				  	   'posts_per_page' => '5',
				  	   'ignore_sticky_posts' => '1'
					  	);
				?>
				<?php $query = new WP_Query($args); ?>
				<?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
				<li class="post">
					<div class="post-gallery">
						<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_post_thumbnail('category-slider'); ?></a>
						<div class="overlay"></div>
					</div>
					<div class="post-title">
						<aside><?php echo thb_DisplaySingleCategory(true); ?></aside>
						<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
						<?php echo thb_DisplayPostMeta(true,true,true,true); ?>
					</div>
				</li>
				<?php endwhile; else: endif; ?>
			</ul>
		</div>
		<?php } ?>
		<section id="recentnews">
			<?php $args = array(
			  	   'posts_per_page' => '9',
			  	   'offset' => '5',
			  	   'ignore_sticky_posts' => '1'
				  	);
			?>
			<?php $query = new WP_Query($args); ?>
			<?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
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
							<?php echo thb_DisplayPostMeta(true,true,true,false); ?>
						</div>
					</div>
				</div>
			</article>
			<?php endwhile; else: endif; ?>
			<a id="loadmore" href="#" data-loading="<?php _e( 'Loading ...', THB_THEME_NAME ); ?>" data-nomore="<?php _e( 'No More Posts to Show', THB_THEME_NAME ); ?>" data-count="9" data-action="thb_ajax_home2"><?php _e( 'Load More', THB_THEME_NAME ); ?></a>
		</section>
	</section>
	<?php get_sidebar('home2'); ?>
</div>
<?php get_footer(); ?>