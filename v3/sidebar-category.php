<aside class="sidebar three columns">
  <?php
  ##############################################################################
  # Category Sidebar
  ##############################################################################
  ?>
  <?php dynamic_sidebar('category'); ?>


  <?php
  include_once( ABSPATH.'wp-admin/includes/plugin.php' );
  if(is_plugin_active("nolrotater/index.php") && function_exists("run_iklanSidebars"))
  {
    echo run_iklanSidebars();
  }
  ?>

</aside>