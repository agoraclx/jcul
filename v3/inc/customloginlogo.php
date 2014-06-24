<?php

/* ----------------------------------------------------------------------------------- */
/* 	Custom Login Logo Support
  /*----------------------------------------------------------------------------------- */

if(!function_exists('custom_login_logo'))
{

  function custom_login_logo()
  {
//    if(ot_get_option('loginlogo', ''))
//    {
//      $loginlogo = ot_get_option('loginlogo', '');
      echo '<style type="text/css">
        html{background-color: white;}
        body.login {background: url("'.get_template_directory_uri().'/assets/img/body.jpg") repeat-x scroll center top transparent;}
        h1 a { background-image:url("'.get_template_directory_uri().'/assets/img/404.jpg") !important; background-size:auto !important;}
        #login{width:810px}
        .login h1 a{height: 340px;width: 810px;}
        </style>';
//    }
  }

  add_action('login_head', 'custom_login_logo');
}


function change_wp_login_url ()
{
   return esc_url(home_url('/'));
}

add_filter('login_headerurl', 'change_wp_login_url');

function change_wp_login_title ()
{
   return 'Powered by Nolgraphic.com';
}

add_filter('login_headertitle', 'change_wp_login_title');