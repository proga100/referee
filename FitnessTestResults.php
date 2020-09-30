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


function fitness_test_results_custom_post_type()
{
	$labels = array(
		'name' => _x('Fitness Tests', 'post type general name'),
		'singular_name' => _x('Fitness Test Result', 'post type singular name'),
		'add_new' => _x('Add New', 'book'),
		'add_new_item' => __('Add New Fitness Test'),
		'edit_item' => __('Edit Fitness Test'),
		'new_item' => __('New Fitness Test'),
		'all_items' => __('All Fitness Tests'),
		'view_item' => __('View Fitness Tests'),
		'search_items' => __('Search Fitness Tests'),
		'not_found' => __('No Fitness Tests found'),
		'not_found_in_trash' => __('No Fitness Tests found in the Trash'),
		'parent_item_colon' => '',
		'menu_name' => 'Fitness Tests'
	);
	$args = array(
		'labels' => $labels,
		'description' => 'Holds our Fitness Tests  specific data',
		'public' => true,
		'menu_position' => 5,
		'supports' => array('title'),
		'has_archive' => true,
	);
	register_post_type('fitness_test_result', $args);
}

add_action('init', 'fitness_test_results_custom_post_type');


/**
 * Adds a submenu page under a custom post type parent.
 */
function register_fitness_test_results()
{
	add_submenu_page(
		'edit.php?post_type=fitness_test_result',
		__('Fitness Tests Upload page', 'textdomain'),
		__('Fitness Tests Upload page', 'textdomain'),
		'edit_posts',
		'fitness_results_upload',
		'fitness_test_results_callback'
	);
}

/**
 * Display callback for the submenu page.
 */
function fitness_test_results_callback()
{
	?>
    <div class="wrap">
        <h1><?php _e('Fitness Test page', 'textdomain'); ?></h1>
		<?php upload_file_fitness_test_results_upload() ?>


    </div>
	<?php
}

add_action('admin_menu', 'register_fitness_test_results');


