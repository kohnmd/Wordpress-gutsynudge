<?php

/**
 * Chillout child theme style includes.
 */
function a13_child_style(){
    global $wp_styles;

    //use also for child theme style
    $user_css_deps = $wp_styles->registered['user-css']->deps;
    wp_enqueue_style('child-style', get_stylesheet_directory_uri(). '/style.css', $user_css_deps, A13_THEME_VER);

    //change loading order of user.css
    array_push($user_css_deps, array('child-style'));

    //take it out of queue and insert at end
    wp_dequeue_style('user-css');
    wp_enqueue_style('user-css');
}
add_action('wp_enqueue_scripts', 'a13_child_style',11);


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


// Create shortcode for nudges.
add_shortcode( 'gutsy-nudge', 'gutsy_nudge_dropdown' );
function gutsy_nudge_dropdown() {
	$categories = get_categories(array(
    	'type'          => 'nudge',
    	'orderby'       => 'name',
    	'order'         => 'ASC',
    	'hide_empty'    => 1
    ));
    
    $output = '<div id="nudge">';
    
        $output = '<select id="nudge-category">';
            $output .= '<option value="">Where do you need a nudge?</option>';
        	foreach ($categories as $category) {
            	$output .= '<option value="' . $category->term_id . '">' . $category->name . '</option>';
        	}
        $output .= '</select>';
	
	$output .= '<div id="nudge-text-container"></div>';
	
	return $output;
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