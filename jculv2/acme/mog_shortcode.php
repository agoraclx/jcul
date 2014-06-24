<?php

function remove_wpautop ($content)
{
   $content = do_shortcode(shortcode_unautop($content));
   $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
   return $content;
}

function nama_author ($atts, $content = null)
{
   extract(shortcode_atts(array (
       'gambar' => '',
       'nama'   => ''
           ), $atts));

   return remove_wpautop('<div class="guest-author-info">
                                 <a class="guest-author-image" href="#">
                                    <img alt="" src="'.get_template_directory_uri().'/mog.php?src='.$gambar.'&h=280&w=280&q=80&s=1">
                                 </a>
                                 <div class="guest-author-summary">
                                    <a class="guest-author-name" href="#">'.$nama.'</a>
                                      '.$content.'
                                 </div>
                          </div>');
}

add_shortcode('nama_author', 'nama_author');

function pop_out ($atts, $content = null)
{
   return remove_wpautop('<div class="popout">'.$content.'</div>');
}

add_shortcode('popout', pop_out);

function iklan ($atts)
{
   extract(shortcode_atts(array (
       'link'   => '',
       'gambar' => ''
           ), $atts));

   return remove_wpautop('<div style="box-shadow:1px 1px 5px 1px #d0d0d0;text-align: center;display: inline-block;"><a data-turbo-target="post-slider" href="'.$link.'" target="_blank"><span class="_ppf loaded"><img src="'.$gambar.'" /></span></a></div>');
}

add_shortcode('iklan', iklan);