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
define( 'OERCURR_CFB_CURRICULUM_URL', plugin_dir_url(__FILE__) );
define( 'OERCURR_CFB_BLK_PLUGIN_DIR_PATH', plugin_dir_path( __DIR__ ) );

define( 'OERCURR_CFB_BLK_PLUGIN_DIR_URL', OERCURR_CURRICULUM_URL."/includes/blocks/curriculum-featured-block" );
define( 'OERCURR_CFB_BLK_BASE_URL', get_home_url() );

define( 'OERCURR_CFB_BLK_CURRICULUM_PLUGIN_URL', OERCURR_CURRICULUM_URL );
define( 'OERCURR_CFB_BLK_BX_RESET_BLOCKED', false );
define( 'OERCURR_CFB_BLK_SLIDE_DESC_LEN', 150 );
define( 'OERCURR_CFB_BLK_BLOCK_WIDTH', 150 );
define( 'OERCURR_CFB_BLK_SLIDE_IMG_HEIGHT', 225 );

function oercurr_cfb_block_assets() { // phpcs:ignore
    // Register block styles for both frontend + backend.
    wp_register_style(
        'curriculum_featured_block-cgb-style-css', // Handle.
        plugins_url( '/curriculum-featured-block/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
        is_admin() ? array( 'wp-editor' ) : null, 
        null
    );

    // Register block editor script for backend.
    wp_register_script(
        'curriculum_featured_block-cgb-block-js', // Handle.
        plugins_url( '/curriculum-featured-block/blocks.build.js', dirname( __FILE__ ) ),
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), 
        null, 
        true
    );

    // Register block editor styles for backend.
    wp_register_style(
        'curriculum_featured_block-cgb-block-editor-css', // Handle.
        plugins_url( '/curriculum-featured-block/blocks.editor.build.css', dirname( __FILE__ ) ),
        array( 'wp-edit-blocks' ),
        null
    );


    // WP Localized globals. Use dynamic PHP stuff in JavaScript via `oercurr_cfb_cgb_Global` object.
    wp_localize_script(
        'curriculum_featured_block-cgb-block-js',
        'oercurr_cfb_cgb_Global', // Array containing dynamic data for a JS Global.
        [
            'pluginDirPath' => OERCURR_CFB_BLK_PLUGIN_DIR_PATH,
            //'pluginDirUrl' => plugin_dir_url( __DIR__ ),
            'pluginDirUrl' => OERCURR_CFB_BLK_PLUGIN_DIR_URL,
            'base_url' => OERCURR_CFB_BLK_BASE_URL,
            'curriculum_plugin_url' => OERCURR_CFB_BLK_CURRICULUM_PLUGIN_URL,
            'bxresetblocked' => OERCURR_CFB_BLK_BX_RESET_BLOCKED,
            'slidedesclength' => OERCURR_CFB_BLK_SLIDE_DESC_LEN,
            'blockwidth' => OERCURR_CFB_BLK_BLOCK_WIDTH,
            'slideimageheight' => OERCURR_CFB_BLK_SLIDE_IMG_HEIGHT,
            'preview_url' => OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-featured-block/images/blockpreview.png',
            // Add more data here that you want to access from `oercurr_cfb_cgb_Global` object.
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
        'oer-curriculum/block-curriculum-featured-block', array(
            // Enqueue blocks.style.build.css on both frontend & backend.
            'style'         => 'curriculum_featured_block-cgb-style-css',
            // Enqueue blocks.build.js in the editor only.
            'editor_script' => 'curriculum_featured_block-cgb-block-js',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'  => 'curriculum_featured_block-cgb-block-editor-css',
            //'render_callback' => 'oercurr_cfb_render_featured_block'
        )
    );
}

// Hook: Block assets.
add_action( 'init', 'oercurr_cfb_block_assets' );

function oercurr_cfb_additional_script_front( $hook ) {
    wp_enqueue_style('curriculum-feat-block-jquery-bxslider-css', OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-featured-block/jquery.bxslider.css');
    wp_enqueue_script('curriculum-feat-block-jquery-bxslider-js', OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-featured-block/jquery.bxslider.js',array('jquery'), '1.0' );
}
add_action( 'wp_enqueue_scripts', 'oercurr_cfb_additional_script_front' );

function oercurr_cfb_additional_script( $hook ) {
    //wp_enqueue_style('curriculum-feat-block-resource-category-style-css', OER_URL.'css/resource-category-style.css');
    wp_enqueue_style('curriculum-feat-block-jquery-bxslider-css', OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-featured-block/jquery.bxslider.css');
    wp_enqueue_script('curriculum-feat-block-jquery-bxslider-js', OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-featured-block/jquery.bxslider.js',array('jquery'), '1.0' );
}
add_action( 'admin_enqueue_scripts', 'oercurr_cfb_additional_script' );

// Register a REST route
add_action( 'rest_api_init', function () {
    //Path to meta query route
        register_rest_route( 'curriculum/feat', 'dataquery', array(
            'methods' => 'GET',
            'callback' => 'oercurr_cfb_dataquery',
                        'permission_callback' => '__return_true'
    ) );
});


function oercurr_cfb_dataquery(){


    $_arr = array();

    // TAXONOMY QUERY RESOURCE
    $_taxres = array();
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
                'post_type' => 'resource',
                'post_status' => 'publish',
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

            $_taxres[$cnt]['term_id'] = $term->term_id;
            $_taxres[$cnt]['name'] = $term->name;
            $_taxres[$cnt]['level'] = 'parent';
            $_taxres[$cnt]['parent'] = $term->parent;
            $_taxres[$cnt]['cnt'] = count(get_posts($args));
            //**************************************
            $cnt++;

            $childterm_query = new WP_Term_Query( array('taxonomy'=>'resource-subject-area','number'=>0,'parent'=>$term->term_id,'hide_empty'=>true) );
            if ( ! empty( $childterm_query->terms ) ) {
                foreach ( $childterm_query->terms as $childterm ) {

                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'resource',
                        'post_status' => 'publish',
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

                    $_taxres[$cnt]['term_id'] = $childterm->term_id;
                    $_taxres[$cnt]['name'] = $childterm->name;
                    $_taxres[$cnt]['level'] = 'child';
                    $_taxres[$cnt]['parent'] = $childterm->parent;
                    $_taxres[$cnt]['cnt'] = count(get_posts($args));
                    $cnt++;
                }
            }
            //**************************************

        }
    }


    // TAXONOMY QUERY CURRICULUM
    $_taxcur = array();
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
                'post_type' => 'oer-curriculum',
                'post_status' => 'publish',
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

            $_taxcur[$cnt]['term_id'] = $term->term_id;
            $_taxcur[$cnt]['name'] = $term->name;
            $_taxcur[$cnt]['level'] = 'parent';
            $_taxcur[$cnt]['parent'] = $term->parent;
            $_taxcur[$cnt]['cnt'] = count(get_posts($args));
            //**************************************
            $cnt++;

            $childterm_query = new WP_Term_Query( array('taxonomy'=>'resource-subject-area','number'=>0,'parent'=>$term->term_id,'hide_empty'=>true) );
            if ( ! empty( $childterm_query->terms ) ) {
                foreach ( $childterm_query->terms as $childterm ) {

                    $args = array(
                        'posts_per_page' => -1,
                        'post_type' => 'oer-curriculum',
                        'post_status' => 'publish',
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

                    $_taxcur[$cnt]['term_id'] = $childterm->term_id;
                    $_taxcur[$cnt]['name'] = $childterm->name;
                    $_taxcur[$cnt]['level'] = 'child';
                    $_taxcur[$cnt]['parent'] = $childterm->parent;
                    $_taxcur[$cnt]['cnt'] = count(get_posts($args));
                    $cnt++;
                }
            }
            //**************************************

        }
    }

    // RESOURCES POSTS
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'resource',
        'orderby' => 'title',
      'order'   => 'ASC',
    );

    $posts = get_posts( $args );
    if($posts){
        $_reslist = array(); $i=0;
        foreach($posts as $post){
            $_reslist[$i]['id'] = $post->ID;
            $_reslist[$i]['title'] = $post->post_title;
            $_sliddesclength = (!isset($attributes['slidedesclength']))? OERCURR_CFB_BLK_SLIDE_DESC_LEN : sanitize_text_field($attributes['slidedesclength']);
            $_reslist[$i]['content'] = html_entity_decode(strip_tags($post->post_content));
            $_reslist[$i]['link'] = get_post_permalink($post->ID);
                $_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
                $_cfb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
            $_reslist[$i]['img'] =  $_cfb_image;
            $term_ids = '';
            $term_objs = get_the_terms($post->ID, 'resource-subject-area');
            if($term_objs){
                foreach ($term_objs as $term_obj){
                    $term_ids .= ($term_ids == '')? $term_obj->term_id: '|'.$term_obj->term_id;
                }
            }else{
                $term_ids = false;
            }
            $_reslist[$i]['tax'] = $term_ids;
            $_reslist[$i]['typ'] =  'res';
            $i++;
        }
    }

    // CURRICULUM POSTS
    $args = array(
        'posts_per_page' => -1,
        'post_type' => 'oer-curriculum',
        'orderby' => 'title',
      'order'   => 'ASC',
    );
    $posts = get_posts( $args );
    if($posts){
        $_curlist = array(); $i=0;
        foreach($posts as $post){

            $_curlist[$i]['id'] = $post->ID;
            $_curlist[$i]['title'] = $post->post_title;
            $_curlist[$i]['content'] = html_entity_decode(strip_tags($post->post_content));
            $_curlist[$i]['link'] = get_post_permalink($post->ID);
            $_tmp_image = get_the_post_thumbnail_url($post->ID,'medium');
            $_cfb_image = (!$_tmp_image)? OERCURR_CURRICULUM_URL.'assets/images/default-img.jpg': $_tmp_image;
            $_curlist[$i]['img'] =  $_cfb_image;

            $term_ids = '';
            $term_objs = get_the_terms($post->ID, 'resource-subject-area');
            if($term_objs){
                foreach ($term_objs as $term_obj){
                    $term_ids .= ($term_ids == '')? $term_obj->term_id: '|'.$term_obj->term_id;
                }
            }else{
                $term_ids = false;
            }

            $_curlist[$i]['tax'] = $term_ids;
            $_curlist[$i]['typ'] =  'cur';
            $i++;
        }
    }


    $_arr[0] = $_taxres;
    $_arr[1] = $_taxcur;
    $_arr[2] = $_reslist;
    $_arr[3] = $_curlist;

    return $_arr;
}

function oercurr_cfb_initiate_admin_bx_slider() {
    global $pagenow;
    if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
        ?>
        <script>

        var curriculumfeatsliders = new Array();
      var curriculumfeatbxconfig;
        let newblockadded = true;

        jQuery(document).ready(function(){

          jQuery(document).on('click','.oercurr_cfb_inspector_feat_addResources',function(e){
            jQuery('.oercurr_cfb_inspector_feat_modal_resource_wrapper').show(300);
          });

          jQuery(document).on('click','.oercurr_cfb_inspector_feat_addCurriculum',function(e){
            jQuery('.oercurr_cfb_inspector_feat_modal_curriculum_wrapper').show(300);
          });

          jQuery(document).on('click','.oercurr_cfb_inspector_feat_modal_wrapper_close span.dashicons',function(e){
            jQuery('.oercurr_cfb_inspector_feat_modal_resource_wrapper').hide(300);
            jQuery('.oercurr_cfb_inspector_feat_modal_curriculum_wrapper').hide(300);
          })

          jQuery(document).on('click','.oercurr_cfb_inspector_feat_hlite_node span.dashicons',function(e){
                let itemid = jQuery(this).parent().attr('data');
                let itemtype = jQuery(this).parent('.oercurr_cfb_inspector_feat_hlite_node').attr('typ');
                jQuery(this).parent('.oercurr_cfb_inspector_feat_hlite_node').removeClass('stay');
                jQuery('.oercurr_cfb_inspector_feat_hlite_remove_trigger').trigger('click');
                jQuery('input[data="'+itemid+'"]').prop('checked',false);
                var blkid = jQuery('.oercurr_cfb_inspector_feat_hlite_remove_trigger').attr('blkid');
                curriculumfeatslider_reset(blkid, 750);
          })

        });


        function curriculumfeatslider_loadall(featblockcount, cwid){
    		
    			var checkExist = setInterval(function() {
    				var numitems = jQuery('ul.featuredwpr_bxslider').length;
    				
    			 	if (numitems == featblockcount) {
    						clearInterval(checkExist);	
    						setTimeout(function(){		
    							
    							jQuery('.featuredwpr_bxslider').each(function(i, slider) {
    								
    								blkid = jQuery(slider).attr('blk');
    								let blkattr = jQuery('.curriculum-feat-attr_'+blkid).text();
    								blkattr = JSON.parse(decodeURI(blkattr));
    								
    								if(jQuery(slider).parent('.bx-viewport').length == 0){

    									var slidewidth = (cwid -  ( parseInt(blkattr['slidemargin']) * (parseInt(blkattr['maxslides']) - 1) ) ) / parseInt(blkattr['maxslides']);
    									oercurr_cfb_cgb_Global['featuredwpr_bxslider_'+blkid] = jQuery(slider).bxSlider({
    											minSlides: parseInt(blkattr['minslides']),
    											maxSlides: parseInt(blkattr['maxslides']),
    											moveSlides: parseInt(blkattr['moveslides']),
    											slideWidth: parseInt(blkattr['slidewidth']),
    											slideMargin: parseInt(blkattr['slidemargin']),
    											pager: false,
    											onSliderLoad: function(currentIndex) {
    													localStorage.setItem("curriculumFeatCurrentSlideIndex-"+blkid, 0);
    													jQuery('.featuredwpr_bxslider').css({'visibility':'visible','height':'auto'});
    													
    													let imgwidth = parseInt(blkattr['slideimageheight']);
    													jQuery('.featuredwpr_bxslider_'+blkid+' li div.frtdsnglwpr a div.img img').css({'height':'100%', 'max-height': imgwidth+'px', 'max-width':'100%' });
    													
    													
    											},
    											onSlideAfter: function($slideElm, oldIndex, newIndex) {
    												var blkid = jQuery(slider).attr('blk');
    												lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, blkid)
    											}
    									});					
    									jQuery(slider).attr('idx',i);
    									
    								}
    								
    							});
    							
    						}, 750); //set timeout
    				 }	
    			}, 100); //set interval
    		}
    		

    		function curriculumfeatslider_reset(blkid,speed, target, cwid){

    			let blkattr = jQuery('.curriculum-feat-attr_'+blkid).text();
    			blkattr = JSON.parse(decodeURI(blkattr));
    			
    			if(typeof target !== 'undefined'){
    				jQuery(target).siblings('img').addClass('show');
    				jQuery(target).addClass('hide');
    			}
    			

    			jQuery('.ls_inspector_feat_modal_checkbox').attr("disabled", true);
    			oercurr_cfb_cgb_Global['bxresetblocked'] = true;
    			var startIndex = localStorage.getItem("curriculumFeatCurrentSlideIndex-"+blkid);
    	    if(startIndex == null)
    	        startIndex = 0;
    			
    			setTimeout(function(){
    						let elmblkid = jQuery('.featuredwpr_bxslider_'+blkid).attr('blk');
    						let bxidx = jQuery('.featuredwpr_bxslider_'+blkid).attr('idx');
    						
    						jQuery('.featuredwpr_bxslider_'+blkid).parents('.bx-viewport').siblings('.oercurr-cfb-ftrdttl').remove();
    						if (oercurr_cfb_cgb_Global['featuredwpr_bxslider_'+blkid]){
    							oercurr_cfb_cgb_Global['featuredwpr_bxslider_'+blkid].reloadSlider({
    								startSlide: startIndex,
    								minSlides: parseInt(blkattr['minslides']),
    								maxSlides: parseInt(blkattr['maxslides']),
    								moveSlides: parseInt(blkattr['moveslides']),
    								slideWidth: parseInt(blkattr['slidewidth']),
    								slideMargin: parseInt(blkattr['slidemargin']),
    								slidealign: blkattr['slidealign'],
    								pager: false,
    								onSliderLoad: function(currentIndex) {
    										jQuery('.featuredwpr_bxslider_'+blkid).css({'visibility':'visible','height':'auto'});	
    										
    										jQuery('.ls_inspector_feat_modal_checkbox').attr("disabled", false);
    										
    										if(typeof target !== 'undefined'){
    											jQuery(target).siblings('img').removeClass('show');
    											jQuery(target).removeClass('hide');
    										}
    										oercurr_cfb_cgb_Global['bxresetblocked'] = false;
    										
    										let imgwidth = parseInt(blkattr['slideimageheight']);
    										jQuery('.featuredwpr_bxslider_'+blkid+' li div.frtdsnglwpr a div.img img').css({'height':'100%', 'max-height': imgwidth+'px', 'max-width':'100%' });
    										
    										let xcnt = jQuery('.featuredwpr_bxslider_'+ blkid +' li').length;
    										let iwid = jQuery('.featuredwpr_bxslider_'+ blkid +' li').width();
    										let sldwidth = (iwid + parseInt(blkattr['slidemargin'])) * xcnt;
    										jQuery('.featuredwpr_bxslider_'+blkid).css({'width':sldwidth+'px'});
    								},
    								onSlideAfter: function($slideElm, oldIndex, newIndex) {
    									lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, elmblkid)
    								}
    							});
    						}
    							
    			}, speed);
    		}

        function lpInspectorFeatSliderIndexSave($slideElm, oldIndex, newIndex, elmblkid) {
            localStorage.setItem("curriculumFeatCurrentSlideIndex-"+elmblkid, newIndex);
        }

        function sort(){
            jQuery(".oercurr_cfb_inspector_feat_hlite_list div").sortable({
                placeholder: "oercurr_cfb_inspector_feat_hlite_node-state-highlight",
                connectWith: ".oercurr_cfb_inspector_feat_hlite_featured",
                cancel: ".oercurr_cfb_inspector_feat_hlite_node .dashicons-dismiss",
                update: function(event, ui) {
                    jQuery('.oercurr_cfb_inspector_feat_hlite_reposition_trigger').trigger('click');
                    var blkid = jQuery('.oercurr_cfb_inspector_feat_hlite_reposition_trigger').attr('blkid');
                    curriculumfeatslider_reset(blkid, 750);
                }
            });
        }

        </script>
        <?php
    }
}
add_action( 'admin_footer', 'oercurr_cfb_initiate_admin_bx_slider' );