function upload_file_fitness_test_results_upload()
{

	if (!defined(ABSPATH)) {
		$absolute_path = __FILE__;
		$path_to_file = explode('wp-content', $absolute_path);
		$path_to_wp = $path_to_file[0];
		require_once($path_to_wp . '/wp-load.php');
	}
	?>

    <h1>Fitnes test results Excel file Upload Form</h1>
    <p>Choose an file to upload.</p>
    <form id="upload_form" action="<?php echo get_site_url(); ?>/wp-content/plugins/referee/upload_fitness_test.php"
          enctype="multipart/form-data" method="post" target="messages">
        <p><input name="upload" id="upload" type="file"
                  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"/>
        </p>
        <p><input id="btnSubmit" type="submit" value="Upload Fitness Tests Results"/></p>
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
                    maxSize = 20000000,
                    fail = function (msg) {
                        messages.append($('<P>').css('color', 'red').text(msg));
                        fileElt.focus();
                    };

                console.log(fileName);

                if (fileName.length == 0) {
                    fail('Please select a file.');
                } else if (!/\.(xls|xlsx)$/.test(fileName)) {
                    fail('Only  Excel files are permitted.');
                } else {
                    var file = fileElt.get(0).files[0];
                    if (file.size > maxSize) {
                        fail('The file is too large. The maximum size is ' + maxSize + ' bytes.');
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
    </style>


	<?php

	return;
}


// function that runs when shortcode is called
function fitness_test_results()
{

	$referee_id = $_REQUEST['referee_id'];


	$posts = get_posts(array(
		'numberposts' => -1,
		'post_type' => 'fitness_test_result'

	));
	$custom_fields = array(
		'referee_id_number' => 'Referee Id',
		'first_name_fitness' => 'First Name',

		'fitness_last_name' => 'Last Name',
		'fitness_test_date' => 'Fitness Test Data',
		'fitness_test_result' => 'Fitness Test'
	);

	$meta_key = 'referee_id';

	$post_id = post_transaction_post_id($meta_key, $referee_id);

	$firstname = get_post_meta($post_id, 'first_name', true);
	$lastname = get_post_meta($post_id, 'last_name', true);
	$html = '<h3> ' . $firstname . '  ' . $lastname . ' Fitness Tests</h3>';

	$import = home_url() . '/wp-admin/edit.php?post_type=fitness_test_result&page=fitness_results_upload';
	$html .= fdatatable($referee_id, $posts, $custom_fields, $import);

	return $html;

}

// register shortcode
add_shortcode('fitness_test_results', 'fitness_test_results');


function fdatatable($referee_id, $posts, $custom_fields, $import)
{

	$links = array(
		'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
		'https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js',
		'https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js',
		'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',

		'https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js'
	);

	$csslink = array(
		'https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css',
		'https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css'
	);

	foreach ($links as $k => $link) {
		wp_enqueue_script('js-file_' . $k, $link, array('jquery'), '', false);

	}
	foreach ($csslink as $k => $csslink) {
		wp_enqueue_style('style' . $k, $csslink);

	}


	// Get the posts


	ob_start();

	?>

    <table id="example" class="display nowrap" style="width:100%">
        <thead>
        <tr>    <?php

			foreach ($custom_fields as $key => $val) {
			    $style = ($key == 'fitness_test_result' ? 'display:none' : null);
			    echo '<th style="'.$style.'" class="' . $key . '">' . $val . '</th>';
			}

			?>

        </tr>
        </thead>
        <tbody>

		<?php

		$i = 0;
		global $pos;
		$pos = array();
		foreach ($posts as $post) {

			$post = (array)$post;
			foreach ($custom_fields as $key => $val) {
				$val = get_post_meta($post['ID'], $key, true);
				//echo $key.'  '.$val.' <br/>';
				if (($referee_id != $val) && ($key == 'referee_id_number')) {
					unset ($post);

				}

			}

			$pos[$i] = $post;
			$i++;
			//  print_r ($pos);
		}


		$pos = array_filter($pos);


		foreach ($pos as $post) {

			echo '<tr>';
			foreach ($custom_fields as $key => $val) {
				$val = get_post_meta($post['ID'], $key, true);
				//echo $key.'  '.$val.' <br/>';
                $style = ($key == 'fitness_test_result' ? 'display:none' : null);
                $val = ( ( $key == 'referee_id_number' && !empty($val)) ? str_pad($val, 3, '0', STR_PAD_LEFT):$val);
				$val = ( ($key == 'date') || ( $key == 'fitness_test_date' && isset($val)) ? date('d-m-Y', strtotime($val)):$val);
				$val = ( ( $key == 'fitness_test_result' && !empty($val)) ? date('h:i:s', strtotime($val)):$val);
				echo '<td style="'.$style.'" class="' . $key . '">';
				print_r($val);
				echo '</td>';


			}
			echo ' </tr>';
		}
		?>
        <tfoot>
        <tr>
			<?php

			foreach ($custom_fields as $key => $val):
			    $style = ($key == 'fitness_test_result' ? 'display:none' : null);
			    echo '<th style="'.$style.'" class="' . $key . '">' . $val . '</th>';

			endforeach;

			?>
        </tr>
        </tfoot>
    </table>


    <script>
        jQuery(document).ready(function () {
            jQuery('#example').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        text: 'Export'

                    }
                    /*
					{


						text: 'Import',
						className: 'btn btn-default btnAddJob',
						action: function ( e, dt, button, config ) {
							window.location = '<?php echo $import ?>';
                            }

                        }
                        */

                ],

                'pageLength': 50

            });


        });


    </script>

    <style>
        span.edit-link {
            display: none;
        }

        table.dataTable.display tbody tr td, table.dataTable.display tr th {
            text-align: center;
        }

        .edit-link {
            display: none !important;
        }
    </style>


	<?php

	return ob_get_clean();
}






