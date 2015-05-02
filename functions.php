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




/**
 * Nudge Importer
 */
function import_nudges() {
    if (current_time('timestamp') > strtotime('March 26, 2015')) {
        die('Too late.');
    }
    
    // Get nudge data from CSV
    
    $nudge_data = array();
    
    $filepath = get_stylesheet_directory() . '/data/gndatabase.csv';
    if (file_exists($filepath) && ($handle = fopen($filepath, 'r')) !== FALSE) {
        $r = 0;
        $header = array();
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (++$r == 1) {
                $header = $data;
                continue;
            }
            
            $nudge_data[] = array_combine($header, $data);
        }
        fclose($handle);
    }
    
    // At this point the nudge_data is a mess.
    // Categories are split into two different fields, and have multiple delimiters.
    // Authors are listed alongsite rest of nudge text.
    // Do some pretty fuzzy string manipulation below to clean up data.
    
    $unique_categories = array();
    $unique_authors = array();
    foreach ($nudge_data as &$nudge) {
        // First, categories.
        $nudge['unique_categories'] = array();
        $all_categories = strtolower($nudge['category'] . ',' . $nudge['subcategory']);
        
        // Unfortunately, some catgories are delimited by commas, and others by slashes.
        $all_categories = explode(',', $all_categories);
        foreach ($all_categories as $categories) {
            $categories = explode('/', $categories);
            foreach ($categories as $category) {
                $category = ucwords(trim($category));
                if ($category != "" && !in_array($category, $unique_categories)) {
                    $unique_categories[] = $category;
                }
                if ($category != "" && !in_array($category, $nudge['unique_categories'])) {
                     $nudge['unique_categories'][] = $category;
                }
               
            }
        }
        
        $matches = array();
        preg_match_all('/((Gutsy (writer|blogger) ?)?-( ?)(.{0,40}))(\n|$)/i', trim($nudge['nudge']), $matches);
        
        $author = "";
        if (!empty($matches[5][0])) {
            $author = $matches[5][0];
            
            if (!in_array($author, $unique_authors)) {
                $unique_authors[] = $author;
            }
            
            $nudge['nudge'] = str_replace($matches[0][0], "", $nudge['nudge']);
        }
        
        $nudge['author'] = $author;
    }
    
    sort($unique_categories);
    sort($unique_authors);
    
    $existing_categories = array();
    foreach ($unique_categories as $category) {        
        $existing_category = term_exists($category, 'category');
        if (!$existing_category) {
            $existing_category = wp_insert_term($category, 'category');
        }
        
        $category_id = $existing_category['term_id'];
        $existing_categories[$category_id] = $category;
    }
    
    $existing_authors = array();
    foreach ($unique_authors as $author) {
        $existing_author = get_user_by('login', sanitize_title($author));
        
        if (!$existing_author) {
            $name_pieces = explode(' ', $author);
            $author_last_name = array_pop($name_pieces);
            $author_first_name = implode(' ', $name_pieces);
            
            $author_id = wp_insert_user(
                array(
                    'user_pass'     => 'password' . mt_rand(10000,99999),
                    'user_login'    => sanitize_title($author),
                    'user_email'    => str_replace('-', "", sanitize_title($author)) . '@example.com',
                    'display_name'  => $author,
                    'first_name'    => $author_first_name,
                    'last_name'     => $author_last_name,
                    'role'          => 'author',
                )
            );
            
            $author_id = $author;
        } else {
            $author_id = $existing_author->ID;
        }
        
        $existing_authors[$author_id] = $author;
    }
    
    foreach ($nudge_data as $nudge) {
        // Post title
        $nudge_content = trim($nudge['nudge']);
        $nudge_busted = explode(' ', $nudge_content);
        $post_title = implode(' ', array_slice($nudge_busted, 0, 3));
        
        // Post categories
        $post_categories = array();
        foreach ($nudge['unique_categories'] as $category_name) {
            $post_categories[] = array_search($category_name, $existing_categories);
        }
        
        // Post author
        $post_author = array_search($nudge['author'], $existing_authors);
        
        
        // Build Post data and insert
        $post = array(
            'post_title'     => $post_title . '...',
            'post_category'  => $post_categories,
            'post_type'      => 'nudge',
            'post_author'    => $post_author,
            'post_status'    => 'publish',
        );
        
        $post_id = wp_insert_post($post);
        
        if (!$post_id) {
            pre_print($nudge);
            pre_print($post);
            die('FAIL!!!');
        }
        
        // Add nudge content to Advanced Custom Fields
        $acf_field_key = 'field_54b33e8fd9ac1';
        $result = update_field($acf_field_key, $nudge_content, $post_id);
    }
    
    pre_print($existing_categories);
    pre_print($existing_authors);
    pre_print($nudge_data);
    
    die('COMPLETE');
}