function initiate_frontend_bx_slider(){
	?>
	<script>
	jQuery(window).on('load', function() {
	  
		let oercurr_sldr = [];
		var oercurr_responsive = [];
	  jQuery('.oercurr_cfb_right_featuredwpr').each(function(i, obj) {
	    let cfb_blkid = jQuery(this).find('ul.featuredwpr_bxslider').attr('blk');
			let blkattr = jQuery('.curriculum-feat-attr_'+cfb_blkid).text();
			blkattr = JSON.parse(decodeURI(blkattr));
			
	    let oercurr_cfb_minslides = blkattr['minslides'],
	    oercurr_cfb_maxslides = blkattr['maxslides'],
	    oercurr_cfb_moveslides = blkattr['moveslides'],
	    oercurr_cfb_slidewidth = blkattr['slidewidth'],
	    oercurr_cfb_slidemargin = blkattr['slidemargin'],
	    oercurr_cfb_slidealign = blkattr['slidealign'],
	    oercurr_cfb_slidedesclength = blkattr['slidedesclength'],
	    oercurr_cfb_slideimageheight = blkattr['slideimageheight']
      
	    oercurr_sldr[cfb_blkid] = jQuery('.featuredwpr_bxslider_'+cfb_blkid).bxSlider({
	      minSlides: parseInt(oercurr_cfb_minslides),
	      maxSlides: parseInt(oercurr_cfb_maxslides),
	      moveSlides: parseInt(oercurr_cfb_moveslides),
	      slideWidth: parseInt(oercurr_cfb_slidewidth),
	      slideMargin: parseInt(oercurr_cfb_slidemargin),
	      pager: false,    
	      onSliderLoad: function(currentIndex) {
	        jQuery(".featuredwpr_bxslider_"+cfb_blkid).css({"visibility":"visible","height":"auto"});
					if(jQuery(".featuredwpr_bxslider_"+cfb_blkid+" li:not(.bx-clone)").length > 3){
						jQuery(".featuredwpr_bxslider_"+cfb_blkid).closest('.bx-wrapper').addClass('mobile');
					}
					
					let imgwidth = parseInt(oercurr_cfb_slideimageheight);
					jQuery('.featuredwpr_bxslider_'+cfb_blkid+' li div.frtdsnglwpr a div.img img').css({'height':'100%', 'max-height': imgwidth+'px', 'max-width':'100%' });
					
					let xcnt = jQuery('.featuredwpr_bxslider_'+ cfb_blkid +' li').length;
					let iwid = jQuery('.featuredwpr_bxslider_'+ cfb_blkid +' li').width();
					let sldwidth = (iwid + parseInt(oercurr_cfb_slidemargin)) * xcnt;
					jQuery('.featuredwpr_bxslider_'+cfb_blkid).css({'width':(sldwidth+1000)+'px'});
					
	      }
	    });
      
      jQuery(window).resize(function() {
			    clearTimeout(oercurr_responsive[cfb_blkid]);
			    oercurr_responsive[cfb_blkid] = setTimeout(function(){
            console.log('Resixe IT!');
              oercurr_sldr[cfb_blkid].reloadSlider({
                minSlides: parseInt(oercurr_cfb_minslides),
        	      maxSlides: parseInt(oercurr_cfb_maxslides),
        	      moveSlides: parseInt(oercurr_cfb_moveslides),
        	      slideWidth: parseInt(oercurr_cfb_slidewidth),
        	      slideMargin: parseInt(oercurr_cfb_slidemargin),
        	      pager: false,    
        	      onSliderLoad: function(currentIndex) {
        	        jQuery(".featuredwpr_bxslider_"+cfb_blkid).css({"visibility":"visible","height":"auto"});
        					if(jQuery(".featuredwpr_bxslider_"+cfb_blkid+" li:not(.bx-clone)").length > 3){
        						jQuery(".featuredwpr_bxslider_"+cfb_blkid).closest('.bx-wrapper').addClass('mobile');
        					}
        					
        					let imgwidth = parseInt(oercurr_cfb_slideimageheight);
        					jQuery('.featuredwpr_bxslider_'+cfb_blkid+' li div.frtdsnglwpr a div.img img').css({'height':'100%', 'max-height': imgwidth+'px', 'max-width':'100%' });
        					
        					let xcnt = jQuery('.featuredwpr_bxslider_'+ cfb_blkid +' li').length;
        					let iwid = jQuery('.featuredwpr_bxslider_'+ cfb_blkid +' li').width();
        					let sldwidth = (iwid + parseInt(oercurr_cfb_slidemargin)) * xcnt;
        					jQuery('.featuredwpr_bxslider_'+cfb_blkid).css({'width':(sldwidth+1000)+'px'});
        					
        	      }
              });
          }, 200);
      });
      
			
	  });

		
	})
	</script>
	<?php
}
add_action( 'wp_footer', 'initiate_frontend_bx_slider' );


function oercurr_cfb_fontawesome_dashboard() {
    wp_enqueue_style('fontawesome-style', OERCURR_CURRICULUM_URL.'lib/fontawesome/css/all.min.css');
}

add_action('admin_init', 'oercurr_cfb_fontawesome_dashboard');