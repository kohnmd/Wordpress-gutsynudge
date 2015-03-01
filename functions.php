<?php

/**
 * Child theme scripts.
 */
function gutsynudge_scripts(){
    global $wp_styles;
    
    // Chillout child theme styles.
    $user_css_deps = $wp_styles->registered['user-css']->deps;
    wp_enqueue_style( 'style-gutsynudge', get_stylesheet_directory_uri() . '/style.css', array_merge(array('style-chosen'), $user_css_deps), A13_THEME_VER );

    // Change loading order of user.css
    array_push($user_css_deps, array('child-style'));

    // Take it out of queue and insert at end
    wp_dequeue_style('user-css');
    wp_enqueue_style('user-css');
    
    
    // Custom JS
    wp_enqueue_script( 'gutsynudge', get_stylesheet_directory_uri() . '/js/gutsynudge.js', array('jquery', 'jquery_chosen'), '20150113', true );
    
    // jQuery Chosen
    wp_enqueue_script( 'jquery_chosen', get_stylesheet_directory_uri() . '/js/chosen.jquery.min.js', array('jquery'), '1.3.0', true );
    wp_enqueue_style( 'style-chosen', get_stylesheet_directory_uri() . '/css/chosen.css', null, '1.3.0' );
    
}
add_action('wp_enqueue_scripts', 'gutsynudge_scripts', 11);


add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
    echo '
        <script type="text/javascript">
            var ajaxurl = "' . admin_url('admin-ajax.php') . '";
        </script>';
}


/**
 * Add your functions below, and overwrite native theme functions.
 */


// Create "Nudge" post type.
add_action( 'init', 'nudge_init' );
function nudge_init() {
	$args = array(
		'labels'                => array(
    		'name'          => 'Nudges',
    		'singular_name' => 'Nudge'
    	),
		'public'                => true,
		'exclude_from_search'   => true,
		'show_in_nav_menus'     => false,
		'show_in_menu'          => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'nudge' ),
		'taxonomies'            => array( 'category' ),
		'supports'              => array( 'title', 'author' )
	);
	register_post_type( 'nudge', $args );
}


// Add a default category to nudges if none is specified.
add_action('publish_nudge', 'nudge_default_category');
function nudge_default_category($post_id) {
	global $wpdb;
	if(!has_term('','category',$post_id)){
		$cats = array(1); // "Random"
		wp_set_object_terms($post_id, $cats, 'category');
	}
}


// Create shortcode for nudge dropdown.
add_shortcode( 'gutsy-nudge', 'gutsy_nudge_dropdown' );
function gutsy_nudge_dropdown() {
	$categories = get_categories(array(
    	'type'          => 'nudge',
    	'orderby'       => 'name',
    	'order'         => 'ASC',
    	'hide_empty'    => 1
    ));
    
    // Nudge select.
    $output = '<div id="nudge-select">';
        $output .= '<select id="nudge-dropdown" style="width: 50%;">';
            $output .= '<option value=""></option>';
        	foreach ($categories as $category) {
            	$output .= '<option value="' . $category->term_id . '">' . $category->name . '</option>';
        	}
        $output .= '</select>';
	$output .= '</div><!-- #nudge-select -->';
	
	return $output;
}


/**
 * The magic query for getting a random nudge!
 */
add_action('wp_ajax_get_nudge', 'get_nudge_callback');
add_action('wp_ajax_nopriv_get_nudge', 'get_nudge_callback');
function get_nudge_callback() {
    $return = array();
    
    if (!empty($_POST['category_id'])) {
        $category_id = intval($_POST['category_id']);
        
        $args = array(
            'post_type'         => 'nudge',
            'category'          => $category_id,
            'orderby'           => 'rand',
            'posts_per_page'    => 1
        );
        $post = get_posts($args);
        $post = array_pop($post);
        
        if (!empty($post)) {
            $return['success'] = true;
            $return['nudge_url'] = get_permalink($post->ID) . '?' . http_build_query(array('c' => $category_id));
        } else {
            // Error
            $return['error'] = true;
            $return ['message'] = 'No posts found.';
        }
        
    } else {
        // Error
        $return['error'] = true;
        $return ['message'] = 'Invalid category.';
    }
    
    header('Content-Type: application/json');
    echo json_encode($return);
    die();
}






/**
 * Debugger
 */

function pre_print($var, $title="", $return=false) {
	$output = "";
	$output .= ($title) ? '<strong>'.$title.'</strong>' : "";
	$output .= '<pre>';
		$output .= print_r($var, true);
	$output .= '</pre>';
	
	if($return) {
		return $output;
	} else {
		echo $output;
	}
}