<?php
get_header();
$home = get_home_url(); ?>
<div class="row is_white">
  <section class="seven columns centered notfound">
    <div class="errorimage"><img src="<?php echo get_template_directory_uri(); ?>/assets/img/404.png" alt="404" /></div>
    <h2>Soory pencarian untuk kata tersebut tidak ditemukan</h2>
    <p><?php _e('Atau silahkan ke <a href="'.$home.'">homepage</a>.', THB_THEME_NAME); ?></p>
    <?php get_search_form(); ?>
    <?php
    if(ot_get_option('fb_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('fb_link'); ?>" class="boxed-icon facebook icon-1x rounded"><i class="fa fa-facebook"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('pinterest_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('pinterest_link'); ?>" class="boxed-icon pinterest icon-1x rounded"><i class="fa fa-pinterest"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('twitter_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('twitter_link'); ?>" class="boxed-icon twitter icon-1x rounded"><i class="fa fa-twitter"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('googleplus_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('googleplus_link'); ?>" class="boxed-icon google-plus icon-1x rounded"><i class="fa fa-google-plus"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('linkedin_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('linkedin_link'); ?>" class="boxed-icon linkedin icon-1x rounded"><i class="fa fa-linkedin"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('instragram_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('instragram_link'); ?>" class="boxed-icon instagram icon-1x rounded"><i class="fa fa-instagram"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('xing_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('xing_link'); ?>" class="boxed-icon xing icon-1x rounded"><i class="fa fa-xing"></i></a>
    <?php } ?>
    <?php
    if(ot_get_option('tumblr_link'))
    {
      ?>
      <a href="<?php echo ot_get_option('tumblr_link'); ?>" class="boxed-icon tumblr icon-1x rounded"><i class="fa fa-tumblr"></i></a>
      <?php } ?>
  </section>
</div>
<?php get_footer();