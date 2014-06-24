<?php
global $wp_embed;
$image_id  = get_post_thumbnail_id();
$image_url = wp_get_attachment_image_src($image_id, 'full')[0];
//$image_url = $image_url[0];
?>
<div class="post-gallery flex-video widescreen">
  <?php
  $embed     = get_post_meta($post->ID, 'post_video', TRUE);
  if($embed != '')
  {
    echo $wp_embed->run_shortcode('[embed]'.$embed.'[/embed]');
  }
  ?>
</div>