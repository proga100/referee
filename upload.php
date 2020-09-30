<?php



//provides access to WP environment
if(!defined(ABSPATH)){
    $absolute_path = __FILE__;
    $path_to_file = explode( 'wp-content', $absolute_path );
    $path_to_wp = $path_to_file[0];



    require_once( $path_to_wp . '/wp-load.php' );
}

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
        if($_FILES['upload']['size'] > (1000000000)) {
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
                csvreader( $fullsize_path);
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