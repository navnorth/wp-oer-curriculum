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
 
function curriculum_featured_block_cgb_block_assets() { // phpcs:ignore
	// Register block styles for both frontend + backend.
	wp_register_style(
		'curriculum_featured_block-cgb-style-css', // Handle.
		plugins_url( 'curriculum-featured-block/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	);

	// Register block editor script for backend.
	wp_register_script(
		'curriculum_featured_block-cgb-block-js', // Handle.
		plugins_url( '/curriculum-featured-block/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	wp_register_style(
		'curriculum_featured_block-cgb-block-editor-css', // Handle.
		plugins_url( 'curriculum-featured-block/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
		null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	);
	
	
	/*
	wp_register_script(
		'curriculum-featured-block-jquery.bxslider.js', // Handle.
		OER_URL.'js/jquery.bxslider.js', // front.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'jquery' ), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/front.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);
	*/
	

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'curriculum_featured_block-cgb-block-js',
		'cgbGlobal', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'base_url' => get_home_url(),
			'curriculum_plugin_url' => OER_LESSON_PLAN_URL,
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
		'cgb/block-curriculum-featured-block', array(
			// Enqueue blocks.style.build.css on both frontend & backend.
			'style'         => 'curriculum_featured_block-cgb-style-css',
			// Enqueue blocks.build.js in the editor only.
			'editor_script' => 'curriculum_featured_block-cgb-block-js',
			// Enqueue blocks.editor.build.css in the editor only.
			'editor_style'  => 'curriculum_featured_block-cgb-block-editor-css',
			'render_callback' => 'render_featured_block'
		)
	);
}

// Hook: Block assets.
add_action( 'init', 'curriculum_featured_block_cgb_block_assets' );

function curriculum_featured_block_additional_script_front( $hook ) {
		wp_enqueue_style('curriculum-feat-block-resource-category-style-css', OER_URL.'css/resource-category-style.css');
		wp_enqueue_style('curriculum-feat-block-jquery-bxslider-css', OER_URL.'css/jquery.bxslider.css');
		wp_enqueue_script('curriculum-feat-block-jquery-bxslider-js', OER_URL.'js/jquery.bxslider.js',array('jquery'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'curriculum_featured_block_additional_script_front' );

function curriculum_featured_block_additional_script( $hook ) {
		wp_enqueue_style('curriculum-feat-block-resource-category-style-css', OER_URL.'css/resource-category-style.css');
		wp_enqueue_style('curriculum-feat-block-jquery-bxslider-css', OER_URL.'css/jquery.bxslider.css');
		wp_enqueue_script('curriculum-feat-block-jquery-bxslider-js', OER_URL.'js/jquery.bxslider.js',array('jquery'), '1.0' );
		wp_enqueue_script('curriculum-feat-block-jquery-ui-min-js', plugins_url( 'curriculum-featured-block/jquery-ui.min.js', dirname( __FILE__ ) ) ,array('jquery'), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'curriculum_featured_block_additional_script' );




function render_featured_block($attributes, $ajx=false){
	
	//print_r($attributes['selectedfeatured']); echo '<br>';
	$feats = explode(",",$attributes['selectedfeatured']);
	$blkid = $attributes['blockid'];
	$_ret .= '<div class="oer_right_featuredwpr">';
		$_ret .= '<div class="oer-ftrdttl curriculum-feat-title_'.$attributes['blockid'].'">'.$attributes['blocktitle'].'</div>';
		$_ret .= '<ul class="featuredwpr_bxslider_front featuredwpr_bxslider_front_'.$attributes['blockid'].'" blk="'.$attributes['blockid'].'" style="visibility:hidden;">';
		
				foreach($feats as $val){
					$feat = explode("|",$val);
					$feat_id = $feat[0]; $feat_type = $feat[1];
					
					$_post = get_post($feat_id);
					$_cfb_link = get_post_permalink($_post->ID);
					$_cfb_title = $_post->post_title;
					$_cfb_desc = substr(html_entity_decode(strip_tags($_post->post_content)),0,150);
					$_tmp_image = get_the_post_thumbnail_url($_post->ID,'medium');
					$_cfb_image = (!$_tmp_image)? OER_LESSON_PLAN_URL.'assets/images/default-img.jpg': $_tmp_image;
					
						
							$_ret .= '<li>';
								$_ret .= '<div class="frtdsnglwpr">';
									$_ret .= '<a href="'.$_cfb_link.'">';
										$_ret .= '<div class="img">';
										
												$_ret .= '<img src="'.$_cfb_image.'" alt="'.$_cfb_title.'" />';
										
										$_ret .= '</div>';
									$_ret .= '</a>';
									$_ret .= '<div class="ttl"><a href="'.$_cfb_link.'">'.$_cfb_title.'</a></div>';
									$_ret .= '<div class="desc">'.$_cfb_desc.'</div>';
								$_ret .= '</div>';
							$_ret .= '</li>';
				}
				
		$_ret .= '</ul>';
	$_ret .= '</div>';

	
	$_ret .= '<script>';
		$_ret .= 'jQuery(document).ready(function(){';
			
			$_ret .= 'jQuery(".featuredwpr_bxslider_front_'.$attributes['blockid'].'").bxSlider({';
					
					//print_r($attributes);
					/*
					echo $blkid.'<br>';
					echo $attributes['minslides'].'<br>';
					echo $attributes['maxslides'].'<br>';
					echo $attributes['moveslides'].'<br>';
					echo $attributes['slidewidth'].'<br>';
					echo $attributes['slidemargin'].'<br>';
					*/

					
					$_ret .= 'minSlides: '.$attributes['minslides'].',';
					$_ret .= 'maxSlides: '.$attributes['maxslides'].',';
					$_ret .= 'moveSlides: '.$attributes['moveslides'].',';
					$_ret .= 'slideWidth: '.$attributes['slidewidth'].',';
					$_ret .= 'slideMargin: '.$attributes['slidemargin'].',';
					$_ret .= 'pager: false,';
					$_ret .= 'onSliderLoad: function(currentIndex) {';
							$_ret .= 'jQuery(".featuredwpr_bxslider_front_'.$attributes['blockid'].'").css({"visibility":"visible","height":"auto"});';				
							$_ret .= 'let dtc = jQuery(".curriculum-feat-title_'.$attributes['blockid'].'").detach();';
							$_ret .= 'jQuery(dtc).insertBefore(jQuery(".featuredwpr_bxslider_front_'.$attributes['blockid'].'").parent(".bx-viewport"));';
							
					$_ret .= '}';
			$_ret .= '});';
			
		$_ret .= '});';
	$_ret .= '</script>';
	
	
	return $_ret;
}

// Register a REST route
add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'curriculum/feat', 'resourcequery', array(
            'methods' => 'GET', 
            'callback' => 'curriculum_feat_resource_query' 
    ) );
		
		register_rest_route( 'curriculum/feat', 'curriculumquery', array(
            'methods' => 'GET', 
            'callback' => 'curriculum_feat_curriculum_query' 
    ) );
		
		
		register_rest_route( 'curriculum/feat', 'taxquery', array(
            'methods' => 'GET', 
            'callback' => 'curriculum_feat_tax_query' 
    ) );
		
});


function curriculum_feat_tax_query(){
	$_posttype = $_GET['posttype'];
	$_arr = array();
	$term_query = new WP_Term_Query( 
		array(
						'taxonomy' => 'resource-subject-area',
						'number' => 0,
						'parent' => 0,
						'hide_empty' => 1
				) 
		);	
	if ( ! empty( $term_query->terms ) ) {
		$cnt = 0;
    foreach ( $term_query ->terms as $term ) {
			
			$args = array(
				'posts_per_page' => -1,
				'post_type' => $_posttype,
				'post_status' => 'published',
				'tax_query' => array(
					array(
						'taxonomy' => 'resource-subject-area',
						'terms' => array($term->term_id),
						'field' => 'term_id', //get the termids only to cut down resources
						'include_children' => false,
					),
					'fields'=> 'ids' //get the ids only to cut down resources
				)
			);
			
			$_arr[$cnt]['term_id'] = $term->term_id;
			$_arr[$cnt]['name'] = $term->name;
			$_arr[$cnt]['level'] = 'parent';
			$_arr[$cnt]['parent'] = $term->parent;
			$_arr[$cnt]['cnt'] = count(get_posts($args));
			//**************************************
			$cnt++;
			
			$childterm_query = new WP_Term_Query( array('taxonomy'=>'resource-subject-area','number'=>0,'parent'=>$term->term_id,'hide_empty'=>true) );	
			if ( ! empty( $childterm_query->terms ) ) {
				foreach ( $childterm_query->terms as $childterm ) {

					$args = array(
						'posts_per_page' => -1,
						'post_type' => $_posttype,
						'post_status' => 'published',
						'tax_query' => array(
							array(
								'taxonomy' => 'resource-subject-area',
								'terms' => array($childterm->term_id),
								'field' => 'term_id', //get the termids only to cut down resources
								'include_children' => false,
							)
						),
						'fields'=> 'ids'  //get the ids only to cut down resources
					);
					
					$_arr[$cnt]['term_id'] = $childterm->term_id;
					$_arr[$cnt]['name'] = $childterm->name;
					$_arr[$cnt]['level'] = 'child';
					$_arr[$cnt]['parent'] = $childterm->parent;
					$_arr[$cnt]['cnt'] = count(get_posts($args));
					$cnt++;
				}
			}
			//**************************************
			
    }
	} 
	 
	return $_arr;
}


function curriculum_feat_resource_query(){
	
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'resource',
		'orderby' => 'title',
  	'order'   => 'ASC',
	);
	/*
	WP_Post Object
	(
	    [ID] => 23836
	    [post_author] => 1
	    [post_date] => 2019-09-02 03:03:22
	    [post_date_gmt] => 2019-09-02 08:03:22
	    [post_content] => 
	    [post_title] => [TESTING] Polar Bears
	    [post_excerpt] => 
	    [post_status] => publish
	    [comment_status] => closed
	    [ping_status] => closed
	    [post_password] => 
	    [post_name] => testing-polar-bears
	    [to_ping] => 
	    [pinged] => 
	    [post_modified] => 2019-09-02 03:03:22
	    [post_modified_gmt] => 2019-09-02 08:03:22
	    [post_content_filtered] => 
	    [post_parent] => 0
	    [guid] => http://k12.localhost.localdomain/?post_type=resource&amp;p=23836
	    [menu_order] => 0
	    [post_type] => resource
	    [post_mime_type] => 
	    [comment_count] => 0
	    [filter] => raw
	)
	*/
	$posts = get_posts( $args );
	if($posts){
		$_ret = array(); $i=0;
		foreach($posts as $post){
			$_ret[$i]['id'] = $post->ID;
			$_ret[$i]['title'] = $post->post_title;
			$_ret[$i]['content'] = $post->post_content;
			$_ret[$i]['link'] = get_post_permalink($post->ID);
				$_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
				$_cfb_image = (!$_tmp_image)? OER_LESSON_PLAN_URL.'assets/images/default-img.jpg': $_tmp_image;
			$_ret[$i]['img'] =  $_cfb_image;
			
			$term_ids = '';
			$term_objs = get_the_terms($post->ID, 'resource-subject-area');
			if($term_objs){
				foreach ($term_objs as $term_obj){
					$term_ids .= ($term_ids == '')? $term_obj->term_id: '|'.$term_obj->term_id;
				}   
			}else{
				$term_ids = false;
			}
			
			$_ret[$i]['tax'] = $term_ids;
			$_ret[$i]['typ'] =  'res';
			$i++;
		}
	}
	
	return $_ret;
	
}

function curriculum_feat_curriculum_query(){
	
	$args = array(
		'posts_per_page' => -1,
		'post_type' => 'lesson-plans',
		'orderby' => 'title',
  	'order'   => 'ASC',
	);
	/*
	WP_Post Object
	(
	    [ID] => 23836
	    [post_author] => 1
	    [post_date] => 2019-09-02 03:03:22
	    [post_date_gmt] => 2019-09-02 08:03:22
	    [post_content] => 
	    [post_title] => [TESTING] Polar Bears
	    [post_excerpt] => 
	    [post_status] => publish
	    [comment_status] => closed
	    [ping_status] => closed
	    [post_password] => 
	    [post_name] => testing-polar-bears
	    [to_ping] => 
	    [pinged] => 
	    [post_modified] => 2019-09-02 03:03:22
	    [post_modified_gmt] => 2019-09-02 08:03:22
	    [post_content_filtered] => 
	    [post_parent] => 0
	    [guid] => http://k12.localhost.localdomain/?post_type=resource&amp;p=23836
	    [menu_order] => 0
	    [post_type] => resource
	    [post_mime_type] => 
	    [comment_count] => 0
	    [filter] => raw
	)
	*/
	$posts = get_posts( $args );
	if($posts){
		$_ret = array(); $i=0;
		foreach($posts as $post){
			
			$_ret[$i]['id'] = $post->ID;
			$_ret[$i]['title'] = $post->post_title;
			$_ret[$i]['content'] = $post->post_content;
			$_ret[$i]['link'] = get_post_permalink($post->ID);
			$_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
			$_cfb_image = (!$_tmp_image)? OER_LESSON_PLAN_URL.'assets/images/default-img.jpg': $_tmp_image;
			$_ret[$i]['img'] =  $_cfb_image;
			
			$term_ids = '';
			$term_objs = get_the_terms($post->ID, 'resource-subject-area');
			if($term_objs){
				foreach ($term_objs as $term_obj){
					$term_ids .= ($term_ids == '')? $term_obj->term_id: '|'.$term_obj->term_id;
				}   
			}else{
				$term_ids = false;
			}
			
			$_ret[$i]['tax'] = $term_ids;
			$_ret[$i]['typ'] =  'cur';
			$i++;
		}
	}
	
	return $_ret;
	
}





function initiate_admin_bx_slider() {
	global $pagenow;
	if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
		?>
		<script>

		var curriculumfeatsliders = new Array();
	  var curriculumfeatbxconfig;
		let newblockadded = true;
		
		jQuery(document).ready(function(){
	
			
			jQuery(document).on('click','.lp_inspector_feat_addResources',function(e){
		    jQuery('.lp_inspector_feat_modal_resource_wrapper').show(300);
		  });
		  
		  jQuery(document).on('click','.lp_inspector_feat_addCurriculum',function(e){
		    jQuery('.lp_inspector_feat_modal_curriculum_wrapper').show(300);
		  });
		  
		  jQuery(document).on('click','.lp_inspector_feat_modal_wrapper_close span.dashicons',function(e){
		    jQuery('.lp_inspector_feat_modal_resource_wrapper').hide(300);
		    jQuery('.lp_inspector_feat_modal_curriculum_wrapper').hide(300);
		  })
		  
		  jQuery(document).on('click','.lp_inspector_feat_hlite_node span.dashicons',function(e){
				let itemid = jQuery(this).parent().attr('data');
				let itemtype = jQuery(this).parent('.lp_inspector_feat_hlite_node').attr('typ');
				jQuery(this).parent('.lp_inspector_feat_hlite_node').removeClass('stay');
				jQuery('.lp_inspector_feat_hlite_remove_trigger').trigger('click');
				jQuery('input[data="'+itemid+'"]').prop('checked',false);
				var blkid = jQuery('.lp_inspector_feat_hlite_remove_trigger').attr('blkid');
				curriculumfeatslider_reset(blkid, 750);
		  })

		});
		

		function curriculumfeatslider_loadall(featblockcount){
		
			var checkExist = setInterval(function() {
				var numitems = jQuery('ul.featuredwpr_bxslider').length;
				
			 	if (numitems == featblockcount) {
						clearInterval(checkExist);	
						setTimeout(function(){		
							//console.log('*******************');
							
							jQuery('.featuredwpr_bxslider').each(function(i, slider) {
								
								blkid = jQuery(slider).attr('blk');
								
								//console.log('LENGTH: '+jQuery(slider).parent('.bx-viewport').length);
								//console.log('--'+blkid);
								//if (cgbGlobal['featuredwpr_bxslider_'+blkid] ===undefined){
								//if (curriculumfeatsliders[i]===undefined){
								if(jQuery(slider).parent('.bx-viewport').length == 0){
									//blkid = jQuery(slider).attr('blk');
									
									//curriculumfeatsliders.splice(i, 0, '');
									//curriculumfeatsliders[i] = jQuery(slider).bxSlider({
									cgbGlobal['featuredwpr_bxslider_'+blkid] = jQuery(slider).bxSlider({
											minSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-minslides")),
											maxSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-maxslides")),
											moveSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-moveslides")),
											slideWidth: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-slidewidth")),
											slideMargin: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-slidemargin")),
											pager: false,
											onSliderLoad: function(currentIndex) {
													localStorage.setItem("curriculumFeatCurrentSlideIndex-"+blkid, 0);
													jQuery('.featuredwpr_bxslider').css({'visibility':'visible','height':'auto'});
													let dtc = jQuery('.curriculum-feat-title_'+blkid).detach();
													jQuery(dtc).insertBefore(jQuery(slider).parent('.bx-viewport'));										
													
											},
											onSlideAfter: function($slideElm, oldIndex, newIndex) {
												var blkid = jQuery(slider).attr('blk');
												lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, blkid)
											}
									});					
									jQuery(slider).attr('idx',i);
									
								}
								//console.log(cgbGlobal['featuredwpr_bxslider_'+blkid]);
							});
							
							//console.log('*******************');
							
						}, 750); //set timeout
				 }	
			}, 100); //set interval
		}
		

		function curriculumfeatslider_reset(blkid,speed){

			/*
			if ( jQuery('.featuredwpr_bxslider_'+blkid).children().length <= 0 ) {
			     return curriculumfeatslider_load(blkid);
			}		
			*/
			
			var startIndex = localStorage.getItem("curriculumFeatCurrentSlideIndex-"+blkid);
	    if(startIndex == null)
	        startIndex = 0;
			
			setTimeout(function(){
						let elmblkid = jQuery('.featuredwpr_bxslider_'+blkid).attr('blk');
						let bxidx = jQuery('.featuredwpr_bxslider_'+blkid).attr('idx');
						let dtc = jQuery('.curriculum-feat-title_'+blkid).detach();
						
						jQuery('.featuredwpr_bxslider_'+blkid).parents('.bx-viewport').siblings('.oer-ftrdttl').remove();
						cgbGlobal['featuredwpr_bxslider_'+blkid].reloadSlider({
							startSlide: startIndex,
							minSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-minslides")),
							maxSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-maxslides")),
							moveSlides: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-moveslides")),
							slideWidth: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-slidewidth")),
							slideMargin: parseInt(localStorage.getItem("lpInspectorFeatSliderSetting-"+blkid+"-slidemargin")),
							pager: false,
							onSliderLoad: function(currentIndex) {
									jQuery('.featuredwpr_bxslider_'+blkid).css({'visibility':'visible','height':'auto'});								
									jQuery(dtc).insertBefore(jQuery('.featuredwpr_bxslider_'+blkid).parent('.bx-viewport'));	
							},
							onSlideAfter: function($slideElm, oldIndex, newIndex) {
								lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, elmblkid)
							}
						});			
			}, speed);
		}
		
		function lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, elmblkid) {
	  	localStorage.setItem("curriculumFeatCurrentSlideIndex-"+elmblkid, newIndex);
		}
		
		
		
		
		function sort(){
				jQuery(".lp_inspector_feat_hlite_list div").sortable({
					placeholder: "lp_inspector_feat_hlite_node-state-highlight",
					connectWith: ".lp_inspector_feat_hlite_featured",
					cancel: ".lp_inspector_feat_hlite_node .dashicons-dismiss",
					update: function(event, ui) {  
						jQuery('.lp_inspector_feat_hlite_reposition_trigger').trigger('click');
						var blkid = jQuery('.lp_inspector_feat_hlite_reposition_trigger').attr('blkid');
						curriculumfeatslider_reset(blkid, 750);
					}
				});
		}
		
		
		
		function activatefeatsort(){
			var featexist = setInterval(function() {
				 if (jQuery('.lp_inspector_feat_hlite_list').length) {
						clearInterval(featexist);
						setTimeout(function(){
							console.log('SORT ACTIVATED');
							sort();
						}, 500);
				 }
			}, 100); // check every 100ms
		}
		
		
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'initiate_admin_bx_slider' );

