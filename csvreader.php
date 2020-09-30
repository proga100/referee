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

require_once __DIR__.'/src/SimpleCSV.php';


function csvreader($filepath)
{

    $handle = fopen($filepath, "r") or die("Error opening file");

    $i = 0;
  //      echo "<pre>";
    if ( $csv = SimpleCSV::import($filepath) ) {

        // Produce array keys from the array values of 1st array element
        $header_values = $rows = [];

        foreach ($csv as $k => $r) {
            if ($k === 0) {
                $header_values = $r;
                continue;
            }elseif($r) {
                $rows[] = array_combine($header_values, $r);
            }
        }

    }

    $referees = array_filter($rows);
    csvtoreferee($referees);
   die('Referee lists was successfully imported.');
}

function csvtoreferee($referees_data){
    global $custom_fields;
    $allposts = get_posts(array('post_type' => 'referee', 'numberposts' => -1));
    foreach ($allposts as $eachpost) {
     //  wp_delete_post($eachpost->ID, true);
    }

    foreach ($referees_data as $referee) {

      //  echo "<pre>";     print_r ($referee);

        $referee_id = $referee['﻿Referee ID Number'];

        if ($referee_id){

            $referee_id = $referee['﻿Referee ID Number'];

               echo  'refere id '.$referee_id;

            $post_id = misha_post_id_by_meta_key_and_value('referee_id', $referee_id);

            //     print_r ($post_id);
            if ($post_id) {


                // Update post 1
                $my_post = array(
                    'ID' => $post_id,
                    'post_title' => $referee["First name"] . ' ' . $referee["Last name"],
                );

                // Update the post into the database
                wp_update_post($my_post);

                foreach ($custom_fields as $key => $label) {

                    update_post_meta($post_id, $key, $referee[$label]);
                }

                echo 'referee id data ' . $post_id . ' updated <br/>';
            } else {


                $new_post = array(
                    'post_type' => 'referee',
                    'post_title' => $referee["First name"] . ' ' . $referee["Last name"],
                    'post_status' => 'publish',
                    'comment_status' => 'closed',   // if you prefer
                    'ping_status' => 'closed',      // if you prefer
                );

                $post_id = wp_insert_post($new_post, true);

                echo 'referee id data ' . $post_id . ' inserted <br/>';

                if (is_wp_error($post_id)) {
                    $errors = $post_id->get_error_messages();
                    foreach ($errors as $error) {
                        echo "- " . $error . "<br />";
                    }
                }

                foreach ($custom_fields as $key => $label) {


                     update_post_meta($post_id, $key, $referee[$label]);
                }


            }

         //     die();
        }
    }

}

/*
 * Returns matched post IDs for a pair of meta key and meta value from database
 *
 * @param string $meta_key
 * @param mixed $meta_value
 *
 * @return array|int Post ID(s)
 */
function misha_post_id_by_meta_key_and_value( $meta_key, $meta_value ){
    global $wpdb;

    $ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s", $meta_key, $meta_value ) );

    if( count( $ids ) > 1 )
        return $ids; // return array
    else
        return $ids[0]; // return int
}

function correct_encoding($text) {
    $current_encoding = mb_detect_encoding($text, 'auto');
    $text = iconv($current_encoding, 'UTF-8', $text);
    return $text;
}




