<?php
$image_id  = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id, 'full');
$image_url = $image_url[0];
?>
<div class="post-gallery nolink">
  <?php
  if(is_single())
  {
    the_post_thumbnail('single');
  }
  else
  {
    the_post_thumbnail('blog');
  }
  ?>
</div>