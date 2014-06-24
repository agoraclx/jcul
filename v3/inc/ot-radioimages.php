<?php
function filter_radio_images( $array, $field_id ) {
  
  /* only run the filter where the field ID is my_radio_images */
  if ( $field_id == 'sidebar_position' ) {
    $array = array(
      array(
        'value'   => 'left-sidebar',
        'label'   => __( 'Left Sidebar', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/left-sidebar.png'
      ),
      array(
        'value'   => 'right-sidebar',
        'label'   => __( 'Right Sidebar', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/right-sidebar.png'
      )
    );
  }
  if ( $field_id == 'slider_position' ) {
    $array = array(
      array(
        'value'   => 'slider-left',
        'label'   => __( 'Left Aligned Slider', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/slider-left.png'
      ),
      array(
        'value'   => 'slider-right',
        'label'   => __( 'Right Aligned Slider', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/slider-right.png'
      )
    );
  }
  if ( $field_id == 'footer_columns' ) {
    $array = array(
      array(
        'value'   => 'fourcolumns',
        'label'   => __( 'Four Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/four-columns.png'
      ),
      array(
        'value'   => 'threecolumns',
        'label'   => __( 'Three Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/three-columns.png'
      ),
      array(
        'value'   => 'twocolumns',
        'label'   => __( 'Two Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/two-columns.png'
      ),
      array(
        'value'   => 'doubleleft',
        'label'   => __( 'Double Left Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/doubleleft-columns.png'
      ),
      array(
        'value'   => 'doubleright',
        'label'   => __( 'Double Right Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/doubleright-columns.png'
      ),
      array(
        'value'   => 'sixcolumns',
        'label'   => __( 'Six Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/six-columns.png'
      )
      
    );
  }
  
  if ( $field_id == 'portfolio_columns' ) {
    $array = array(
      array(
        'value'   => 'three',
        'label'   => __( 'Four Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/four-columns.png'
      ),
      array(
        'value'   => 'four',
        'label'   => __( 'Three Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/three-columns.png'
      ),
      array(
        'value'   => 'six',
        'label'   => __( 'Two Columns', 'option-tree' ),
        'src'     => get_template_directory_uri() . '/assets/img/admin/two-columns.png'
      )
      
    );
  }
  return $array;
  
}
add_filter( 'ot_radio_images', 'filter_radio_images', 10, 2 );