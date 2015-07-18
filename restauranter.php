<?php

/*
  Plugin Name: Restauranter
  Plugin URI: http://www.alxs.co.uk
  Version: 1.0.0
  Author: Alex Scott
  Description: Restauranter is a restaurant management plugin.  Create dinner menus, take reservations and send and receive notifications with your customers.
  Text Domain: restauranter
  License: GPLv3
 */


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if(!class_exists('Restauranter'))
{
    class Restauranter
    {

    }
}
if(class_exists('Restauranter'))
{
    $restauranter = new Restauranter();
}

if (isset($restauranter)) {
    function load_custom_admin_scripts() {
      wp_register_style( 'smoothness', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
      wp_register_script( 'main', plugins_url( '/js/main.js', __FILE__ ), array(), '', true );
      wp_register_script( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/jquery-ui.js', array(), '', true );
      wp_register_script( 'jquery-time', plugins_url( '/js/jquery-time.js', __FILE__ ), array(), '', true );
      wp_register_style( 'jquery-time', plugins_url( '/css/jquery-time.css', __FILE__ ));
      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'jquery-ui' );
      wp_enqueue_script( 'jquery-time' );
      wp_enqueue_script( 'main' );
      wp_enqueue_style( 'smoothness' );
      wp_enqueue_style( 'jquery-time' );
    }
    add_action( 'admin_enqueue_scripts', 'load_custom_admin_scripts' );

    function meals_init() {
    $labels = array(
      'name'               => _x( 'Meals', 'post type general name' ),
      'singular_name'      => _x( 'Meal', 'post type singular name' ),
      'add_new'            => _x( 'Add New', 'meal' ),
      'add_new_item'       => __( 'Add New Meal' ),
      'edit_item'          => __( 'Edit Meal' ),
      'new_item'           => __( 'New Meal' ),
      'all_items'          => __( 'All Meals' ),
      'view_item'          => __( 'View Meal' ),
      'search_items'       => __( 'Search Meals' ),
      'not_found'          => __( 'No meals found' ),
      'not_found_in_trash' => __( 'No meals found in the Trash' ), 
      'parent_item_colon'  => '',
      'menu_name'          => 'Restauranter'
    );
    $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our products and product specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
      'has_archive'   => true,
      'menu_icon' => 'dashicons-list-view',
      'show_in_nav_menu' => true,
      'show_in_menu' => true,
      'taxonomies' => array('meal_category', 'diet_requirement'),
    );
    register_post_type( 'meals', $args );
  }
  add_action( 'init', 'meals_init' );
  function reservations_init() {
    $labels = array(
      'name'               => _x( 'Reservations', 'post type general name' ),
      'singular_name'      => _x( 'Reservation', 'post type singular name' ),
      'add_new'            => _x( 'Add New', 'reservation' ),
      'add_new_item'       => __( 'Add New Reservation' ),
      'edit_item'          => __( 'Edit Reservation' ),
      'new_item'           => __( 'New Reservation' ),
      'all_items'          => __( 'Reservations' ),
      'view_item'          => __( 'View Reservation' ),
      'search_items'       => __( 'Search Reservations' ),
      'not_found'          => __( 'No reservations found' ),
      'not_found_in_trash' => __( 'No reservations found in the Trash' ), 
      'parent_item_colon'  => '',
      'menu_name'          => 'Reservations'
    );
    $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our reservations',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array( 'none' ),
      'has_archive'   => true,
      'show_in_nav_menu' => true,
      'show_in_menu' => 'edit.php?post_type=meals',
    );
    register_post_type( 'reservations', $args );
  }
  add_action( 'init', 'reservations_init' );
  function my_courses() {
    $labels = array(
      'name'              => _x( 'Courses', 'taxonomy general name' ),
      'singular_name'     => _x( 'Course', 'taxonomy singular name' ),
      'search_items'      => __( 'Search Courses' ),
      'all_items'         => __( 'All Courses' ),
      'parent_item'       => __( 'Parent Course' ),
      'parent_item_colon' => __( 'Parent Course:' ),
      'edit_item'         => __( 'Edit Course' ), 
      'update_item'       => __( 'Update Course' ),
      'add_new_item'      => __( 'Add New Course' ),
      'new_item_name'     => __( 'New Course' ),
      'menu_name'         => __( 'Courses' ),
    );
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
    );
    register_taxonomy( 'meal_category', 'meals', $args );
  }
  add_action( 'init', 'my_courses', 0 );
  function diet_requirements() {
    $labels = array(
      'name'              => _x( 'Diet Requirements', 'taxonomy general name' ),
      'singular_name'     => _x( 'Diet Requirement', 'taxonomy singular name' ),
      'search_items'      => __( 'Search Diet Requirements' ),
      'all_items'         => __( 'All Diet Requirements' ),
      'parent_item'       => __( 'Parent Diet Requirement' ),
      'parent_item_colon' => __( 'Parent Diet Requirement:' ),
      'edit_item'         => __( 'Edit Diet Requirement' ), 
      'update_item'       => __( 'Update Diet Requirement' ),
      'add_new_item'      => __( 'Add New Diet Requirement' ),
      'new_item_name'     => __( 'New Diet Requirement' ),
      'menu_name'         => __( 'Diet Requirements' ),
    );
    $args = array(
      'labels' => $labels,
      'hierarchical' => true,
    );
    register_taxonomy( 'diet_requirement', 'meals', $args );
  }
  add_action( 'init', 'diet_requirements', 0 );

  function custom_edit_reservations_columns( $column, $post_id ) {
    switch ( $column ) {
      case "_firstname":
        $firstname = get_post_meta($post_id, '_firstname', true);
        echo $firstname;
      break;
    }
  }
  add_action( "manage_posts_custom_column", "custom_edit_reservations_columns", 10, 2 );
  function restauranter_add_meta_box() {

	  $screens = array( 'reservations' );

	  foreach ( $screens as $screen ) {

		  add_meta_box(
			  'restauranter_firstname',
			  __( 'Reservation Details', 'restauranter' ),
			  'restauranter_reservation_details',
			  $screen, 
			  'normal', 
			  'high'
		  );
	  }
  }
  add_action( 'add_meta_boxes', 'restauranter_add_meta_box' );
 
  function restauranter_reservation_details() {
	  global $post;
	
	  echo '<input type="hidden" name="reservationmeta_noncename" id="reservationmeta_noncename" value="' . 
	  wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	  $firstname = get_post_meta($post->ID, '_firstname', true);
	  $lastname = get_post_meta($post->ID, '_lastname', true);
	  $phone = get_post_meta($post->ID, '_phone', true);
	  $email = get_post_meta($post->ID, '_email', true);
	  $guests = get_post_meta($post->ID, '_guests', true);
	  $date = get_post_meta($post->ID, '_date', true);
	  $time = get_post_meta($post->ID, '_time', true);
	
    echo '<p>First Name:</p>';
    echo '<input type="text" name="_firstname" value="' . $firstname  . '" class="widefat" />';
    echo '<p>Last Name:</p>';
    echo '<input type="text" name="_lastname" value="' . $lastname  . '" class="widefat" />';
    echo '<p>Phone Number:</p>';
    echo '<input type="text" name="_phone" value="' . $phone  . '" class="widefat" />';
    echo '<p>Email:</p>';
    echo '<input type="text" name="_email" value="' . $email  . '" class="widefat" />';
    echo '<p>Number of Guests:</p>';
    echo '<input type="text" name="_guests" value="' . $guests  . '" class="widefat" />';
    echo '<p>Reservation Date:</p>';
    echo '<input type="text" name="_date" value="' . $date  . '" class="widefat date" />';
    echo '<p>Reservation Time:</p>';
    echo '<input type="text" name="_time" value="' . $time  . '" class="widefat time" />';
    echo '<input type="submit" name="_time" value="" class="widefat submit" />';

  }
  function restauranter_save_reservations_meta($post_id, $post) {
	
	  if ( !wp_verify_nonce( $_POST['reservationmeta_noncename'], plugin_basename(__FILE__) )) {
	  return $post->ID;
	  }

	  // Is the user allowed to edit the post or page?
	  if ( !current_user_can( 'edit_post', $post->ID ))
		  return $post->ID;

	  $reservations_meta['_firstname'] = $_POST['_firstname'];
	  $reservations_meta['_lastname'] = $_POST['_lastname'];
	  $reservations_meta['_phone'] = $_POST['_phone'];
	  $reservations_meta['_email'] = $_POST['_email'];
	  $reservations_meta['_guests'] = $_POST['_guests'];
	  $reservations_meta['_date'] = $_POST['_date'];
	  $reservations_meta['_time'] = $_POST['_time'];
	
	
	  foreach ($reservations_meta as $key => $value) {
		  if( $post->post_type == 'revision' ) return;
		  $value = implode(',', (array)$value);
		  if(get_post_meta($post->ID, $key, FALSE)) {
			  update_post_meta($post->ID, $key, $value);
		  } else {
			  add_post_meta($post->ID, $key, $value);
		  }
		  if(!$value) delete_post_meta($post->ID, $key);
	  }

  }

  add_action('save_post', 'restauranter_save_reservations_meta', 1, 2); // save the custom fields


function add_new_reservations_columns($reservations_columns) {
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['_firstname'] = __('First Name', '_firstname');
    return $new_columns;
}
add_filter('manage_edit-reservations_columns' , 'add_new_reservations_columns');


add_action('manage_edit-reservations_custom_column', 'manage_reservations_columns', 10, 2);
 
function manage_reservations_columns($column_name, $id) {
    global $wpdb;
    switch ($column_name) {
    case '_firstname':
        echo $firstname;
            break;
 
    case '_lastname':
        echo $lastname; 
        break;
    default:
        break;
    } // end switch
}   


}
?>
