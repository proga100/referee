<?php
/*
Plugin Name: Referee Custom Type
Plugin URI: https://github.com/
Description: Creates referee custom post types
Author: flance.info
Version: 1.0.0
Author URI: http://flance.info
Text Domain: referee
Domain Path: /languages
License: GPLv2
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function referee_custom_post_type() {
    $labels = array(
        'name'               => _x( 'Referees', 'post type general name' ),
        'singular_name'      => _x( 'Referee', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'book' ),
        'add_new_item'       => __( 'Add New Referee' ),
        'edit_item'          => __( 'Edit Referee' ),
        'new_item'           => __( 'New Referee' ),
        'all_items'          => __( 'All Referees' ),
        'view_item'          => __( 'View Referee' ),
        'search_items'       => __( 'Search Referees' ),
        'not_found'          => __( 'No Referee found' ),
        'not_found_in_trash' => __( 'No Referees found in the Trash' ),
        'parent_item_colon'  => '',
           'menu_name'          => 'Referees'
  );
  $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our Referees and Referee specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array(  'title','thumbnail' ),
      'has_archive'   => true,
  );
    register_post_type( 'referee', $args );
}

add_action( 'init', 'referee_custom_post_type' );



function my_updated_messages( $messages ) {
    global $post, $post_ID;
    $messages['product'] = array(
        0 => '’',
    1 => sprintf( __('Referee updated. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.'),
    3 => __('Custom field deleted.'),
    4 => __('Referee updated.'),
    5 => isset($_GET['revision']) ? sprintf( __('Referee restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Referee published. <a href="%s">View product</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Referee saved.'),
    8 => sprintf( __('Referee submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Referee scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Referee draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
}

add_filter( 'post_updated_messages', 'my_updated_messages' );


function taxonomies_referee() {
    $labels = array(
        'name'              => _x( 'Referee Categories', 'taxonomy general name' ),
        'singular_name'     => _x( 'Referee Category', 'taxonomy singular name' ),
        'search_items'      => __( 'Search Referee Categories' ),
        'all_items'         => __( 'All Referee Categories' ),
        'parent_item'       => __( 'Parent Referee Category' ),
        'parent_item_colon' => __( 'Parent Referee Category:' ),
        'edit_item'         => __( 'Edit Referee Category' ),
        'update_item'       => __( 'Update Referee Category' ),
        'add_new_item'      => __( 'Add New Referee Category' ),
        'new_item_name'     => __( 'New Referee Category' ),
        'menu_name'         => __( 'Referee Categories' ),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy( 'referee_category', 'referee', $args );
}

add_action( 'init', 'taxonomies_referee', 0 );


add_action( 'add_meta_boxes', 'referee_lastname_box' );

function referee_lastname_box() {

    // name of custome fields
    $custom_fields = array(

        'referee_id' => 'Referee ID Number',
        'first_name' => 'First Name',
        'middle_name' => 'Middle Name',
        'last_name' => 'Last Name',
        'date_birth' => 'Date of Birth',
        'age' =>  'Age',
        'nrd_id'  => 'NRD ID',
        'gender' => 'Gender',
        'email' => 'Email Address',
        'ethnicity' => 'Ethnicity',
        'home_phone'=> 'Home Phone',
        'mobile_phone'=> 'Mobile Phone',
        'address' => 'Address',
        'suburb' => 'Suburb',
        'city'   => 'City',
        'post_code' => 'Post Code',
        'occupation' => 'Occupation',
        'active referee' => 'Active Referee',
        'primary_function' => 'Primary Function',
        'under18'  => 'Under 18',
        'parent_first_name' => 'Parent First Name',
        'parent_last_name' => 'Parent Last Name',
        'parent_contact_phone_number' => 'Parent Contact Phone Number',
        'panel'=>'Panel',
        'years_started' => 'Year started with NHRRA',
        'fitness_test_date' => 'Fitness Test Date & Result'
    );

    foreach ($custom_fields as $key=>$label){
        add_meta_box(
            'referee_'.$key.'_box',
            __( $label, 'myplugin_textdomain' ),
            'referee_lastname_box_content',
            'referee',
            'normal',
            'high',
            array($key,$label)
        );
    }


}

function referee_lastname_box_content( $post,$args ) {

   // print_r ($args);
    wp_nonce_field( plugin_basename( __FILE__ ), 'referee_lastname_box_content_nonce' );
    echo '<label for="'.$args['arg'][1].'"></label>';

   $last_name = get_post_meta( get_the_ID(), $args['args'][0], true );
    echo '<input type="text" id="'.$args['args'][0].'" name="'.$args['args'][0].'" placeholder="'.$args['args'][1].'" value="'. $last_name.'"/>';
}




add_action( 'save_post', 'referee_lastname_box_save' );

function referee_lastname_box_save( $post_id ) {

    $custom_fields = array(

        'referee_id' => 'Referee ID Number',
        'first_name' => 'First Name',
        'middle_name' => 'Middle Name',
        'last_name' => 'Last Name',
        'date_birth' => 'Date of Birth',
        'age' =>  'Age',
        'nrd_id'  => 'NRD ID',
        'gender' => 'Gender',
        'email' => 'Email Address',
        'ethnicity' => 'Ethnicity',
        'home_phone'=> 'Home Phone',
        'mobile_phone'=> 'Mobile Phone',
        'address' => 'Address',
        'suburb' => 'Suburb',
        'city'   => 'City',
        'post_code' => 'Post Code',
        'occupation' => 'Occupation',
        'active referee' => 'Active Referee',
        'primary_function' => 'Primary Function',
        'under18'  => 'Under 18',
        'parent_first_name' => 'Parent First Name',
        'parent_last_name' => 'Parent Last Name',
        'parent_contact_phone_number' => 'Parent Contact Phone Number',
        'panel'=>'Panel',
        'years_started' => 'Year started with NHRRA',
        'fitness_test_date' => 'Fitness Test Date & Result'
    );

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return;

    if ( !wp_verify_nonce( $_POST['referee_lastname_box_content_nonce'], plugin_basename( __FILE__ ) ) )
        return;

    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
            return;
    } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
            return;
    }

    foreach ($custom_fields as $key=>$label){

        $custome_field_val = $_POST[$key];

        update_post_meta( $post_id, $key, $custome_field_val  );
    }



}


