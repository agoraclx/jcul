<?php
/* Columns Shortcode */
function shortcode_column_row( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));

	return '<div class="row">' .  do_shortcode($content) . '</div>';
}
function shortcode_column($atts, $content = null, $code) {

	if ($code == 'two-columns') {
		return '<div class="six columns">' .  do_shortcode($content) . '</div>';
	}
	if ($code == 'three-columns') {
		return '<div class="four columns">' .  do_shortcode($content) . '</div>';
	}
	if ($code == 'four-columns') {
		return '<div class="three columns">' .  do_shortcode($content) . '</div>';
	}
	if ($code == 'six-columns') {
		return '<div class="two columns">' .  do_shortcode($content) . '</div>';
	}
	if ($code == 'two-three-columns') {
		return '<div class="eight columns">' .  do_shortcode($content) . '</div>';
	}
	if ($code == 'three-four-columns') {
		return '<div class="nine columns">' .  do_shortcode($content) . '</div>';
	}
}
add_shortcode('columns', 'shortcode_column_row');
add_shortcode('two-columns', 'shortcode_column');
add_shortcode('three-columns', 'shortcode_column');
add_shortcode('four-columns', 'shortcode_column');
add_shortcode('six-columns', 'shortcode_column');
add_shortcode('two-three-columns', 'shortcode_column');
add_shortcode('three-four-columns', 'shortcode_column');

/* Inline Label Shortcodes */
function tags($atts, $content = null ) {
    extract(shortcode_atts(array(
    	'color'      => 'black'
    ), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$out = '<span class="label '.$color.'">' .$content. '</span>';
	
    return $out;
}
add_shortcode('tags', 'tags');

/* Notification Shortcodes */
function notification( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'type'      => 'success',
       	'title'			=> 'Success Message'
    ), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$icon = '';
	switch($type) {
	 case 'success':
	 	$icon = 'check';
	 break;
	 case 'error':
	 	$icon = 'times-circle';
	 break;
	 case 'information':
	 	$icon = 'info-circle';
	 break;
	 case 'warning':
	 	$icon = 'warning';
	 break;
	 case 'note':
	 	$icon = 'file-o';
	 break;
	 case 'grey':
	 	$icon = 'clock-o';
	 break;
	}
	$out = '<div class="notification-box '.$type.'"><div class="icon-holder"><i class="fa fa-'.$icon.'"></i></div><h6>'.$title.'</h6><div class="text cf"><p>' .$content. '</p></div><a href="" class="close">×</a></div>';
    return $out;
}
add_shortcode('notification', 'notification');

/* Blockquote */
function blockquotes( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'pull'      => '',
       	'author'    => ''
    ), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$authorhtml = '';
	if ($author) {
		$authorhtml = '<cite>'. $author. '</cite>';
	}
	$out = '<blockquote class="styled '.$pull.'"><p>' .$content. $authorhtml. '</p></blockquote>';
    return $out;
}
add_shortcode('blockquote', 'blockquotes');


/* Buttons */
function buttons( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'color'      => '',
       	'link'       => '#',
       	'size'			 => 'small',
       	'icon'			 => ''
    ), $atts));
	
	if($icon) { $content = $content. ' <i class="fa fa-'.$icon.'"></i>'; }
	
	$out = '<a class="btn '.$color.' '.$size.'" href="'.$link.'">' .$content. '</a>';
  
  return $out;
}
add_shortcode('button', 'buttons');

/* Icons */
function icons( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'type'      => 'ok',
       	'url'				=> '',
       	'box'				=> '',
       	'size'			=> 'icon-1x',
       	'round'		=> ''
    ), $atts));
 
		$out = '<i class="fa fa-'.$type.'"></i>';
  
  	if ($box && $round == 'true') {
  		if ($type == 'facebook' || $type == 'twitter' || $type == 'google-plus' || $type == 'pinterest' || $type == 'linkedin') {
  			$class = $type;
  		} else {
  			$class = '';
  		}
  		$out = '<a href="'.$url.'" class="boxed-icon '.$class.' '. $size.' rounded">'.$out.'</i></a>';
  	} else if ($box && $round != 'true') {
  		if ($type == 'facebook' || $type == 'twitter' || $type == 'google-plus' || $type == 'pinterest' || $type == 'linkedin') {
  			$class = $type;
  		}
  		$out = '<a href="'.$url.'" class="boxed-icon '.$class.' '. $size.'">'.$out.'</i></a>';
  	}	else {
  		$out = '<aside class="boxed-icon '. $size.' no-link"><i class="fa fa-'.$type.' '. $size.'"></i></aside>';
  	}	
  	
  	return $out;
}
add_shortcode('icon', 'icons');

