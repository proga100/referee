<?php
//provides access to WP environment
if(!defined(ABSPATH)){
    $absolute_path = __FILE__;
    $path_to_file = explode( 'wp-content', $absolute_path );
    $path_to_wp = $path_to_file[0];
    require_once( $path_to_wp . '/wp-load.php' );
}

//ini_set('error_reporting', E_ALL); ini_set('display_errors', true);

require_once __DIR__.'/src/SimpleXLSX.php';




/* import
you get the following information for each file:
$_FILES['field_name']['name']
$_FILES['field_name']['size']
$_FILES['field_name']['type']
$_FILES['field_name']['tmp_name']
*/
//echo $path_to_wp;

define('ALLOW_UNFILTERED_UPLOADS', true);


if($_FILES['upload']['name']) {
    if(!$_FILES['upload']['error']) {
        //validate the file
        $new_file_name = strtolower($_FILES['upload']['tmp_name']);
        //can't be larger than 300 KB
        if($_FILES['upload']['size'] > (12000000)) {
            //wp_die generates a visually appealing message element
            wp_die('Your file size is to large.');
        }
        else {
            //the file has passed the test
            //These files need to be included as dependencies when on the front end.
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );

            // Let WordPress handle the upload.
            // Remember, 'upload' is the name of our file input in our form above.


            $file_id = media_handle_upload( 'upload', 0 );



            if ( is_wp_error( $file_id ) ) {
                wp_die('Error loading file!');
            } else {


                $fullsize_path = get_attached_file( $file_id ); // Full path
               // csvreader( $fullsize_path);

                reader_fitness_excel($fullsize_path);
                wp_die('Your file was successfully imported.');


            }
        }
    }
    else {
        //set that to be the returned message
        wp_die('Error: '.$_FILES['upload']['error']);
    }

    define('ALLOW_UNFILTERED_UPLOADS', false);
}


function reader_fitness_excel($fullsize_path){

    if ( $xlsx = SimpleXLSX::parse($fullsize_path)) {

        // Produce array keys from the array values of 1st array element
        $header_values = $rows = [];

        foreach ($xlsx->rows() as $k => $r) {
            if ($k === 0) {
                $header_values = $r;
                continue;
            }
            $rows[] = array_combine($header_values, $r);
        }

            /*
           $allposts = get_posts(array('post_type' => 'referee_transactions', 'numberposts' => -1));
           foreach ($allposts as $eachpost) {
               wp_delete_post($eachpost->ID, true);
           }
            */

         //  echo '<pre>';   print_r($rows);

        foreach ($rows as $fvalue){

            $new_post = array(
                'post_type' => 'fitness_test_result',
                'post_title' => $fvalue["First Name"] . ' ' . $fvalue["Last Name"],
                'post_status' => 'publish',
                'comment_status' => 'closed',   // if you prefer
                'ping_status' => 'closed',      // if you prefer
            );


            $post_id = wp_insert_post($new_post, true);

            $custom_fields = array(
                'Referee ID Number'=>'referee_id_number',
                'First Name' =>'first_name_fitness'  ,
                'Last Name' =>'fitness_last_name'  ,
                'Fitness Test Date'=>'fitness_test_date',
                'Fitness Test Result'=>'fitness_test_result'
            );

            foreach ($fvalue as $fkey=>$fieldvalue){

                if ($fkey == 'Fitness Test Result'){
                    $date = date_create($fieldvalue);
                    $fieldvalue = date_format($date, 'H:i');
                                 }
                 echo $custom_fields[$fkey].' '.$fieldvalue.' ';
                 update_field($custom_fields[$fkey], $fieldvalue , $post_id);
            }

            echo '<br />';
       }
         //  get_field('field_name', $post->ID);
    }
}



