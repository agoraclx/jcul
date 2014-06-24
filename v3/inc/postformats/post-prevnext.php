<aside id="sharethispost" class="sharethispost cf hide-on-print">
  <div class="placeholder">
    <div class="share-button-boxy"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink(); ?>" data-via="JepangCulture" data-lang="en" data-counturl="<?php the_permalink(); ?>" data-count="vertical">Tweet</a>
      <script>!function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (!d.getElementById(id)) {
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);
          }
        }(document, "script", "twitter-wjs");</script>
    </div>
    <div class="share-button-boxy"><iframe src="//www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;width=59&amp;layout=box_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=61&amp;appId=184062871755685" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:59px; height:61px;" allowTransparency="true"></iframe></div>
    <div class="share-button-boxy"><!-- Place this tag where you want the +1 button to render. -->
      <div class="g-plusone" data-size="tall" data-href="<?php the_permalink(); ?>"></div>
      <script type="text/javascript">
        window.___gcfg = {lang: 'id'};
        (function() {
          var po = document.createElement('script');
          po.type = 'text/javascript';
          po.async = true;
          po.src = 'https://apis.google.com/js/platform.js';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(po, s);
        })();
      </script></div>
  </div><?php /* ?>
  <a href="#" class="sharenow">Share This Article <i class="fa fa-plus"></i></a>
  <a href="#" class="sharenow">Share This Article</a><?php */ ?>
</aside>
<!-- Start Previous / Next Post -->
<?php
$prev_post = get_adjacent_post(false, '', true);

if(!empty($prev_post))
{
  $excerpt = $prev_post->post_content;
  $previd  = $prev_post->ID;
  $thumb   = get_the_post_thumbnail($previd, 'slider');

  echo '<div class="post post-navi hide-on-print prev"><div class="post-gallery">'.$thumb.'<div class="overlay"></div></div><div class="post-title"><h2><a href="'.get_permalink($previd).'" title="'.$prev_post->post_title.'">'.ShortenText($prev_post->post_title, 50).'</a></h2></div></div>';
}

$next_post = get_adjacent_post(false, '', false);

if(!empty($next_post))
{
  $excerptnext = $next_post->post_content;
  $nextid      = $next_post->ID;
  $thumb       = get_the_post_thumbnail($nextid, 'slider');
  echo '<div class="post post-navi hide-on-print next"><div class="post-gallery">'.$thumb.'<div class="overlay"></div></div><div class="post-title"><h2><a href="'.get_permalink($nextid).'" title="'.$next_post->post_title.'">'.ShortenText($next_post->post_title, 50).'</a></h2></div></div>';
}
wp_reset_query(); ?>
<!-- End Previous / Next Post -->