/* Dropcap */
function dropcap( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	'boxed'      => 'false'
    ), $atts));
 		
 		if ($boxed == "true") {
 			$type = 'boxed';
 		} else {
 			$type = '';
 		}
		$out = '<span class="dropcap '.$type.'">'.$content.'</span>';
  	
  	return $out;
}
add_shortcode('dropcap', 'dropcap');


/* Icon Styled Lists */
function icon_list($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		'icon'      => 'ok'
	), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$output = '';
	if (!preg_match_all("/(.?)\[(item)\b(.*?)(?:(\/))?\](?:(.+?)\[\/item\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		
		$output .='<ul class="iconlist">';
		for($i = 0; $i < count($matches[0]); $i++) {
			$output .= '<li><i class="icon-'.$icon.'"></i>' . do_shortcode(trim($matches[5][$i])) .'</li>';
		}
		$output .='</ul>';
		return $output;
	}
}
add_shortcode('icon-list', 'icon_list');

/* Tabs Shortcode */
function shortcode_tabs($atts, $content = null, $code) {
	extract(shortcode_atts(array(
	), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$output = '';
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		$numRand = rand('10','100');
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		
		$output .='<dl class="tabs">';
		for($i = 0; $i < count($matches[0]); $i++) {
			if ($i == "0") {
			$output .= '<dd class="active"><a title="' . $matches[3][$i]['title'] .  '" href="#tab'.$numRand.'-'. $i . '">';
				if(isset($matches[3][$i]['icon'])) {
					$output .= '<i class="fa fa-'.$matches[3][$i]["icon"].'"></i>';
				}
			$output .= $matches[3][$i]['title'] . '</a></dd>';
			} else {
			$output .= '<dd><a title="' . $matches[3][$i]['title'] .  '" href="#tab' .$numRand.'-'. $i . '">';
				if(isset($matches[3][$i]['icon'])) {
					$output .= '<i class="fa fa-'.$matches[3][$i]["icon"].'"></i>';
				}
			$output .= $matches[3][$i]['title'] . '</a></dd>';
			}
			
		}
		$output .='</dl>';
		$output .='<ul class="tabs-content">';
		for($i = 0; $i < count($matches[0]); $i++) {
			if ($i == "0") {
			$output .= '<li id="tab' .$numRand.'-'. $i . 'Tab" class="active">' . do_shortcode(trim($matches[5][$i])) .'</li>';
			} else {
			$output .= '<li id="tab' .$numRand.'-'. $i . 'Tab" style="display:none;">' . do_shortcode(trim($matches[5][$i])) .'</li>';
			}
					}
		$output .='</ul>';
		return $output;
	}
}
add_shortcode('tabs', 'shortcode_tabs');

/* Accordion Shortcode */
function shortcode_accordion($atts, $content, $code) {
	extract(shortcode_atts(array(
	), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$output = '';
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		$numRand = rand('10','100');
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		
		for($i = 0; $i < count($matches[0]); $i++) {
			if ($i == "0") {
			$output .= '
			<li class="active">
				<div class="title">
					<h5>' . $matches[3][$i]['title'] .  '</h5>
				</div>
				<div class="content">
					' . do_shortcode(trim($matches[5][$i])) .'
				</div>
			</li>';
			} else {
			$output .= '
			<li>
				<div class="title">
					<h5>' . $matches[3][$i]['title'] .  '</h5>
				</div>
				<div class="content">
					' . do_shortcode(trim($matches[5][$i])) .'
				</div>
			</li>';
			}
			
		}
		
		$output ='<ul class="accordion" id="accordion-' .$numRand.'">' . $output . '</ul>';
		return $output;
	}
}
add_shortcode('accordion', 'shortcode_accordion');

/* Toggle Shortcode */
function shortcode_toggle( $atts, $content = null)
{
 extract(shortcode_atts(array(
        'title'      => '',
        ), $atts));
		$content = remove_invalid_tags($content, array('p'));
		$content = remove_invalid_tags($content, array('br'));
   return '<div class="toggle"><div class="title">'.$title.'</div><div class="inner">'.do_shortcode($content).'</div></div>';
}
add_shortcode('toggle', 'shortcode_toggle');


/* Divider Shortcodes */
function seperator( $atts, $content = null ) {
	extract(shortcode_atts(array(
	    'style' => 'style1'
	), $atts));
   return '<div class="seperator '.$style.'"><h6>'.$content.'</h6></div>';
}
add_shortcode('seperator', 'seperator');

?>