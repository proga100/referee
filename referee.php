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
if (!defined('ABSPATH')) {
	exit;
}

function register_session()
{
	//  if( !session_id() )        session_start();
}

add_action('init', 'register_session');

global $custom_fields; 
show_admin_bar(false); 
// name of custome fields
$custom_fields = array(
	'referee_id' => 'Referee ID Number',
	'first_name' => 'First name',
	'middle_name' => 'Middle name',
	'last_name' => 'Last name',
	'date_birth' => 'Date of Birth',
	'age' => 'Age',
	'nrd_id' => 'NrdId',
	'gender' => 'Gender',
	'email' => 'Personal email address',
	'ethnicity' => 'Ethnicity 1',
	'home_phone' => 'Home phone number',
	'mobile_phone' => 'Mobile phone number',
	'address' => 'Address',
	'suburb' => 'Suburb',
	'city' => 'Town/City',
	'post_code' => 'Postcode',
	'occupation' => 'Your Occupation',
	'active_referee' => 'Active Referee',
	'primary_function' => 'Primary Function (non active referee)',
	'under18' => 'Under 18',
	'parent_first_name' => 'Parent/Guardian first name (If  under 18)',
	'parent_last_name' => 'Parent/Guardian last name (If  under 18)',
	'parent_contact_phone_number' => 'Parent Contact Phone Number',
	'panel' => 'Panel',
	'years_started' => 'Year Started with NHRRA',
	'fitness_test_date' => 'Fitness Test Date',
	'Fitness_Test_Result' => 'Fitness Test Result',
	'Life_Member' => 'Life Member',
	'Board_Member' => 'Board Member',
	'4th_Official' => '4th Official',
	'Referee_Coach' => 'Referee Coach',
	'Selection_Committee' => 'Selection Committee'
);

