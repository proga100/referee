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



function referee_transaction_custom_post_type() {
    $labels = array(
        'name'               => _x( 'Referees Appointments', 'post type general name' ),
        'singular_name'      => _x( 'Referee Appointment', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'book' ),
        'add_new_item'       => __( 'Add New Referee Appointment' ),
        'edit_item'          => __( 'Edit Referee Appointment' ),
        'new_item'           => __( 'New Referee Appointment' ),
        'all_items'          => __( 'All Referees Appointments' ),
        'view_item'          => __( 'View Referee Appointment' ),
        'search_items'       => __( 'Search Referee Appointments' ),
        'not_found'          => __( 'No Referee Appointments found' ),
        'not_found_in_trash' => __( 'No Referee Appointments found in the Trash' ),
        'parent_item_colon'  => '',
           'menu_name'          => 'Referees Appointments'
  );
  $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our Referees Appointments and Referees Appointments specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array(  'title' ),
      'has_archive'   => true,
  );
    register_post_type( 'referee_transactions', $args );
}

add_action( 'init', 'referee_transaction_custom_post_type' );


/**
 * Adds a submenu page under a custom post type parent.
 */
function register_referees_transactions() {
    add_submenu_page(
        'edit.php?post_type=referee_transactions',
        __( 'Referees Appointments Upload page', 'textdomain' ),
        __( 'Referees  Appointments Upload page', 'textdomain' ),
        'edit_posts',
        'referees_transaction_upload',
        'referees_transactions_callback'
    );
}

/**
 * Display callback for the submenu page.
 */
function referees_transactions_callback() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'Referees Appointment Upload page', 'textdomain' ); ?></h1>
        <?php  upload_file_referees_upload() ?>


    </div>
<?php
}
add_action('admin_menu', 'register_referees_transactions');


function upload_file_referees_upload(){

    if(!defined(ABSPATH)){
        $absolute_path = __FILE__;
        $path_to_file = explode( 'wp-content', $absolute_path );
        $path_to_wp = $path_to_file[0];



        require_once( $path_to_wp . '/wp-load.php' );
    }



    ?>

    <h1>Excel Referees Appointments Excel file Upload Form</h1>
    <p>Choose an  file to upload.</p>
    <form id="upload_form" action="<?php echo get_site_url(); ?>/wp-content/plugins/referee/upload_referee_transactions.php" enctype="multipart/form-data" method="post" target="messages">
 <p><input name="upload" id="upload" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /></p>
        <p><input id="btnSubmit" type="submit" value="Upload Referees Appointments" /></p>
        <iframe name="messages" id="messages"></iframe>
        <p><input id="reset_upload_form" type="reset" value="Reset form" /></p>
    </form>

    <script>

        jQuery(document).ready(function( $ ) {
            $('#upload_form').on('submit', function(evt) {
                var form     = $(evt.target),
                    fileElt  = form.find('input#upload'),

                    fileName = fileElt.val(),
                    messages = form.find('iframe#messages').contents().find("body"),
                    maxSize  = 12000000,
                    fail     = function(msg) {
                        messages.append( $('<P>').css('color', 'red').text(msg) );
                        fileElt.focus();
                    };

                console.log(fileName);

                if (fileName.length == 0) {
                    fail('Please select a file.');
                }
                else if (!/\.(xls|xlsx)$/.test(fileName)) {
                    fail('Only  Excel files are permitted.');
                }
                else {
                    var file = fileElt.get(0).files[0];
                    if (file.size > maxSize) {
                        fail('The file is too large. The maxieeemum size is ' + maxSize + ' bytes.');
                    }
                    else {
                        return true;
                    }
                }
                return false;
            });
        });

    </script>

    <style>
        iframe#messages {
            width: 100%;
            height: 300px;
        }
    </style>


    <?php

    return ;
}


// function that runs when shortcode is called
function referee_transactions() {

    $referee_id = $_REQUEST['referee_id'];


    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'referee_transactions'

    ));

    $meta_key = 'referee_id';

    $post_id =  post_transaction_post_id( $meta_key, $referee_id );

    $firstname =   get_post_meta( $post_id, 'first_name', true );
    $lastname =   get_post_meta( $post_id, 'last_name', true );
    $html = '<h3> '.$firstname.'  '. $lastname.' Appointments</h3>';

    $custom_fields = array(
        'referee_id_number' => 'Referee Id',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'date' => 'Date',
        'role' => 'Role',
        'home_team' => 'Home Team',
        'away_team' => 'Away Team',

        'time' => 'Time',
        'venue' => 'Venue',
        'status'=> 'Status'
    );

    $import =  home_url().'/wp-admin/edit.php?post_type=referee_transactions&page=referees_transaction_upload';

    $html .= fdatatable( $referee_id, $posts, $custom_fields, $import );

    return $html;
}
// register shortcode
add_shortcode('referee_transactions', 'referee_transactions');





/*
 * Returns matched post IDs for a pair of meta key and meta value from database
 *
 * @param string $meta_key
 * @param mixed $meta_value
 *
 * @return array|int Post ID(s)
 */
function post_transaction_post_id( $meta_key, $meta_value ){
    global $wpdb;

    $ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $meta_key, $meta_value ) );

    if( count( $ids ) > 1 )
        return $ids; // return array
    else
        return $ids[0]; // return int
}














