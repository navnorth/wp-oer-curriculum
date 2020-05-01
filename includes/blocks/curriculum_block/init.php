<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * Assets enqueued:
 * 1. blocks.style.build.css - Frontend + Backend.
 * 2. blocks.build.js - Backend.
 * 3. blocks.editor.build.css - Backend.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * @since 1.0.0
 */
 
 wp_enqueue_script( 'curriculum_block-front-js', plugins_url( '/curriculum_block/front.build.js', dirname( __FILE__ ) ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),'1.0.1' , true );
 wp_localize_script( 'curriculum_block-front-js', 'curriculum_block_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
 
function curriculum_block_cgb_block_assets() { // phpcs:ignore
	
	// Register block editor script for backend.
	/*
	wp_register_script(
		'curriculum_block-front-js', // Handle.
		plugins_url( '/dist/front.build.js', dirname( __FILE__ ) ), // front.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/front.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);
	*/
	
	// Register block styles for both frontend + backend.
	wp_register_style(
		'curriculum_block-cgb-style-css', // Handle.
		plugins_url( '/curriculum_block/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'curriculum_block-cgb-block-js', // Handle.
		plugins_url( '/curriculum_block/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'curriculum_block-cgb-block-editor-css', // Handle.
		plugins_url( '/curriculum_block/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
	
	
	
	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'curriculum_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'base_url' => get_home_url(),
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	/**
	 * Register Gutenberg block on server-side.
	 *
	 * Register the block on server-side to ensure that the block
	 * scripts and styles for both frontend and backend are
	 * enqueued when the editor loads.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
	 * @since 1.16.0
	 */
	register_block_type(
		'cgb/block-curriculum-block', array(
			// Enqueue front.script.build.js on both frontend & backend.
			'script'        => 'curriculum_block-front-js',
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'curriculum_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'curriculum_block-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'curriculum_block-cgb-block-editor-css',
			'render_callback' => 'render_posts_block'
		)
	);
}

function render_posts_block($attributes, $ajx=false){
	
		
		//echo $attributes['blockid'].'<br>';
		//echo $attributes['selectedCategory'].'<br>';
		//echo $attributes['postsPerPage'].'<br>';
		//echo $attributes['sortBy'].'<br><br>';
		
	//print_r(json_encode($attributes));
	//print_r($attributes);
  //echo $attributes['selectedCategory'].' - '.$attributes['sortBy'].'<br><br>';
	//echo print_r(get_post_type(24109));
	//echo '<br>';
	
	$bid = $attributes['blockid'];
	$ord = ($attributes['sortBy'] == 'title')? 'ASC': 'DESC';	
	$args = array(
							'posts_per_page' => $attributes['postsPerPage'],
							'post_type' => 'lesson-plans',
							'tax_query' => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'resource-subject-area',
									//'terms' => array('1105'),
									'terms' => explode(',', $attributes['selectedCategory']),
									'field' => 'term_id',
									'include_children' => false,
								)
							),
							'orderby' => $attributes['sortBy'],
            	'order'   => $ord,
						);
	$posts = get_posts( $args );

	$_count = count($posts);
	$_wrapper = '';
	$_content = '';

	foreach($posts as $post){
		$_content .= '<div class="lp-cur-blk-row">';
			$featured_img_url = get_the_post_thumbnail_url($post->ID,'medium'); 
			$_content .= '<a href="'.get_post_permalink($post->ID).'" class="lp-cur-blk-left"><img src="'.$featured_img_url.'" alt="" /></a>';
			$_content .= '<div class="lp-cur-blk-right">';
				$_content .= '<div class="ttl"><a href="'.get_post_permalink($post->ID).'">'.$post->post_title.'</a></div>';
				$_content .= '<div class="lp-cur-postmeta">';
					if(count($post->oer_lp_grades)>1){
						$_content .= '<span class="lp-cur-postmeta-grades"><strong>Grades:</strong> '. $post->oer_lp_grades[0].'-'.$post->oer_lp_grades[count($post->oer_lp_grades)-1].'</span>';
					}else{
						if($post->oer_lp_grades[0] != ''){
								$_content .= '<span class="lp-cur-postmeta-grades"><strong>Grade:</strong> '. $post->oer_lp_grades[0].'</span>';
						}
					}
				$_content .= '</div>';					
				if(trim($post->post_content," ") != ''){
					$_content .= '<div class="desc">'.substr(wp_strip_all_tags($post->post_content),0,180).' ...</div>';
				}			
				$_arr_tag = get_the_tags($post->ID);
				$_content .= '<div class="lp-cur-tags tagcloud">';
					foreach($_arr_tag as $key => $tag) {
						$_content .= '<span><a href="'.get_home_url().'/tag/'.$tag->slug.'" alt="" class="button">'.$tag->name.'</a></span>';
					}
				$_content .= '</div">';					
			$_content .= '</div>';
		$_content .= '</div>';
		$_content .= '</div>';
	}

	


	$_wrapper .= '<div class="lp-cur-blk-main" blockid="'.$bid.'">';
		$_wrapper .= '<script>';
			//$_wrapper .= 'jQuery( document ).ready(function() {';
				$_wrapper .= 'localStorage.setItem("selectedCategory-'.$bid.'", "'.$attributes['selectedCategory'].'");';
				$_wrapper .= 'localStorage.setItem("postsPerPage-'.$bid.'", "'.$attributes['postsPerPage'].'");';
				$_wrapper .= 'localStorage.setItem("sortBy-'.$bid.'", "'.$attributes['sortBy'].'");';
			//$_wrapper .= '});';
		$_wrapper .= '</script>';
		$_wrapper .= '<div class="lp-cur-blk-topbar">';	
			$_wrapper .= '<div class="lp-cur-blk-topbar-left">';
				$_wrapper .= '<span>Browse All '.$_count.' Curriculums</span>';
			$_wrapper .= '</div>';
			$_wrapper .= '<div class="lp-cur-blk-topbar-right">';	
					$_wrapper .= '<div class="lp-cur-blk-topbar-display-box">';
						$_wrapper .= '<div class="lp-cur-blk-topbar-display-text"><span>Show '.$attributes['postsPerPage'].'</span><a href="#"><i class="fa fa-th-list" aria-hidden="true"></i></a></div>';
						$_wrapper .= '<ul class="lp-cur-blk-topbar-display-option lp-cur-blk-topbar-option" style="display:none;">';	
									for ($i=5; $i <=30; $i+=5){ 
										 if($i == $attributes['postsPerPage']){
											 $_wrapper .= '<li class="selected"><a href="#" ret="'.$i.'">'.$i.'</a></li>';
										 }else{
											 $_wrapper .= '<li><a href="#" ret="'.$i.'">'.$i.'</a></li>';
										 }
									}
						$_wrapper .= '</ul>';
					$_wrapper .= '</div>';					
					$_wrapper .= '<div class="lp-cur-blk-topbar-sort-box">';
						$_wrapper .= '<div class="lp-cur-blk-topbar-sort-text"><span>Sort by: '.$attributes['sortBy'].'</span><a href="#"><i class="fa fa-sort" aria-hidden="true"></i></a></div>';
						$_wrapper .= '<ul class="lp-cur-blk-topbar-sort-option lp-cur-blk-topbar-option" style="display:none;">';
									$_sel = ($attributes['sortBy'] == 'date')? 'class="selected"':'';
									$_wrapper .= '<li '.$_sel.'><a href="#" ret="date">Date Added</a></li>';
									$_sel = ($attributes['sortBy'] == 'modified')? 'class="selected"':'';
									$_wrapper .= '<li '.$_sel.'><a href="#" ret="modified">Date Updated</a></li>';
									$_sel = ($attributes['sortBy'] == 'title')? 'class="selected"':'';
									$_wrapper .= '<li '.$_sel.'><a href="#" ret="title">Title a-z</a></li>';
						$_wrapper .= '</ul>';
					$_wrapper .= '</div>';					
			$_wrapper .= '</div>';
		$_wrapper .= '</div>';
	
		$_wrapper .= '<div id="lp_cur_blk_content_wrapper"  class="lp-cur-blk-wrapper">';
			$_wrapper .= '<div id="lp-cur-blk-content_drop">';
				if(!count($posts) > 0){
					$_wrapper = 'No Curriculum Found.';
				}else{
					$_wrapper .= $_content;
				}
			$_wrapper .= '</div>';
			
			// Preloader Start
			$_wrapper .= '<div class="lp_cur_blk_content_preloader_table" style="display:none;">';
				$_wrapper .= '<div class="lp_cur_blk_content_preloader_cell">';
					$_wrapper .= '<div class="lds-dual-ring"></div>';
				$_wrapper .= '</div>';
				$_wrapper .= '<div class="lp_cur_blk_content_preloader_overlay"></div>';
			$_wrapper .= '</div>';		
			// Preloader End
			
		$_wrapper .= '</div>';
		
		
		
	$_wrapper .= '</div>';
	
	
	if(!$ajx){
		$_ret = $_wrapper;
	}else{
		$_arr['cnt'] = $_count;
		$_arr['data'] = $_content;
		$_ret = json_encode($_arr);
	}
	
	return $_ret;
	
}

// Hook: Block assets.
add_action( 'init', 'curriculum_block_cgb_block_assets' );



function ajxRebuildPostsBlock(){
	$_arr = array();
	$_arr['selectedCategory'] = $_POST['sel'];
	$_arr['postsPerPage']     = $_POST['per'];
	$_arr['sortBy']           = $_POST['srt'];	
	echo render_posts_block($_arr, true);
	//echo json_encode($_arr);
	die();
}
add_action( 'wp_ajax_ajxRebuildPostsBlock', 'ajxRebuildPostsBlock' );
add_action('wp_ajax_nopriv_ajxRebuildPostsBlock', 'ajxRebuildPostsBlock');




// Register a REST route
add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'curriculum/v2', 'taxquery', array(
            'methods' => 'GET', 
            'callback' => 'curriculum_tax_query' 
    ) );
});


function curriculum_tax_query(){
	$_taxonomy = $_GET['taxonomy'];
	$_postperpage = $_GET['postperpage'];
	$_taxterms = $_GET['taxterms'];
	$_sortby = $_GET['sortby'];
	if($_taxterms != ''){
		$args = array(
			'posts_per_page' => $_postperpage,
			'post_type' => 'lesson-plans',
			'tax_query' => array(
				//'relation' => 'AND',
				array(
					'taxonomy' => $_taxonomy,
					'terms' => explode(',', $_taxterms),
					'field' => 'term_id',
				)
			),
			'orderby' => $_sortby,
	  	'order'   => 'ASC',
		);
	}else{
		$args = array(
			'posts_per_page' => $_postperpage,
			'post_type' => 'lesson-plans',
			'orderby' => $_sortby,
	  	'order'   => 'ASC',
		);
	}
	$posts = get_post( $args );
	return $posts;
}



