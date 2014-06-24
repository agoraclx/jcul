<?php

#-----------------------------------------------------------------
# Columns
#-----------------------------------------------------------------

$thb_shortcodes['header_1'] = array( 
	'type'=>'heading', 
	'title'=>__('Columns')
);

$thb_shortcodes['two_columns'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/2) + (1/2)')
);

$thb_shortcodes['three_columns'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/3) + (1/3) + (1/3)')
);

$thb_shortcodes['four_columns'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/4) + (1/4) + (1/4) + (1/4)')
);

$thb_shortcodes['six_columns'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/6) + (1/6) + (1/6) + (1/6) + (1/6) + (1/6)')
);

$thb_shortcodes['two_third_one_third'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(2/3) + (1/3)')
);

$thb_shortcodes['one_third_two_third'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/3) + (2/3)')
);

$thb_shortcodes['one_forth_three_forth'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(1/4) + (3/4)')
);

$thb_shortcodes['three_forth_one_forth'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('(3/4) + (1/4)')
);

#-----------------------------------------------------------------
# Elements 
#-----------------------------------------------------------------

$thb_shortcodes['header_2'] = array( 
	'type'=>'heading', 
	'title'=>__('Elements')
);

$thb_shortcodes['quote'] = array( 
	'type'=>'regular', 
	'title'=>__('Quotes'), 
	'attr'=>array( 
		'align'=>array(
			'type'=>'radio', 
			'title'=>__('Alignment'), 
			'opt'=>array(
				'normal'=>'Normal',
				'pullleft'=>'Pull Left',
				'pullright'=>'Pull Right'
			)
		),
		'content'=>array(
			'type'=>'textarea', 
			'title'=>__('Content')
		),
		'author'=>array(
			'type'=>'text', 
			'title'=>'Quote Author'
		)
		
	)
);
$thb_shortcodes['btn'] = array( 
	'type'=>'regular', 
	'title'=>__('Button'), 
	'attr'=>array(
		'size'=>array(
			'type'=>'radio', 
			'title'=>__('Size'),
			'opt'=>array(
				'small'=>'small',
				'medium'=>'medium',
				'large'=>'large'
			)
		),
		'color'=>array(
			'type'=>'radio', 
			'title'=>__('Color'),
			'opt'=>array(
				'black'=>'Black',
				'blue1'=>'Blue - 1',
				'blue2'=>'Blue - 2',
				'red'=>'Red',
				'green1'=>'Green - 1',
				'green2'=>'Green - 2',
				'green3'=>'Green - 3',
				'red'=>'Red',
				'purple'=>'Purple',
				'pink'=>'Pink',
				'grey'=>'Grey',
				'smoked'=>'Smoked',
				'yellow'=>'Yellow',
				'bordeaux'=>'Bordeaux'
			)
		),
		'icon'=>array(
			'type'=>'text', 
			'title'=>__('Icon')
		),
		'title'=>array(
			'type'=>'text', 
			'title'=>__('Buton Text')
		),
		'link'=>array(
			'type'=>'text', 
			'title'=>__('Buton Link')
		)
	)
);

$thb_shortcodes['notifications'] = array( 
	'type'=>'regular', 
	'title'=>__('Notification'), 
	'attr'=>array(
		'type'=>array(
			'type'=>'radio', 
			'title'=>__('Type'),
			'opt'=>array(
				'success'=>'Success',
				'error'=>'Error',
				'information'=>'Information',
				'warning'=>'Warning',
				'note'=>'Note',
				'grey'=>'Grey'
			)
		),
		'title'=> array(
			'type'=>'text', 
			'title'=>__('Title')
		),
		'content'=>array(
			'type'=>'textarea', 
			'title'=>__('Content')
		)
	)
);
$thb_shortcodes['seperator'] = array( 
	'type'=>'regular', 
	'title'=>__('Seperator'),
	'attr'=>array(
		'style'=>array(
			'type'=>'radio', 
			'title'=>__('Style'),
			'opt'=>array(
				'style1'=>'Style - 1',
				'style2'=>'Style - 2',
				'style3'=>'Style - 3'
			)
		),
		'title'=> array(
			'type'=>'text', 
			'title'=>__('Title')
		)
	)
);
$thb_shortcodes['tags'] = array( 
	'type'=>'regular', 
	'title'=>__('Tags', THB_THEME_NAME ), 
	'attr'=>array(
		'color'=>array(
			'type'=>'radio', 
			'title'=>__('Color', THB_THEME_NAME),
			'opt'=>array(
				'black'=>'Black',
				'red'=>'Red',
				'blue'=>'Blue',
				'green'=>'Green',
				'grey'=>'Grey',
				'yellow'=>'Yellow'
			)
		),
		'text'=>array(
			'type'=>'text', 
			'title'=>__('Text', THB_THEME_NAME)
		)
	)
);
$thb_shortcodes['dropcap'] = array( 
	'type'=>'direct_to_editor', 
	'title'=>__('Dropcap')
);

#-----------------------------------------------------------------
# Interface Elements 
#-----------------------------------------------------------------

$thb_shortcodes['header_6'] = array( 
	'type'=>'heading', 
	'title'=>__('Interface Elements')
);


$thb_shortcodes['tabs'] = array( 
	'type'=>'dynamic', 
	'title'=>__('Tabs'), 
	'attr'=>array(
		'tabs'=>array('type'=>'custom')
	)
);

$thb_shortcodes['accordion'] = array( 
	'type'=>'dynamic', 
	'title'=>__('Accordion'), 
	'attr'=>array(
		'accordion'=>array('type'=>'custom')
	)
);


$thb_shortcodes['toggle'] = array( 
	'type'=>'regular', 
	'title'=>__('Toggle'), 
	'attr'=>array( 
		'title'=> array(
			'type'=>'text', 
			'title'=>__('Title')
		),
		'content'=>array(
			'type'=>'textarea', 
			'title'=>__('Content')
		)
	)
);

#-----------------------------------------------------------------
# Icons
#-----------------------------------------------------------------

$thb_shortcodes['header_8'] = array( 
	'type'=>'heading', 
	'title'=>__('Icon Related')
);

$thb_shortcodes['single_icon'] = array( 
	'type'=>'regular', 
	'title'=>__('Single Icon'), 
	'attr'=>array( 
		'icon'=>array(
			'type'=>'text', 
			'title'=>__('Icon')
		),
		'size'=>array(
			'type'=>'radio', 
			'title'=>__('Icon Size'),
			'opt'=>array(
				'icon-1x'=>'1x',
				'icon-2x'=>'2x',
				'icon-3x'=>'3x',
				'icon-4x'=>'4x'
			)
		),
		'boxed'=> array(
			'type'=>'checkbox', 
			'title'=>__('Boxed?'),
			'desc'=>'Boxed contains the icon inside a box'
		),
		'rounded'=> array(
			'type'=>'checkbox', 
			'title'=>__('Rounded?'),
			'desc'=>'Containing box is rounded. (Boxed option should be checked)'
		),
		'icon_link'=>array(
			'type'=>'text', 
			'title'=>__('Icon Link'),
			'desc'=>'If you would like to link the icon to an url, enter it here. (Boxed option should be checked)'
		)
	)
);

$thb_shortcodes['icon_list'] = array( 
	'type'=>'dynamic', 
	'title'=>__('Icon List'), 
	'attr'=>array(
		'icon'=>array(
			'type'=>'text', 
			'title'=>__('Icon')
		),
		'icon_list'=>array('type'=>'custom')
	)
);
?>