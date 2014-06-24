<?php get_header(); ?>
<div class="row is_white">
  <section class="nine columns">
    <?php if(have_posts()) : while(have_posts()) : the_post(); ?>
        <article <?php post_class('post'); ?> id="post-<?php the_ID(); ?>">

          <?php
          // The following determines what the post format is and shows the correct file accordingly
          $format = get_post_format();
          if($format)
          {
            get_template_part('inc/postformats/'.$format);
          }
          else
          {
            get_template_part('inc/postformats/standard');
          }
          ?>
          <div class="post-title">
            <aside><?php echo thb_DisplaySingleCategory(true); ?></aside>
            <h1><?php the_title(); ?></h1>
          </div>
          <aside class="post-meta">
            <a class="facebook_line" href="http://www.facebook.com/sharer.php?u=<?php echo the_permalink(); ?>&amp;t=<?php the_title(); ?>" onclick="window.open(encodeURI(decodeURI(this.href)), 'sharewindow', 'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1, resizable=!'); return false;">Facebook</a>
            <a class="twitter_tweet" href="http://twitter.com/intent/tweet?text=【<?php the_title(); ?> | <?php echo the_permalink(); ?>】" onclick="window.open(encodeURI(decodeURI(this.href)), 'tweetwindow', 'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1, resizable=!' ); return false;">twitter</a>
            <a class="google_plus" href="https://plus.google.com/share?url=<?php echo the_permalink(); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Google+</a>
            <ul>
              <li><?php _e('Oleh', THB_THEME_NAME); ?> <strong><?php the_author_posts_link(); ?></strong></li>
              <li>&bull;  &nbsp; <?php echo agora_time_diff(get_the_time('U'), current_time('timestamp')).' yang lalu'; ?></li>
            </ul>
          </aside>
          <div class="post-content">
            <?php
            get_template_part('inc/postformats/post-meta');
            the_content();
            if(is_single())
            {
              wp_link_pages();
            }
            ?>
          </div>

        </article>
        <?php
      endwhile;
      get_template_part('inc/postformats/post-review');
    else :
      ?>
      <p>sumimasen ...</p>
    <?php
    endif;
    get_template_part('inc/postformats/post-prevnext');
    get_template_part('inc/postformats/post-related');
//    get_template_part('inc/postformats/post-prevnext');
    get_template_part('inc/postformats/post-endbox');
    ?>
    <section class="relations">
      <h2>Facebook</h2>
      <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FJapan-Pop-Culture%2F421141184598981&amp;width=<?php agent(); ?>&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23fff&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: <?php agent(); ?>px; height:258px;" allowTransparency="true"></iframe>
      <div id="fb-root"></div>
      <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id))
            return;
          js = d.createElement(s);
          js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
      <div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="10" data-width="<?php agent(); ?>"></div>
    </section>
    <section id="comments" class="cf">
      <?php // comments_template('', true);   ?>
      <div id="disqus_thread"></div>
      <script type="text/javascript">
        var disqus_shortname = 'jcul';
        (function() {
          var dsq = document.createElement('script');
          dsq.type = 'text/javascript';
          dsq.async = true;
          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
      </script>
      <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    </section>
  </section>
  <?php get_sidebar('single'); ?>
</div>
<?php
get_footer();
