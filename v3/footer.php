</div>
<?php
/*
  if(ot_get_option('footer') != 'no')
  {
  ?>
  <div class="row is_white">
  <div class="twelve columns">
  <footer id="footer">
  <div class="row">

  <?php
  if(ot_get_option('footer_columns') == 'fourcolumns')
  {
  ?>
  <div class="three columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer3'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer4'); ?>
  </div>
  <?php
  }
  elseif(ot_get_option('footer_columns') == 'threecolumns')
  {
  ?>
  <div class="four columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="four columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <div class="four columns">
  <?php dynamic_sidebar('footer3'); ?>
  </div>
  <?php
  }
  elseif(ot_get_option('footer_columns') == 'twocolumns')
  {
  ?>
  <div class="six columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="six columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <?php
  }
  elseif(ot_get_option('footer_columns') == 'doubleleft')
  {
  ?>
  <div class="six columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer3'); ?>
  </div>
  <?php
  }
  elseif(ot_get_option('footer_columns') == 'doubleright')
  {
  ?>
  <div class="three columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="three columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <div class="six columns">
  <?php dynamic_sidebar('footer3'); ?>
  </div>
  <?php
  }
  elseif(ot_get_option('footer_columns') == 'sixcolumns')
  {
  ?>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer1'); ?>
  </div>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer2'); ?>
  </div>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer3'); ?>
  </div>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer4'); ?>
  </div>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer5'); ?>
  </div>
  <div class="two mobile-two columns">
  <?php dynamic_sidebar('footer6'); ?>
  </div>
  <?php } ?>
  </div>
  </footer>
  </div>
  </div>
  <?php
  } */
if(ot_get_option('subfooter') != 'no')
{
  ?>
  <div class="row is_white">
    <div class="twelve columns">
      <section id="subfooter">
        <div class="row">
          <div class="four columns">
            <p><?php echo ot_get_option('copyright', '&COPY;'); ?> <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
            <p><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/" title="j-cul.com is licensed under a Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a></p>
          </div>
          <div class="eight columns">
            <?php wp_nav_menu(array('theme_location' => 'footer-menu', 'depth' => 1, 'container' => false)); ?>
          </div>
        </div>
      </section>
    </div>
  </div>
<?php } ?>
</div> <!-- End #wrapper -->
<?php
if(ot_get_option('disablescrollbubble') != 'yes')
{
  ?>
  <div id="scrollbubble"></div>
  <?php
}
echo ot_get_option('ga');
wp_footer();
?>
</body>
</html>