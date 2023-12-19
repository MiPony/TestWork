<?php

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

// Connecting scripts and styles
function handany_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri(), array(), _S_VERSION );
    wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', array(), _S_VERSION );
}
add_action( 'wp_enqueue_scripts', 'handany_scripts' );

// Register header menu
function register_handany_menus() {
    $locations = array(
        'header-menu' => __( 'Header Menu' )
    );
    
    register_nav_menus($locations);
}
add_action( 'init', 'register_handany_menus' );

// Connect woocommerce
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
   add_theme_support( 'woocommerce' );
}  

// Display custom field in admin panel
add_action('woocommerce_product_options_general_product_data', 'woocommerce_product_custom_fields');
function woocommerce_product_custom_fields(){
    global $woocommerce, $post;
    echo '<div class="product_custom_field">';

    woocommerce_wp_text_input(
        array(
            'id' => '_custom_product_date_field',
            'placeholder' => 'Product create date',
            'label' => __('Custom product date', 'woocommerce'),
            'type' => 'date'
        )
    );

	woocommerce_wp_select( array(
		'id'      => 'custom_product_select_field',
		'label' => __('Custom select product type', 'woocommerce'),
		'desc_tip' => true,
		'style' => 'margin-bottom:40px;',
		'value' => get_post_meta( get_the_ID(), 'custom_product_select_field', true ),
		'options' => array(
			'' => 'Select...',
			'Rare' => 'Rare',
			'Frequent' => 'Frequent',
			'Unusual' => 'Unusual'
		)
	) );

    echo '
        <div style="display:flex;flex-direction: column;align-items:center;width:100%;">
            <a href="#" 
                class="remove_custom_field_button" 
                style="display:inline-block;font-size:20px;"
            >
                Remove custom fields
            </a>
            <input 
                type="submit" 
                class="metabox_submit button-primary"
                style="display:inline-block;font-size:20px;margin:20px 0;"
                value="Update product" 
            />
        </div>
	</div>';
}
// Save custom fields
add_action('woocommerce_process_product_meta', 'woocommerce_product_custom_fields_save');
function woocommerce_product_custom_fields_save($post_id){

		$woocommerce_custom_product_date_field = $_POST['_custom_product_date_field'];
		if (!empty($woocommerce_custom_product_date_field)){
			update_post_meta($post_id, '_custom_product_date_field', esc_attr($woocommerce_custom_product_date_field));
		}

		$woocommerce_custom_product_select_field = $_POST['custom_product_select_field'];
		if (!empty($woocommerce_custom_product_select_field)){
			update_post_meta($post_id, 'custom_product_select_field', esc_attr($woocommerce_custom_product_select_field));
		}
}

// Connecting script
function custom_include_myuploadscript() {
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'myuploadscript', get_stylesheet_directory_uri() . '/assets/js/customscript.js', array('jquery'), null, false );
}
add_action( 'admin_enqueue_scripts', 'custom_include_myuploadscript' );

// Custom uploader in admin page product
function custom_image_uploader_field( $name, $value = '') {
    $image = ' button">Upload image';
    $image_size = 'full';
    $display = 'none';

    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
        $display = 'inline-block';
    } 
    return '
    <div>
        <a href="#" class="custom_upload_image_button' . $image . '</a>
        <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
        <a href="#" class="custom_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
    </div>';
}

// Add a meta box
add_action( 'admin_menu', 'custom_meta_box_add' );
function custom_meta_box_add() {
    add_meta_box('customdiv', 
        'Custom Image',
        'custom_print_box',
        'product',
        'normal',
        'default' );
}

// Meta Box HTML
function custom_print_box( $post ) {
    $meta_key = 'second_featured_img';
    echo custom_image_uploader_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
}

// Save Meta Box data
add_action('save_post', 'custom_save');
function custom_save( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;

    $meta_key = 'second_featured_img';
    if($_POST['second_featured_img']){
        update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );
    }

    return $post_id;
}

// AJAX create new product
add_action('wp_ajax_create_product', 'create_product_callback');
function create_product_callback() {
    global $wpdb;

    require_once (ABSPATH . 'wp-admin/includes/image.php');
    require_once (ABSPATH . 'wp-admin/includes/file.php');
    require_once (ABSPATH . 'wp-admin/includes/media.php');
    
	$arr=[];
    if($_FILES){
        $file_handler = 'updoc';
        $attach_id = media_handle_upload($file_handler, $pid );
    }
	wp_parse_str( $_POST['create_product'], $arr);
    $post_data = array(
        'post_title'    => sanitize_text_field( $arr['productname'] ),
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type'     => 'product'
    );
    
    $post_id = wp_insert_post( $post_data );
    update_post_meta( $post_id, '_price', $arr['price'] );
    update_post_meta( $post_id, '_regular_price', $arr['price'] );
    update_post_meta( $post_id, '_custom_product_date_field', $arr['_custom_product_date_field'] );
    update_post_meta( $post_id, 'custom_product_select_field', $arr['custom_product_select_field'] );
    if($attach_id){
        update_post_meta( $post_id, 'second_featured_img', $attach_id );
    }
    set_post_thumbnail( $post_id, $attach_id );

    wp_die();
}

// AJAX reset custom fields
add_action('wp_ajax_remove_custom_fields', 'remove_custom_fields_callback');
add_action('wp_ajax_nopriv_remove_custom_fields', 'remove_custom_fields_callback');
function remove_custom_fields_callback() {
    $post_id = intval($_POST['post_id']);
    delete_post_meta( $post_id, 'second_featured_img' );
    delete_post_meta( $post_id, '_custom_product_date_field' );
    delete_post_meta( $post_id, 'custom_product_select_field' );

    wp_die();
}

add_action('wp_ajax_remove_custom_image', 'remove_custom_image_callback');
add_action('wp_ajax_nopriv_remove_custom_image', 'remove_custom_image_callback');
function remove_custom_image_callback() {
    $post_id = intval($_POST['post_id']);
    delete_post_meta( $post_id, 'second_featured_img' );
    wp_die();
}