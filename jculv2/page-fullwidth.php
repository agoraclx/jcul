<?php
/**
* template name: full width
*
*/
get_header();?>
<?php if(have_posts()) while(have_posts()) : the_post(); ?>
<div class="pages show" id="body-container">
<div id="main">
<div class="page-container" id="page_<?php the_ID(); ?>">
<div id="body">
<div class="flex-box">
<div class="box-cell page">
<hgroup class="page-header channel">
<h2><?php the_title(); ?></h2>
</hgroup>
<div class="page-content" style="max-width: 100%;">
<div class="description">
<?php the_content(); ?>
</div>
<?php endwhile; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php get_footer();