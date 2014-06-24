<aside class="sidebar two columns">
  <?php
  ##############################################################################
  # The MOST POPULARS PLUGINS 
  ##############################################################################
  ?>
  <div class="widget cf widget_latestcategory" id="latestcategory-2">
    <h6 style="border-color: #e61ca6">Top View</h6>
    <?php
    $arg = array(
      'range' => 'monthly',
      'limit' => 10,
    );
    get_mostpopular($arg);
    ?>
  </div>
  <?php
  ##############################################################################
  # The Right Sidebar
  ##############################################################################
  dynamic_sidebar('right');
  ?>


  <?php
  ##############################################################################
  # menampilken iklan dari NOL ROTATOR PLUGIN
  ##############################################################################
  ?>
  <?php
  include_once( ABSPATH.'wp-admin/includes/plugin.php' );
  if(is_plugin_active("nolrotater/index.php") && function_exists("run_iklanSidebars"))
  {
    echo run_iklanSidebars();
  }
  ?>


</aside>