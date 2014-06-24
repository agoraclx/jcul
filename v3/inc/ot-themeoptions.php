<?php

/**
 * Initialize the options before anything else.
 */
add_action('admin_init', '_custom_theme_options', 1);

/**
 * Theme Mode demo code of all the available option types.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function _custom_theme_options()
{

  /**
   * Get a copy of the saved settings array.
   */
  $saved_settings = get_option('option_tree_settings', array());

  /**
   * Create a custom settings array that we pass to
   * the OptionTree Settings API Class.
   */
  $custom_settings = array(
    'sections' => array(
      array(
        'title' => 'General',
        'id'    => 'general'
      ),
//      array(
//        'title' => 'Weather',
//        'id'    => 'weather'
//      ),
      array(
        'title' => 'Header Styling',
        'id'    => 'header'
      ),
      array(
        'title' => 'Top Level Category Colors',
        'id'    => 'category_colors'
      ),
      array(
        'title' => 'Home (Style1) Categories',
        'id'    => 'home_categories'
      ),
//      array(
//        'title' => 'Home (Style3) Categories',
//        'id'    => 'home2_categories'
//      ),
//      array(
//        'title' => 'Typography',
//        'id'    => 'typography'
//      ),
      array(
        'title' => 'Advertisement',
        'id'    => 'ads'
      ),
      array(
        'title' => 'Social',
        'id'    => 'social'
      ),
      array(
        'title' => 'Contact',
        'id'    => 'contact'
      ),
      array(
        'title' => 'Sidebars',
        'id'    => 'sidebars'
      ),
      array(
        'title' => 'Twitter OAuth',
        'id'    => 'twitter'
      ),
      array(
        'title' => 'Misc',
        'id'    => 'misc'
      )
    ),
    'settings' => array(
      array(
        'label'   => 'Breaking News Tag',
        'id'      => 'breaking_news',
        'type'    => 'tag_checkbox',
        'desc'    => 'Please select from which tags you would like to display posts from inside the breaking news marquee.',
        'std'     => '',
        'section' => 'general'
      ),
      array(
        'label'   => 'Featured News Tag',
        'id'      => 'featured_news',
        'type'    => 'tag_checkbox',
        'desc'    => 'Please select from which tags you would like to display posts from inside the featured news carousel on homepage.',
        'std'     => '',
        'section' => 'general'
      ),
      array(
        'label'   => 'Boxed Layout',
        'id'      => 'boxed',
        'type'    => 'radio',
        'desc'    => 'The content is contained and the body background is visible from the sides.',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'general'
      ),
      array(
        'label'   => 'Display Breadcrumbs',
        'id'      => 'breadcrumbs',
        'type'    => 'radio',
        'desc'    => 'Would you like to display the Breadcrumbs?',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'yes',
        'section' => 'general'
      ),
      array(
        'label'   => 'Display Footer',
        'id'      => 'footer',
        'type'    => 'radio',
        'desc'    => 'Would you like to display the Footer?',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'yes',
        'section' => 'general'
      ),
      array(
        'label'   => 'Footer Columns',
        'id'      => 'footer_columns',
        'type'    => 'radio-image',
        'desc'    => 'You can change the layout of footer columns here',
        'std'     => 'sixcolumns',
        'section' => 'general'
      ),
      array(
        'label'   => 'City to pull weather information for',
        'id'      => 'weather_city',
        'type'    => 'text',
        'desc'    => 'Istanbul, New York, NY, Moscow, etc.',
        'section' => 'weather'
      ),
      array(
        'label'   => 'Units for weather',
        'id'      => 'weather_units',
        'type'    => 'radio',
        'desc'    => 'Use Metric or Imperial system?',
        'choices' => array(
          array(
            'label' => 'Metric',
            'value' => 'metric'
          ),
          array(
            'label' => 'Imperial',
            'value' => 'imperial'
          )
        ),
        'std'     => 'metric',
        'section' => 'weather'
      ),
      array(
        'label'   => 'Disable Scroll Bubble',
        'id'      => 'disablescrollbubble',
        'type'    => 'radio',
        'desc'    => 'Would you like to remove the scroll bubble?',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Disable Page End Box',
        'id'      => 'disablepageendbox',
        'type'    => 'radio',
        'desc'    => 'Would you like to remove the page end boxes, viewable in articles?',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Login Logo Upload',
        'id'      => 'loginlogo',
        'type'    => 'upload',
        'desc'    => 'You can upload a custom logo for your wp-admin login page here',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Favicon Upload',
        'id'      => 'favicon',
        'type'    => 'upload',
        'desc'    => 'You can upload your own favicon here',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Disable Like System',
        'id'      => 'disablelike',
        'type'    => 'radio',
        'desc'    => 'Would you like to remove the like functionality?',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Extra CSS',
        'id'      => 'extra_css',
        'type'    => 'textarea-simple',
        'desc'    => 'Any CSS that you would like to add to the theme',
        'rows'    => '5',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Google Analytics',
        'id'      => 'ga',
        'type'    => 'textarea-simple',
        'desc'    => 'Google analytics field. Your GA code will be entered at the bottom of the theme',
        'rows'    => '5',
        'section' => 'misc'
      ),
      array(
        'label'   => 'Header Style',
        'id'      => 'header_style',
        'type'    => 'radio',
        'desc'    => 'Which header style would you like to use?',
        'choices' => array(
          array(
            'label' => 'Style 1',
            'value' => 'style1'
          ),
          array(
            'label' => 'Style 2',
            'value' => 'style2'
          )
        ),
        'std'     => 'style1',
        'section' => 'header'
      ),
      array(
        'label'   => 'Logo Upload',
        'id'      => 'logo',
        'type'    => 'upload',
        'desc'    => 'You can upload your own logo here. Since this theme is retina-ready, <strong>please upload a double size image.</strong> The image should be maximum 160 pixels in height.',
        'section' => 'header'
      ),
      array(
        'label'   => 'Mobile Logo Upload',
        'id'      => 'logo_mobile',
        'type'    => 'upload',
        'desc'    => 'You can upload your own mobile logo here.  The image should be maximum 80 pixels in height. <small>Smaller version of your logo for mobile screens</small>',
        'section' => 'header'
      ),
      array(
        'label'   => 'Use Text as Logo?',
        'id'      => 'logo_text',
        'type'    => 'radio',
        'desc'    => 'Would you like to use text instead of image for your logo? <small>You can adjust text options from Typography page</small> ',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'header'
      ),
      array(
        'label'   => 'Categories',
        'id'      => 'home_categories',
        'type'    => 'category_checkbox',
        'desc'    => 'Please select which categories you would like to display at home page.',
        'std'     => '',
        'section' => 'home_categories'
      ),
      array(
        'label'   => 'Category Order',
        'id'      => 'home_category_order',
        'type'    => 'category_order',
        'desc'    => 'Please select the order of the home page categories. If a category is not selected above, it will be ignored.',
        'section' => 'home_categories'
      ),
      array(
        'label'   => 'Categories on Top',
        'id'      => 'home2_top_categories',
        'type'    => 'category_checkbox',
        'desc'    => 'Please select 3 categories you would like to display at the top. Will be displayed in 3 columns',
        'std'     => '',
        'section' => 'home2_categories'
      ),
      array(
        'label'   => 'Categories in Middle',
        'id'      => 'home2_middle_categories',
        'type'    => 'category_checkbox',
        'desc'    => 'Please select categories you would like to display in the middle. They will be displayed in wide format',
        'std'     => '',
        'section' => 'home2_categories'
      ),
      array(
        'label'   => 'Categories at Bottom',
        'id'      => 'home2_bottom_categories',
        'type'    => 'category_checkbox',
        'desc'    => 'Please select 3 categories you would like to display at the bottom. Will be displayed in 3 columns',
        'std'     => '',
        'section' => 'home2_categories'
      ),
      array(
        'label'   => 'Category Colors',
        'id'      => 'category_colors',
        'type'    => 'category_colorpicker',
        'desc'    => '',
        'section' => 'category_colors'
      ),
      array(
        'label'   => 'Text Color',
        'id'      => 'text_color',
        'type'    => 'colorpicker',
        'desc'    => 'Body Text Color',
        'section' => 'body'
      ),
      array(
        'label'   => 'Logo Typography',
        'id'      => 'logo_type',
        'type'    => 'typography',
        'desc'    => 'If you are using text instead of image, select font options for the logo.',
        'section' => 'typography'
      ),
      array(
        'label'   => 'Body Text Typography',
        'id'      => 'body_type',
        'type'    => 'typography',
        'desc'    => 'Font Settings for general body font',
        'section' => 'typography'
      ),
      array(
        'label'   => 'Disable Advertisements?',
        'id'      => 'disableads',
        'type'    => 'radio',
        'desc'    => 'Check yes to disable advertisements on Home - Style 3',
        'choices' => array(
          array(
            'label' => 'Yes',
            'value' => 'yes'
          ),
          array(
            'label' => 'No',
            'value' => 'no'
          )
        ),
        'std'     => 'no',
        'section' => 'ads'
      ),
      array(
        'label'   => 'Advertise Page Link',
        'id'      => 'ads_default',
        'type'    => 'text',
        'desc'    => 'Link to the advertise/contact page if there is no advertisements',
        'section' => 'ads'
      ),
      array(
        'label'   => 'Header Advertisement',
        'id'      => 'ads_header',
        'type'    => 'textarea-simple',
        'desc'    => 'Code for the advertisement here',
        'rows'    => '5',
        'section' => 'ads'
      ),
      array(
        'label'   => 'Advertisement 1 code',
        'id'      => 'ads_1',
        'type'    => 'textarea-simple',
        'desc'    => 'Code for the advertisement here',
        'rows'    => '5',
        'section' => 'ads'
      ),
      array(
        'label'   => 'Advertisement 2 code',
        'id'      => 'ads_2',
        'type'    => 'textarea-simple',
        'desc'    => 'Code for the advertisement here',
        'rows'    => '5',
        'section' => 'ads'
      ),
      array(
        'label'     => 'Facebook Link',
        'id'        => 'fb_link',
        'type'      => 'text',
        'desc'      => 'Facebook profile/page link',
        'std'       => '',
        'rows'      => '',
        'post_type' => '',
        'taxonomy'  => '',
        'class'     => '',
        'section'   => 'social'
      ),
//      array(
//        'label'     => 'Pinterest Link',
//        'id'        => 'pinterest_link',
//        'type'      => 'text',
//        'desc'      => 'Pinterest profile/page link',
//        'std'       => '',
//        'rows'      => '',
//        'post_type' => '',
//        'taxonomy'  => '',
//        'class'     => '',
//        'section'   => 'social'
//      ),
      array(
        'label'     => 'Twitter Link',
        'id'        => 'twitter_link',
        'type'      => 'text',
        'desc'      => 'Twitter profile/page link',
        'std'       => '',
        'rows'      => '',
        'post_type' => '',
        'taxonomy'  => '',
        'class'     => '',
        'section'   => 'social'
      ),
      array(
        'label'     => 'Google Plus Link',
        'id'        => 'googleplus_link',
        'type'      => 'text',
        'desc'      => 'Google Plus profile/page link',
        'std'       => '',
        'rows'      => '',
        'post_type' => '',
        'taxonomy'  => '',
        'class'     => '',
        'section'   => 'social'
      ),
      array(
        'label'     => 'Youtube Link',
        'id'        => 'youtube_link',
        'type'      => 'text',
        'desc'      => 'Youtube profile/page link',
        'std'       => '',
        'rows'      => '',
        'post_type' => '',
        'taxonomy'  => '',
        'class'     => '',
        'section'   => 'social'
      ),
//      array(
//        'label'     => 'Linkedin Link',
//        'id'        => 'linkedin_link',
//        'type'      => 'text',
//        'desc'      => 'Linkedin profile/page link',
//        'std'       => '',
//        'rows'      => '',
//        'post_type' => '',
//        'taxonomy'  => '',
//        'class'     => '',
//        'section'   => 'social'
//      ),
//      array(
//        'label'     => 'Instagram Link',
//        'id'        => 'instragram_link',
//        'type'      => 'text',
//        'desc'      => 'Instagram profile/page link',
//        'std'       => '',
//        'rows'      => '',
//        'post_type' => '',
//        'taxonomy'  => '',
//        'class'     => '',
//        'section'   => 'social'
//      ),
//      array(
//        'label'     => 'Xing Link',
//        'id'        => 'xing_link',
//        'type'      => 'text',
//        'desc'      => 'Xing profile/page link',
//        'std'       => '',
//        'rows'      => '',
//        'post_type' => '',
//        'taxonomy'  => '',
//        'class'     => '',
//        'section'   => 'social'
//      ),
//      array(
//        'label'     => 'Tumblr Link',
//        'id'        => 'tumblr_link',
//        'type'      => 'text',
//        'desc'      => 'Tumblr profile/page link',
//        'std'       => '',
//        'rows'      => '',
//        'post_type' => '',
//        'taxonomy'  => '',
//        'class'     => '',
//        'section'   => 'social'
//      ),
      array(
        'label'   => 'Twitter Bar username',
        'id'      => 'twitter_bar_username',
        'type'    => 'text',
        'desc'    => 'Username to pull tweets for',
        'section' => 'twitter'
      ),
      array(
        'label'   => 'Consumer Key',
        'id'      => 'twitter_bar_consumerkey',
        'type'    => 'text',
        'desc'    => 'Visit <a href="https://dev.twitter.com/apps">this link</a> in a new tab, sign in with your account, click on Create a new application and create your own keys in case you dont have already',
        'section' => 'twitter'
      ),
      array(
        'label'   => 'Consumer Secret',
        'id'      => 'twitter_bar_consumersecret',
        'type'    => 'text',
        'desc'    => 'Visit <a href="https://dev.twitter.com/apps">this link</a> in a new tab, sign in with your account, click on Create a new application and create your own keys in case you dont have already',
        'section' => 'twitter'
      ),
      array(
        'label'   => 'Access Token',
        'id'      => 'twitter_bar_accesstoken',
        'type'    => 'text',
        'desc'    => 'Visit <a href="https://dev.twitter.com/apps">this link</a> in a new tab, sign in with your account, click on Create a new application and create your own keys in case you dont have already',
        'section' => 'twitter'
      ),
      array(
        'label'   => 'Access Token Secret',
        'id'      => 'twitter_bar_accesstokensecret',
        'type'    => 'text',
        'desc'    => 'Visit <a href="https://dev.twitter.com/apps">this link</a> in a new tab, sign in with your account, click on Create a new application and create your own keys in case you dont have already',
        'section' => 'twitter'
      ),
      array(
        'label'        => 'Map Zoom Amount',
        'id'           => 'contact_zoom',
        'desc'         => 'Value should be between 1-18, 1 being the entire earth and 18 being right at street level. <small>You can get lat-long coordinates using <a href="http://www.latlong.net/convert-address-to-lat-long.html" target="_blank">Latlong.net</a></small>',
        'std'          => '8',
        'type'         => 'numeric-slider',
        'section'      => 'contact',
        'min_max_step' => '1,18,1'
      ),
      array(
        'label'   => 'Map Center Latitude',
        'id'      => 'map_center_lat',
        'type'    => 'text',
        'desc'    => 'Please enter the latitude for the maps center point. <small>You can get lat-long coordinates using <a href="http://www.latlong.net/convert-address-to-lat-long.html" target="_blank">Latlong.net</a></small>',
        'section' => 'contact'
      ),
      array(
        'label'   => 'Map Center Longtitude',
        'id'      => 'map_center_long',
        'type'    => 'text',
        'desc'    => 'Please enter the longitude for the maps center point.',
        'section' => 'contact'
      ),
      array(
        'label'   => 'Map Infowindow Text',
        'id'      => 'map_pin_info',
        'type'    => 'text',
        'desc'    => 'If you would like to display any text in an info window for your pin, please enter it here.',
        'section' => 'contact'
      ),
      array(
        'label'   => 'Map Pin Image',
        'id'      => 'map_pin_image',
        'type'    => 'upload',
        'desc'    => 'If you would like to use your own pin, you can upload it here',
        'section' => 'contact'
      ),
      array(
        'id'      => 'sidebars_text',
        'label'   => 'About the sidebars',
        'desc'    => 'All sidebars that you create here will appear both in the Widgets Page(Appearance > Widgets), from where you will have to configure them, and in the pages, where you will be able to choose a sidebar for each page',
        'std'     => '',
        'type'    => 'textblock',
        'section' => 'sidebars'
      ),
      array(
        'label'    => 'Create Sidebars',
        'id'       => 'sidebars',
        'type'     => 'list-item',
        'desc'     => 'Please choose a unique title for each sidebar!',
        'section'  => 'sidebars',
        'settings' => array(
          array(
            'label' => 'ID',
            'id'    => 'id',
            'type'  => 'text',
            'desc'  => 'Please write a lowercase id, with <strong>no spaces</strong>'
          )
        )
      )
    )
  );

  /* settings are not the same update the DB */
  if($saved_settings !== $custom_settings)
  {
    update_option('option_tree_settings', $custom_settings);
  }
}

