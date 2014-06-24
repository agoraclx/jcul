<?php
require_once( '../../../../../wp-load.php' );
Header("Content-type: text/css");
error_reporting(0);
$fontlist = array();

function google_webfont($font, $default = false)
{
  global $fontlist;

  $fontbase = 'http://fonts.googleapis.com/css?family=';
  $import   = '';

  if($font)
  {
    $otfont = ot_get_option($font);
    if($otfont['font-family'])
    {
      $otfontfamily = $otfont['font-family'];
    }
    else
    {
      $otfontfamily = $default;
    }
    if(!in_array($otfontfamily, $fontlist))
    {
      array_push($fontlist, $otfontfamily);
      if($otfontfamily)
      {
        $cssfont = str_replace(' ', '+', $otfontfamily);
        $import  = '@import "'.$fontbase.$cssfont.':200,300,400,400italic,600,700";';

        return $import;
      }
    }
  }
}

function typeecho($array, $important = false, $default = false)
{

  if($array['font-family'])
  {
    echo "font-family: ".$array['font-family'].";\n";
  }
  else if($default)
  {
    echo "font-family: ".$default.";\n";
  }
  if($array['font-color'])
  {
    echo "color: ".$array['font-color'].";\n";
  }
  if($array['font-style'])
  {
    echo "font-style: ".$array['font-style'].";\n";
  }
  if($array['font-variant'])
  {
    echo "font-variant: ".$array['font-variant'].";\n";
  }
  if($array['font-weight'])
  {
    echo "font-weight: ".$array['font-weight'].";\n";
  }
  if($array['font-size'])
  {

    if($important)
    {
      echo "font-size: ".$array['font-size']." !important;\n";
    }
    else
    {
      echo "font-size: ".$array['font-size'].";\n";
    }
  }
  if($array['text-decoration'])
  {
    echo "text-decoration: ".$array['text-decoration']." !important;\n";
  }
  if($array['text-transform'])
  {
    echo "text-transform: ".$array['text-transform']." !important;\n";
  }
  if($array['line-height'])
  {
    echo "line-height: ".$array['line-height']." !important;\n";
  }
  if($array['letter-spacing'])
  {
    echo "letter-spacing: ".$array['letter-spacing']." !important;\n";
  }
}

function bgecho($array)
{
  if($array['background-color'])
  {
    echo "background-color: ".$array['background-color'].";\n";
  }
  if($array['background-image'])
  {
    echo "background-image: url(".$array['background-image'].");\n";
  }
  if($array['background-repeat'])
  {
    echo "background-repeat: ".$array['background-repeat'].";\n";
  }
  if($array['background-attachment'])
  {
    echo "background-attachment: ".$array['background-attachment'].";\n";
  }
  if($array['background-position'])
  {
    echo "background-position: ".$array['background-position'].";\n";
  }
}

function measurementecho($array)
{
  echo $array[0].$array[1];
}

echo google_webfont('logo_type')."\n";
echo google_webfont('post_title_type')."\n";
echo google_webfont('body_type', 'Merriweather')."\n";
echo google_webfont('menu_type')."\n";
echo google_webfont('submenu_type')."\n";
echo google_webfont('widget_title_type')."\n";
echo google_webfont('footer_widget_title_type')."\n";
echo google_webfont('footer_type')."\n";
echo google_webfont('heading_h1_type')."\n";
echo google_webfont('heading_h2_type')."\n";
echo google_webfont('heading_h3_type')."\n";
echo google_webfont('heading_h4_type')."\n";
echo google_webfont('heading_h5_type')."\n";
echo google_webfont('heading_h6_type')."\n";

function hex2rgb($hex)
{

  $hex = str_replace("#", "", $hex);

  if(strlen($hex) == 3)
  {

    $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
    $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
    $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
  }
  else
  {

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
  }

  $rgb = array($r, $g, $b);

  return implode(",", $rgb); // returns the rgb values separated by commas
}
?>
/* Options set in the admin page */

body {
<?php typeecho(ot_get_option('body_type'), false, 'ProximaNovaRegular'); ?>
color: <?php echo ot_get_option('text_color'); ?>;
}
<?php echo ot_get_option('extra_css');