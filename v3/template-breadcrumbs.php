<?php
if(!is_page_template('template-home.php') && !is_page_template('template-home-style2.php') && !is_page_template('template-home-style3.php') && !is_404())
{

  if(ot_get_option('breadcrumbs') != 'no')
  {
    ?>
    <div class="row is_white">
      <div class="twelve columns">
        <div id="breadcrumbs">
          <?php thb_breadcrumb(); ?>
        </div>
      </div>
    </div>
  <?php
  }
}