function referee_custom_post_type()
{
	$labels = array(
		'name' => _x('Referees', 'post type general name'),
		'singular_name' => _x('Referee', 'post type singular name'),
		'add_new' => _x('Add New', 'book'),
		'add_new_item' => __('Add New Referee'),
		'edit_item' => __('Edit Referee'),
		'new_item' => __('New Referee'),
		'all_items' => __('All Referees'),
		'view_item' => __('View Referee'),
		'search_items' => __('Search Referees'),
		'not_found' => __('No Referee found'),
		'not_found_in_trash' => __('No Referees found in the Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Referees'
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Holds our Referees and Referee specific data',
		'public' => true,
		'menu_position' => 5,
		'supports' => array('title'),
		'has_archive' => true,
	);
	register_post_type('referee', $args);
}

add_action('init', 'referee_custom_post_type'); 
function my_updated_messages($messages)
{
	global $post, $post_ID;
	$messages['product'] = array(
		0 => '’',
		1 => sprintf(__('Referee updated. <a href="%s">View product</a>'), esc_url(get_permalink($post_ID))),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('Referee updated.'),
		5 => isset($_GET['revision']) ? sprintf(__('Referee restored to revision from %s'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
		6 => sprintf(__('Referee published. <a href="%s">View product</a>'), esc_url(get_permalink($post_ID))),
		7 => __('Referee saved.'),
		8 => sprintf(__('Referee submitted. <a target="_blank" href="%s">Preview product</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
		9 => sprintf(__('Referee scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
		10 => sprintf(__('Referee draft updated. <a target="_blank" href="%s">Preview product</a>'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
	);
	return $messages;
} 
add_filter('post_updated_messages', 'my_updated_messages'); 
function taxonomies_referee()
{
	$labels = array(
		'name' => _x('Referee Categories', 'taxonomy general name'),
		'singular_name' => _x('Referee Category', 'taxonomy singular name'),
		'search_items' => __('Search Referee Categories'),
		'all_items' => __('All Referee Categories'),
		'parent_item' => __('Parent Referee Category'),
		'parent_item_colon' => __('Parent Referee Category:'),
		'edit_item' => __('Edit Referee Category'),
		'update_item' => __('Update Referee Category'),
		'add_new_item' => __('Add New Referee Category'),
		'new_item_name' => __('New Referee Category'),
		'menu_name' => __('Referee Categories'),
	);
	$args = array(
		'labels' => $labels,
		'hierarchical' => true,
	);
	register_taxonomy('referee_category', 'referee', $args);
}

// add_action( 'init', 'taxonomies_referee', 0 ); 
add_action('add_meta_boxes', 'referee_lastname_box');

function referee_lastname_box()
{

	// name of custome fields
	$custom_fields = array(
		'referee_id' => 'Referee ID Number',
		'first_name' => 'First Name',
		'middle_name' => 'Middle Name',
		'last_name' => 'Last Name',
		'date_birth' => 'Date of Birth',
		'age' => 'Age',
		'nrd_id' => 'NRD ID',
		'gender' => 'Gender',
		'email' => 'Email Address',
		'ethnicity' => 'Ethnicity',
		'home_phone' => 'Home Phone',
		'mobile_phone' => 'Mobile Phone',
		'address' => 'Address',
		'suburb' => 'Suburb',
		'city' => 'City',
		'post_code' => 'Post Code',
		'occupation' => 'Occupation',
		'active_referee' => 'Active Referee',
		'primary_function' => 'Primary Function',
		'under18' => 'Under 18',
		'parent_first_name' => 'Parent First Name',
		'parent_last_name' => 'Parent Last Name',
		'parent_contact_phone_number' => 'Parent Contact Phone Number',
		'panel' => 'Panel',
		'years_started' => 'Year started with NHRRA',
		// 'fitness_test_date' => 'Fitness Test Date & Result'
		'Life_Member' => 'Life Member',
		'Board_Member' => 'Board Member',
		'4th_Official' => '4th Official',
		'Referee_Coach' => 'Referee Coach',
		'Selection_Committee' => 'Selection Committee'
	); 
	foreach ($custom_fields as $key => $label) {
		add_meta_box(
			'referee_' . $key . '_box',
			__($label, 'myplugin_textdomain'),
			'referee_lastname_box_content',
			'referee',
			'normal',
			'high',
			array($key, $label)
		);
	} 
} 
function referee_lastname_box_content($post, $args)
{

	// print_r ($args);

	$custom_fields_input_type = array(
		'referee_id' => 'text',
		'first_name' => 'text',
		'middle_name' => 'text',
		'last_name' => 'text',
		'date_birth' => 'date',
		'age' => 'number',
		'nrd_id' => 'number',
		'gender' => 'dropdown',
		'email' => 'email',
		'ethnicity' => 'text',
		'home_phone' => 'number',
		'mobile_phone' => 'number',
		'address' => 'text',
		'suburb' => 'text',
		'city' => 'text',
		'post_code' => 'number',
		'occupation' => 'text',
		'active_referee' => 'dropdown',
		'primary_function' => 'dropdown',
		'under18' => 'dropdown',
		'parent_first_name' => 'text',
		'parent_last_name' => 'text',
		'parent_contact_phone_number' => 'number',
		'panel' => 'number',
		'years_started' => 'number',
		'fitness_test_date' => 'date',
		'Life_Member' => 'dropdown',
		'Board_Member' => 'dropdown',
		'4th_Official' => 'dropdown',
		'Referee_Coach' => 'dropdown',
		'Selection_Committee' => 'dropdown'
	);
	wp_nonce_field(plugin_basename(__FILE__), 'referee_lastname_box_content_nonce');
	echo '<label for="' . $args['arg'][1] . '"></label>';

	$yesnooption = array('active_referee', 'under18', 'Life_Member', 'Board_Member', '4th_Official', 'Referee_Coach', 'Selection_Committee');
	$genderoption = array('gender');

	$value = $last_name = get_post_meta(get_the_ID(), $args['args'][0], true);
	if ($custom_fields_input_type[$args['args'][0]] == 'text') {
		echo '<input type="text" id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" placeholder="' . $args['args'][1] . '" value="' . $last_name . '"/>';
	} elseif ($custom_fields_input_type[$args['args'][0]] == 'number') {
		echo '<input type="number" id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" placeholder="' . $args['args'][1] . '" value="' . $last_name . '"/>';
	} elseif ($custom_fields_input_type[$args['args'][0]] == 'email') {
		echo '<input type="email" id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" placeholder="' . $args['args'][1] . '" value="' . $last_name . '"/>';
	} elseif ($custom_fields_input_type[$args['args'][0]] == 'date') {
		echo '<input type="date" id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" placeholder="' . $args['args'][1] . '" value="' . $last_name . '"/>';
	} elseif ($custom_fields_input_type[$args['args'][0]] == 'dropdown') {

		echo '<select id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" >';
		echo ' <option value="">---</option>';

		if (in_array($args['args'][0], $yesnooption)) {
			if ($value == 'Yes') $yselect = ' selected';
			if ($value == 'No') $nselect = ' selected';
			echo '  <option' . $yselect . ' value="Yes">Yes</option>
                      <option' . $nselect . '  value="No">No</option>';

		} elseif (in_array($args['args'][0], $genderoption)) {
			if ($value == 'Male') $yselect = ' selected';
			if ($value == 'Female') $nselect = ' selected';
			echo '  <option' . $yselect . ' value="Male">Male</option>
                      <option' . $nselect . '  value="Female">Female</option>';
		} else {
			if ($value == 'Administrator') $aselect = ' selected';
			if ($value == 'Referee Coach') $rselect = ' selected';
			echo '  <option ' . $aselect . '  value="Administrator">Administrator</option>
          <option ' . $rselect . '  value="Referee Coach">Referee Coach</option>';
		}

		echo '</select>';
	} elseif ($custom_fields_input_type[$args['args'][0]] == 'radio') {

		echo '<input type="text" id="' . $args['args'][0] . '" name="' . $args['args'][0] . '" placeholder="' . $args['args'][1] . '" value="' . $last_name . '"/>';

	}
} 
add_action('save_post', 'referee_lastname_box_save');

function referee_lastname_box_save($post_id)
{

	$custom_fields = array(

		'referee_id' => 'Referee ID Number',
		'first_name' => 'First Name',
		'middle_name' => 'Middle name',
		'last_name' => 'Last Name',
		'date_birth' => 'Date of Birth',
		'age' => 'Age',
		'nrd_id' => 'NRD ID',
		'gender' => 'Gender',
		'email' => 'Email Address',
		'ethnicity' => 'Ethnicity',
		'home_phone' => 'Home Phone',
		'mobile_phone' => 'Mobile Phone',
		'address' => 'Address',
		'suburb' => 'Suburb',
		'city' => 'City',
		'post_code' => 'Post Code',
		'occupation' => 'Occupation',
		'active_referee' => 'Active Referee',
		'primary_function' => 'Primary Function',
		'under18' => 'Under 18',
		'parent_first_name' => 'Parent First Name',
		'parent_last_name' => 'Parent Last Name',
		'parent_contact_phone_number' => 'Parent Contact Phone Number',
		'panel' => 'Panel',
		'years_started' => 'Year started with NHRRA',
		'fitness_test_date' => 'Fitness Test Date & Result',
		'Life_Member' => 'Life Member',
		'Board_Member' => 'Board Member',
		'4th_Official' => '4th Official',
		'Referee_Coach' => 'Referee Coach',
		'Selection_Committee' => 'Selection Committee'
	);

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;

	//  if ( !wp_verify_nonce( $_POST['referee_lastname_box_content_nonce'], plugin_basename( __FILE__ ) ) )   return;

	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id))
			return;
	} else {
		if (!current_user_can('edit_post', $post_id))
			return;
	}

	foreach ($custom_fields as $key => $label) {

		$custome_field_val = $_POST[$key];

		update_post_meta($post_id, $key, $custome_field_val);
	}

	if (empty(get_the_title($post_id))) {
		$my_post = array(
			'ID' => $post_id,
			'post_title' => $_POST['first_name'] . '  ' . $_POST['last_name']
		);

		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'referee_lastname_box_save');

		// update the post, which calls save_post again
		wp_update_post($my_post);

		// re-hook this function
		add_action('save_post', 'referee_lastname_box_save');
	}

}

add_shortcode('wpbsearch', 'get_search_form'); 
add_action('wp_ajax_referee_csv_export', 'referee_csv_export');

function referee_csv_export()
{

	$custom_fields = array(

		'referee_id' => 'Referee ID Number',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'middle_name' => 'Middle Name',
		'date_birth' => 'Date of Birth',
		'age' => 'Age',
		'nrd_id' => 'NRD ID',
		'gender' => 'Gender',
		'email' => 'Email Address',
		'ethnicity' => 'Ethnicity',
		'home_phone' => 'Home Phone',
		'mobile_phone' => 'Mobile Phone',
		'address' => 'Address',
		'suburb' => 'Suburb',
		'city' => 'City',
		'post_code' => 'Post Code',
		'occupation' => 'Occupation',
		'active_referee' => 'Active Referee',
		'primary_function' => 'Primary Function',
		'under18' => 'Under 18',
		'parent_first_name' => 'Parent First Name',
		'parent_last_name' => 'Parent Last Name',
		'parent_contact_phone_number' => 'Parent Contact Phone Number',
		'panel' => 'Panel',
		'years_started' => 'Year started with NHRRA',
		'fitness_test_date' => 'Fitness Test Date & Result',
		'Life_Member' => 'Life Member',
		'Board_Member' => 'Board Member',
		'4th_Official' => '4th Official',
		'Referee_Coach' => 'Referee Coach',
		'Selection_Committee' => 'Selection Committee'
	); 
	$referees_ids = $_SESSION['searched_referees'];

	foreach ($referees_ids as $referee_id) {
		foreach ($custom_fields as $key => $val) {
			$lists[$referee_id][$val] = get_post_meta($referee_id, $key, true);

		}
	}
	download_send_headers("data_export_" . date("Y-m-d") . ".csv");
	echo array2csv($lists);
	die();
}

add_shortcode('datasearch', 'datatable');

function datatable($fields)
{

	$links = array(
		'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
		'https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js',
		'https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
		'https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js',
		'/wp-content/plugins/referee/assets/dataTables.checkboxes.min.js',
		'https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js'
	);

	$csslink = array(
		'https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css',
		'https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css', 
		'https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css',
		'/wp-content/plugins/referee/assets/referee.css'

	);

	foreach ($links as $k => $link) {
		wp_enqueue_script('js-file_' . $k, $link, array('jquery'), '', false);

	}
	foreach ($csslink as $k => $csslink) {
		wp_enqueue_style('style' . $k, $csslink);

	} 
	$custom_fields = array(

		'referee_id' => 'Referee ID Number',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'middle_name' => 'Middle Name',
		'date_birth' => 'Date of Birth',
		'age' => 'Age',
		'nrd_id' => 'NRD ID',
		'gender' => 'Gender',
		'email' => 'Email Address',
		'ethnicity' => 'Ethnicity',
		'home_phone' => 'Home Phone',
		'mobile_phone' => 'Mobile Phone',
		'address' => 'Address',
		'suburb' => 'Suburb',
		'city' => 'City',
		'post_code' => 'Post Code',
		'occupation' => 'Occupation',
		'active_referee' => 'Active Referee',
		'primary_function' => 'Primary Function',
		'under18' => 'Under 18',
		'parent_first_name' => 'Parent First Name',
		'parent_last_name' => 'Parent Last Name',
		'parent_contact_phone_number' => 'Parent Contact Phone Number',
		'panel' => 'Panel',
		'years_started' => 'Year started with NHRRA',
		'fitness_test_date' => 'Fitness Test Date & Result',
		'Life_Member' => 'Life Member',
		'Board_Member' => 'Board Member',
		'4th_Official' => '4th Official',
		'Referee_Coach' => 'Referee Coach',
		'Selection_Committee' => 'Selection Committee'
	);

	// Set the arguments for the query
	$args = array(
		'numberposts' => -1, // -1 is for all
		'post_type' => 'referee', // or 'post', 'page'
		'orderby' => 'date', // or 'date', 'rand'
		'order' => 'ASC', // or 'DESC'
		//'category' 		=> $category_id,
		//'exclude'		=> get_the_ID()
		// ...
		// http://codex.wordpress.org/Template_Tags/get_posts#Usage

	);

// Get the posts
	$referee = get_posts($args);

	ob_start();

	?>
  <style>
         #loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid #3498db;
          width: 120px;
          height: 120px;
          -webkit-animation: spin 2s linear infinite;
          animation: spin 2s linear infinite;
          position: relative;
          margin: auto;
          top:150px;
        }
         @-webkit-keyframes spin {
            0%  {-webkit-transform: rotate(0deg);}
            100% {-webkit-transform: rotate(360deg);}
        }
         .hide {
            z-index: 1000;
            position: absolute;
            background: #ffffff;
            height: 100%;
            width: 100%;
            opacity: 1;
        }
   </style>
<div class="hide"><div id="loader"></div></div>
<div class="table_referee" >
    <table id="example" class="display example" style="width:100%">
        <thead>
        <tr>
            <th class="check noExport"></th>
            <th class="edit noExport">Edit</th>
            <th class="profile_image noExport">Image</th>
            <th class="referee_id">Referee ID Number</th>

			<?php

			foreach ($custom_fields as $key => $val) {
			    $style = $noexport  = null;
				if ($key != 'referee_id') {
				    if ( $key == "4th_Official" ) $style = "display:none;" ;
				    if ( $key == "fitness_test_date") $noexport = "noExport";
					echo '<th style="' . $style . '" class="' . $key . '  '.$noexport .' ">' . $val . '</th>';
				}

			}
			?>
            <th class="transaction noExport">Appointments</th>
            <th class="transaction noExport">Fitness Test</th>
        </tr>
        </thead>
        <tbody>

		<?php
		foreach ($referee as $referee) {
			echo '<tr>';
			?>
            <td class="check"></td>
            <td class="edit"><a class="post-edit-link"
                                href="<?php echo $url = get_option('siteurl'); ?>/wp-admin/post.php?post=<?php echo $referee->ID; ?>&action=edit">
                    <img src="wp-content/themes/referee/assets/edit.png">
                    <span class="screen-reader-text"> <?php get_the_title() ?></span>
                </a>
            </td>
            <td class="profile_image_show">
				<?php

				$file = get_field('profile_image', $referee->ID); 
				$size = 'thumbnail'; // (thumbnail, medium, large, full or custom size) 
				if ($file) {
					?>

                    <img id="myImg" class="pr_img myImg" src="<?php echo $file['url']; ?> "/>
					<?php
				}
				?>
            </td>

			<?php

			foreach ($custom_fields as $key => $val) { 
				$val = get_post_meta($referee->ID, $key, true);
				if ($key == 'referee_id') { 
					echo '<td  class="' . $key . '">';
					print_r($val);
					echo '</td>';
				}

			} 
			?>

			<?php
			foreach ($custom_fields as $key => $val) {
                $style = null;

				$val = get_post_meta($referee->ID, $key, true);
				if ($key != 'referee_id') {
				    if ( $key == "4th_Official" ) $style = "display:none;" ;
					echo '<td style="'.$style.'"  class="' . $key . '">';
					print_r($val);
					echo '</td>';
				}
			}
			?>
            <td class="transaction">
                <a href="referee-transactions/?referee_id=<?php echo get_post_meta($referee->ID, 'referee_id', true) ?>">Appointments</a>
            </td>
            <td class="fitnes_test_results">
                <a href="fitness-test-results/?referee_id=<?php echo get_post_meta($referee->ID, 'referee_id', true) ?>">Fitness
                    Test</a>
            </td>
            </tr>

			<?php
		}
		?>

        </tbody>

    </table>
</div>
    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- The Close Button -->
        <span class="close">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img01">

        <!-- Modal Caption (Image Text) -->
        <div id="caption"></div>
    </div>



    <script>
        jQuery(document).ready(function () {
            let table = jQuery('#example').DataTable({
                "processing": true,
                 "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> '},
                dom: 'Bfrtip',
                "initComplete": function(settings, json) {
                            jQuery(".hide").css("display", "none");
                 },
                buttons: [
                    {
                        text: '<img style="width: 20px;" src="wp-content/themes/referee/assets/add.png">',
                        className: 'btn btn-default btnAddJob refbtn',
                        action: function (e, dt, button, config) {
                            window.location = '<?php echo home_url() . '/wp-admin/post-new.php?post_type=referee' ?>';
                        }

                    },
                    {
                        extend: 'csv',
                        text: 'Export Referees',
                        className: 'refbtn',
                        exportOptions: {
                            columns: 'thead th:not(.noExport)'
                        }
                    },
                    {
                        text: 'Import Referees',
                        className: 'btn btn-default btnAddJob refbtn',
                        action: function (e, dt, button, config) {
                            window.location = '<?php echo home_url() . '/wp-admin/edit.php?post_type=referee&page=referees_upload' ?>';
                        }

                    },
                    {
                        text: 'Import Appointments',
                        className: 'btn btn-default btnAddJob refbtn',
                        action: function (e, dt, button, config) {
                            window.location = '<?php echo home_url() . '/wp-admin/edit.php?post_type=referee_transactions&page=referees_transaction_upload' ?>';
                        }

                    },
                    {
                        text: 'Import Fitness Tests',
                        className: 'btn btn-default btnAddJob refbtn',
                        action: function (e, dt, button, config) {
                            window.location = '<?php echo home_url() . '/wp-admin/edit.php?post_type=fitness_test_result&page=fitness_results_upload' ?>';
                        }

                    }, 
                ], 
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }],
                select: {
                    style: 'os',
                    selector: 'td:first-child',
                    'style': 'multi'
                },

                /*
                            'columnDefs': [
                                {
                                    'targets': 0,
                                    'checkboxes': {
                                        'selectRow': true
                                    }
                                }
                            ],
                            'select': {
                                'style': 'multi'
                            },
                */
                'pageLength': 50,
                'scrollY': 700,
                'order': [[5, 'asc']]

            }); 
            jQuery('#example').on('click', 'td.select-checkbox', function () {
                jQuery(this).parent().toggleClass('selected');
            }); 
            jQuery('.dataTable').on('click', 'th.select-checkbox', function () {

                jQuery(this).parent().toggleClass('selected');

                if (jQuery(this).parent().is(".selected")) {
                    // table.rows( {page: 'current'} ).select();
                    table.rows().select();
                } else {
                    // table.rows( {page: 'current'} ).deselect();
                    table.rows().deselect();
                }

                table.rows({selected: true}).every(function () {
                    jQuery(this.node()).addClass('selected');
                });

                table.rows({selected: false}).every(function () {
                    jQuery(this.node()).removeClass('selected');
                });

            }); 
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the image and insert it inside the modal - use its "alt" text as a caption 
            var img = document.getElementsByClassName("myImg");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            jQuery(".myImg").click(function () {
                console.log('ddd');
                modal.style.display = "block";
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            });
            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }

        });
    </script> 
    <style>
         #loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid #3498db;
          width: 120px;
          height: 120px;
          -webkit-animation: spin 2s linear infinite;
          animation: spin 2s linear infinite;
          position: relative;
          margin: auto;
          top:150px;
        }
         @-webkit-keyframes spin {
            0%  {-webkit-transform: rotate(0deg);}
            100% {-webkit-transform: rotate(360deg);}
        }
         .hide {
            z-index: 1000;
            position: absolute;
            opacity: 1;
            background: #ccc;
            height: 100%;
            width: 100%;
            opacity: 0.9;
        }

        <?php
         $columnttshow = array(
            'referee_id',
            'first_name',
             'last_name'
             );
         foreach ( $custom_fields as $key=>$val){
            if (!in_array($key,$columnttshow))   echo '.'.$key.'{display:none;}';
         }
        ?>

    </style>

	<?php

	return ob_get_clean();
} 
include_once('csvreader.php'); 
/**
 * Adds a submenu page under a custom post type parent.
 */
