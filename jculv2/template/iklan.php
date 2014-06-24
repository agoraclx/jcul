<?php
include_once( ABSPATH.'wp-admin/includes/plugin.php' );
if(is_plugin_active("nolrotater/index.php") && function_exists("run_iklanSidebars"))
{
   ?>
   <div class="article-container sharable">
      <?php echo run_iklanSidebars(); ?>
   </div> <?php
}