/**
 * Category Colorpicker option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if(!function_exists('ot_type_category_colorpicker'))
{

  function ot_type_category_colorpicker($args = array())
  {

    /* turns arguments array into variables */
    extract($args);

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    $args = array(
      'type'         => 'post',
      'child_of'     => 0,
      'parent'       => '',
      'orderby'      => 'name',
      'order'        => 'ASC',
      'hide_empty'   => 0,
      'hierarchical' => 0,
      'exclude'      => '',
      'include'      => '',
      'number'       => '',
      'taxonomy'     => 'category',
      'pad_counts'   => false
    );

    $categories = get_terms('category', array('hide_empty' => false,));

    foreach($categories as $category)
    {
      $field_id    = 'category_colors_'.$category->term_id;
      $field_name  = 'option_tree[category_colors_'.$category->term_id.']';
      $field_value = ot_get_option($field_id);

      /* format setting outer wrapper */
      echo '<div class="format-setting type-colorpicker has-desc format-settings">';

      /* description */
      echo '<div class="description">Category color for "'.$category->name.'"</div>';

      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';

      /* build colorpicker */
      echo '<div class="option-tree-ui-colorpicker-input-wrap">';

      /* colorpicker JS */
      echo '<script>jQuery(document).ready(function($) { OT_UI.bind_colorpicker("'.esc_attr($field_id).'"); });</script>';

      /* input */
      echo '<input type="text" name="'.esc_attr($field_name).'" id="'.esc_attr($field_id).'" value="'.esc_attr($field_value).'" class="widefat option-tree-ui-input cp_input '.esc_attr($field_class).'" autocomplete="off" />';

      /* set border color */
      $border_color = in_array($field_value, array('#FFFFFF', '#FFF', '#ffffff', '#fff')) ? '#ccc' : esc_attr($field_value);

      echo '<div id="cp_'.esc_attr($field_id).'" class="cp_box"'.( $field_value ? " style='background-color:".esc_attr($field_value)."; border-color:$border_color;'" : '' ).'></div>';

      echo '</div>';

      echo '</div>';

      echo '</div>';
    }
  }

}
/**
 * Category Order option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */

