</div>
<?php
if(!is_singular())
{
   include_once( ABSPATH.'wp-admin/includes/plugin.php' );
   // check for plugin using plugin name
   if(is_plugin_active("nolrotater/index.php") && function_exists("run_AgoRacLX") )
   {
        echo run_AgoRacLX();
   }
}
wp_footer();
?></body></html>