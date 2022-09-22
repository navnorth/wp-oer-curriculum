<?php
/**
 * Plugin Name:       Curriculum Thumbnail Block
 * Description:       Use this block to add OER curriculum thumbnail
 * Requires at least: 5.3
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       oer-curriculum-thumbnail-block
 *
 * @package           oer-curriculum
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */

global $wp_version;

function oer_curriculum_thumbnail_block_init() {
	register_block_type( __DIR__ );
}

function oer_curriculum_thumbnail_block_init_legacy(){
	wp_register_script('oercurr_ctb_block_js', plugin_dir_url( __FILE__ ).'/build/index.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), null, true	);
	wp_register_style('oercurr_ctb_block_editor_css', plugin_dir_url( __FILE__ ).'/build/index.css',array( 'wp-edit-blocks' ),null);
	wp_register_style('oercurr_ctb_block_front_css', plugin_dir_url( __FILE__ ).'/build/style-index.css',array( 'wp-edit-blocks' ),null);
	wp_localize_script('oercurr_ctb_block_js', 'oercurr_ctb_legacy_marker', ['legacy' => 'true']);
	register_block_type(
		'oer-curriculum/oer-curriculum-thumbnail-block', array(
			'editor_script' => 'oercurr_ctb_block_js',
			'editor_style'  => 'oercurr_ctb_block_editor_css',
			'style'         => 'oercurr_ctb_block_front_css'
		)
	);
}

if($wp_version < 5.8){
	add_action( 'init', 'oer_curriculum_thumbnail_block_init_legacy' );
}else{
	add_action( 'init', 'oer_curriculum_thumbnail_block_init' );
}



add_action( 'rest_api_init', 'oercurr_add_meta_to_api_v2');
function oercurr_add_meta_to_api_v2() {
    // Register Grade Levels to REST API
    register_rest_field( 'oer-curriculum',
                'oer_curriculum_grades_tax',
                array(
                'get_callback' => 'oercurr_rest_get_meta_field_v2',
                'update_callback' => null,
                'schema' => null
                  ) );

    // Register Featured Image to REST API
    register_rest_field( 'oer-curriculum',
            'featured_image_url',
            array(
                'get_callback'    => 'oercurr_get_rest_featured_image_v2',
                'update_callback' => null,
                'schema'          => null,
            ) );
		
		// Register Featured Image to REST API
    register_rest_field( 'oer-curriculum',
            'oer_curriculum_thumbnail_block_options',
            array(
                'get_callback'    => 'oercurr_rest_get_curriculum_posts',
                'update_callback' => null,
                'schema'          => null,
            ) );
		
		//Path to meta query route
    register_rest_route( 'oercurr/thumbnail', 'optionquery', array(
        'methods' => 'GET',
        'callback' => 'oercurr_rest_get_curriculum_posts',
                    'permission_callback' => '__return_true'
						) );
		
		//Path to meta query route
    register_rest_route( 'oercurr/thumbnail', 'getcurriculum', array(
        'methods' => 'GET',
        'callback' => 'oercurr_rest_get_specific_curriculum',
                    'permission_callback' => '__return_true'
						) );

}

function oercurr_rest_get_meta_field_v2($inquiryset, $field, $request){
    if ($field=="oer_curriculum_grades_tax") {
        return get_the_terms($inquiryset['id'], 'curriculum-grade-level');
    } else{
        return get_post_meta($inquiryset['id'], $field, true);
		}
}

function oercurr_get_rest_featured_image_v2($inquiryset, $field, $request) {
    if( $inquiryset['featured_media'] ){
        $img = wp_get_attachment_image_src( $inquiryset['featured_media'], 'app-thumb' );
        return $img[0];
    }
    return false;
}



function oercurr_rest_get_specific_curriculum($inquiryset){
	$tmparr = array();
	$_cid = sanitize_text_field($_GET['cid']);
	$_curr_sel_post = get_post( $_cid );
	
	//$tmparr = $_curr_sel_post;

	$tmparr['id'] = $_curr_sel_post->ID;
	$tmparr['name'] = $_curr_sel_post->post_title;
	$tmparr['link'] = get_post_permalink($_curr_sel_post->ID);
	$_tmp_image = get_the_post_thumbnail_url($_curr_sel_post->ID,'full');
	$_ctb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
	$tmparr['img'] =  $_ctb_image;

	
	$_oercurr_grade_taxonomy = get_the_terms($_cid, 'curriculum-grade-level');
	$_oercurr_grade  = array();
	foreach($_oercurr_grade_taxonomy as $key=>$value) {
		$_oercurr_grade[$key] = $value->name;
	}

	$tmparr['grade'] = implode(", ",$_oercurr_grade);
	return json_encode($tmparr);
	
}

function oercurr_rest_get_curriculum_posts(){
	$_curlist = array(); 
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'oer-curriculum',
		'orderby' => 'title',
		'order'   => 'ASC',
	);
	$posts = get_posts( $args );
	if($posts){
			$i=0;
			foreach($posts as $post){
					
					$_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
					$_ctb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
					/*
					$_curlist[$post->ID] = array(
						"id" => $post->ID,
						"title" => $post->post_title,
						"link" => get_post_permalink($post->ID),
						"title" => $_ctb_image
					);
					*/
					/*
					$_curlist[$post->ID]['id'] = $post->ID;
					$_curlist[$post->ID]['title'] = $post->post_title;
					//$_curlist[$post->ID]['content'] = html_entity_decode(strip_tags($post->post_content));
					$_curlist[$post->ID]['link'] = get_post_permalink($post->ID);
					$_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
					$_ctb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
					$_curlist[$post->ID]['img'] =  $_ctb_image;
					*/
					
					$_curlist[$i]['id'] = $post->ID;
					$_curlist[$i]['title'] = $post->post_title;
					//$_curlist[$i]['content'] = html_entity_decode(strip_tags($post->post_content));
					$_curlist[$i]['link'] = get_post_permalink($post->ID);
					$_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
					$_ctb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
					$_curlist[$i]['img'] =  $_ctb_image;
					/*
					$term_ids = '';
					$term_objs = get_the_terms($post->ID, 'resource-subject-area');
					if($term_objs){
							foreach ($term_objs as $term_obj){
									$term_ids .= ($term_ids == '')? $term_obj->term_id: '|'.$term_obj->term_id;
							}
					}else{
							$term_ids = false;
					}

					$_curlist[$post->ID]['tax'] = $term_ids;
					$_curlist[$post->ID]['typ'] =  'cur';
					*/
					
					$i++;
					
			}
	}
	
	return $_curlist;
}