/**
 * Filter in the Category Order option type
 */
function add_ot_type_category_order($array)
{

  $array['category-order'] = 'Category Order';

  return $array;
}

add_filter('ot_option_types_array', 'add_ot_type_category_order');

/**
 * Category Order option type
 */
if(!function_exists('ot_type_category_order'))
{

  function ot_type_category_order($args = array())
  {

    /* turns arguments array into variables */
    extract($args);

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-category-order '.( $has_desc ? 'has-desc' : 'no-desc' ).'">';

    /* description */
    echo $has_desc ? '<div class="description">'.htmlspecialchars_decode($field_desc).'</div>' : '';

    /* format setting inner wrapper */
    echo '<div class="format-setting-inner">';

    /* pass the settings array arround */
    echo '<input type="hidden" name="'.esc_attr($field_id).'_settings_array" id="'.esc_attr($field_id).'_settings_array" value="'.ot_encode(serialize($field_settings)).'" />';

    /**
     * settings pages have array wrappers like 'option_tree'.
     * So we need that value to create a proper array to save to.
     * This is only for NON metaboxes settings.
     */
    if(!isset($get_option))
      $get_option = '';

    /* build list items */
    echo '<ul class="option-tree-setting-wrap option-tree-sortable" data-name="'.esc_attr($field_id).'" data-id="'.esc_attr($post_id).'" data-get-option="'.esc_attr($get_option).'" data-type="'.esc_attr($type).'">';

    /* make life easier */
    $_field_name = $get_option ? $get_option.'['.$field_id.']' : $field_id;

    /* Empty categories array */
    $categories = array();

    /* Get all the categories */
    $args        = array(
      'hide_empty'   => 0,
      'orderby'      => 'name',
      'order'        => 'ASC',
      'hierarchical' => false
    );
    $_categories = get_terms('category', $args);

    /* Create an array we can use to store categories in the proper format */
    foreach($_categories as $category)
    {
      $categories[] = array(
        'title' => $category->name,
        'id'    => $category->term_id
      );
    }

    /* Set the $field_value array */
    if(( is_array($field_value) && !empty($field_value) ) || !empty($categories))
    {

      /* If there is already a value merge in the categories on the fly if one is added via WP */
      if(is_array($field_value) && !empty($field_value))
      {

        /* Empty arrays */
        $ids            = array();
        $new_categories = array();

        /* Build ID array */
        foreach($field_value as $cat)
        {
          $ids[] = $cat['id'];
        }

        /* New categories array */
        foreach($categories as $cat)
        {
          if(!in_array($cat['id'], $ids))
          {
            $new_categories[] = $cat;
          }
        }

        /* Merge arrays */
        $field_value = array_merge($field_value, $new_categories);

        /* Remove empty categories */
        foreach($field_value as $key => $cat)
        {
          if(!get_posts('category='.$cat['id']))
          {
            unset($field_value[$key]);
          }
        }

        /* Set to the default categories */
      }
      else
      {

        $field_value = $categories;
      }

      /* Loop over array */
      foreach($field_value as $key => $category)
      {

        echo '<li class="ui-state-default list-list-item">';

        echo '<div class="option-tree-setting">';

        echo '<div class="open">'.$category['title'].'</div>';

        echo '<input type="hidden" name="'.esc_attr($_field_name.'['.$key.'][title]').'" value="'.esc_attr($category['title']).'" />';

        echo '<input type="hidden" name="'.esc_attr($_field_name.'['.$key.'][id]').'" value="'.esc_attr($category['id']).'" />';

        echo '</div>';

        echo '</li>';
      }
    }

    echo '</ul>';

    /* description */
    echo '<div class="list-item-description">'.apply_filters('ot_list_item_description', __('You can re-order with drag & drop, the order will update after saving.', 'option-tree'), $field_id).'</div>';

    echo '</div>';

    echo '</div>';
  }

}
/**
 * Pure Gallery option type. https://gist.github.com/purethemes/5381815
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if(!function_exists('ot_type_puregallery'))
{

  function ot_type_puregallery($args = array())
  {
    /* turns arguments array into variables */
    extract($args);
    global $post;

    $current_post_id = $post->ID;

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    /* format setting outer wrapper */
    echo '<div class="format-setting type-post_attachments_checkbox type-checkbox '.( $has_desc ? 'has-desc' : 'no-desc' ).'">';

    /* description */
    echo $has_desc ? '<div class="description">'.htmlspecialchars_decode($field_desc).' <br/><a href="#" class="delete-gallery">Delete gallery</a></div>' : '';

    /* format setting inner wrapper */
    echo '<div class="format-setting-inner">';

    /* setup the post types */
    $post_type = isset($field_post_type) ? explode(',', $field_post_type) : array('post');
    global $pagenow;
    if($pagenow == 'themes.php')
    {
      $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'post_mime_type' => 'image',
        'post__in'       => explode(",", $field_value),
        'posts_per_page' => '-1',
        'orderby'        => 'post__in'
      );
    }
    else
    {
      $args = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'post__in'       => explode(",", $field_value),
        'post_mime_type' => 'image',
        'posts_per_page' => '-1',
        'orderby'        => 'post__in'
      );
    }

    /* query posts array */
    $my_posts = get_posts($args);

    /* has posts */
    echo '<input type="hidden" name="'.esc_attr($field_name).'" id="'.esc_attr($field_id).'" value="'.esc_attr($field_value).'" class="widefat option-tree-ui-input '.esc_attr($field_class).'" />';
    if(is_array($my_posts) && !empty($my_posts))
    {
      echo '<ul style="margin:0px" id="option-tree-gallery-list">';
      foreach($my_posts as $my_post)
      {
        echo '<li>';
        $thumbnail = wp_get_attachment_image_src($my_post->ID, 'thumbnail');
        echo '<img  src="'.$thumbnail[0].'" width="60" height="60" />';
        echo '</li>';
      }
      echo "</ul>";
      echo '<a title="Add images" class="option-tree-attachments-update option-tree-ui-button blue right hug-right addgallery" href="#">Edit Slider Gallery</a>';
    }
    else
    {
      echo '<ul style="margin:0px" id="option-tree-gallery-list"></ul><p>'.__('No Gallery', 'option-tree').'</p>';
      echo '<a title="Add images" class="option-tree-attachments-update option-tree-ui-button blue right hug-right addgallery" href="#">Create Slider Gallery</a>';
    }

    echo '</div>';
    echo '</div>';

    wp_reset_query();
  }

  //fake and dirty shortcode for stupid media uploader
  function media_view_settings($settings, $post)
  {
    if(!is_object($post))
      return $settings;
    $shortcode = '[gallery ';
    $ids       = get_post_meta($post->ID, 'pp_gallery_slider', TRUE);
    $ids       = explode(",", $ids);

    if(is_array($ids))
      $shortcode .= 'ids = "'.implode(',', $ids).'"]';
    else
      $shortcode .= "id = \"{$post->ID}\"]";
    $settings['thbgallery'] = array('shortcode' => $shortcode);
    return $settings;
  }

  add_filter('media_view_settings', 'media_view_settings', 10, 2);

  function ot_type_attachments_ajax_update()
  {
    if(!empty($_POST['ids']))
    {
      $args      = array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'post__in'       => $_POST['ids'],
        'post_mime_type' => 'image',
        'posts_per_page' => '-1',
        'orderby'        => 'post__in'
      );
      $return    = '';
      /* query posts array */
      $query     = new WP_Query($args);
      $post_type = isset($field_post_type) ? explode(',', $field_post_type) : array('post');
      /* has posts */
      if($query->have_posts())
      {
        while($query->have_posts())
        {
          $query->the_post();
          $return .= '<li>';
          $thumbnail = wp_get_attachment_image_src($query->post->ID, 'thumbnail');
          $return .= '<img  src="'.$thumbnail[0].'" width="60" height="60" />';
          $return .= '</li>';
        }
      }
      else
      {
        $return .= '<p>'.__('No Posts Found', 'option-tree').'</p>';
      }
      wp_reset_query();
      echo $return;
      exit();
    }
  }

  add_action('wp_ajax_attachments_update', 'ot_type_attachments_ajax_update');
}