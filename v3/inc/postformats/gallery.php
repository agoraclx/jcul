<?php
$image_id         = get_post_thumbnail_id();
$image_url        = wp_get_attachment_image_src($image_id, 'full');
$image_url        = $image_url[0];
?>
<div class="post-gallery flex-start flex">
  <ul class="slides">
    <?php
    $attachments      = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
    $attachment_array = explode(',', $attachments);
    foreach($attachment_array as $attachment) :
      if(is_single())
      {
        $src = wp_get_attachment_image_src($attachment, 'single');
      }
      else
      {
        $src = wp_get_attachment_image_src($attachment, 'blog');
      }
      $image_url = wp_get_attachment_image_src($attachment, 'full');
      $image_url = $image_url[0];
      ?>
      <li><img src="<?php echo $src[0]; ?>" /></li>
    <?php endforeach; ?>
  </ul>
</div>