function books_register_ref_page()
{
	add_submenu_page(
		'edit.php?post_type=referee',
		__('Referees Upload page', 'textdomain'),
		__('Referees Upload page', 'textdomain'),
		'edit_posts',
		'referees_upload',
		'books_ref_page_callback'
	);
}

/**
 * Display callback for the submenu page.
 */
function books_ref_page_callback()
{
	?>
    <div class="wrap">
        <h1><?php _e('Referees Upload page', 'textdomain'); ?></h1>
		<?php upload_file() ?> 
    </div>
	<?php
}

add_action('admin_menu', 'books_register_ref_page'); 
function upload_file()
{

	if (!defined(ABSPATH)) {
		$absolute_path = __FILE__;
		$path_to_file = explode('wp-content', $absolute_path);
		$path_to_wp = $path_to_file[0]; 
		require_once($path_to_wp . '/wp-load.php');
	}
	?>

    <h1>Excel Upload Form</h1>
    <p>Choose an Excel file to upload.</p>
    <form id="upload_form" action="<?php echo get_site_url(); ?>/wp-content/plugins/referee/upload.php"
          enctype="multipart/form-data" method="post" target="messages">
        <p><input name="upload" id="upload" type="file" accept="text/comma-separated-values"/></p>
        <p><input id="btnSubmit" type="submit" value="Upload Referees"/></p>
        <iframe name="messages" id="messages"></iframe>
        <p><input id="reset_upload_form" type="reset" value="Reset form"/></p>
    </form>

    <script>

        jQuery(document).ready(function ($) {
            $('#upload_form').on('submit', function (evt) {
                var form = $(evt.target),
                    fileElt = form.find('input#upload'),

                    fileName = fileElt.val(),
                    messages = form.find('iframe#messages').contents().find("body"),
                    maxSize = 12000000,
                    fail = function (msg) {
                        messages.append($('<P>').css('color', 'red').text(msg));
                        fileElt.focus();
                    };

                console.log(fileName);

                if (fileName.length == 0) {
                    fail('Please select a file.');
                } else if (!/\.(csv)$/.test(fileName)) {
                    fail('Only  CSV files are permitted.');
                } else {
                    var file = fileElt.get(0).files[0];
                    if (file.size > maxSize) {
                        fail('The fileddd is too large. The maximum size is ' + maxSize + ' bytes.');
                    } else {
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

        <?php

        foreach ( $custom_fields as $key=>$val){

                       echo '.'.$key.'{display:none;}';

                   }

       ?>
    </style> 
	<?php

	return;
}

include_once('referee_transactions.php');
include_once('FitnessTestResults.php');
