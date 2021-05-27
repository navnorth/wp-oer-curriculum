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
 

function oercur_cb_enqueue_script_function(){
    wp_enqueue_script( 'curriculum_block-front-js', plugins_url( '/curriculum-block/front.build.js', dirname( __FILE__ ) ), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),'1.0.1' , true );
    wp_localize_script( 'curriculum_block-front-js', 'curriculum_block_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'oercur_cb_enqueue_script_function' );

function oercurr_cb_block_assets() { // phpcs:ignore
    
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
        plugins_url( '/curriculum-block/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
        is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
        null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
    );

    // Register block editor script for backend.
    wp_register_script(
        'oercurr_cb_block-cgb-js', // Handle.
        plugins_url( '/curriculum-block/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
        null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
        true // Enqueue the script in the footer.
    );

    // Register block editor styles for backend.
    wp_register_style(
        'curriculum_block-cgb-block-editor-css', // Handle.
        plugins_url( '/curriculum-block/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
        array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
        null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
    );
    
    
    
    // WP Localized globals. Use dynamic PHP stuff in JavaScript via `oercurr_cb_cgb_Global` object.
    wp_localize_script(
        'oercurr_cb_block-cgb-js',
        'oercurr_cb_cgb_Global', // Array containing dynamic data for a JS Global.
        [
            'pluginDirPath' => plugin_dir_path( __DIR__ ),
            'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
            'base_url' => get_home_url(),
            'preview_url' => OERCURR_CURRICULUM_URL.'includes/blocks/curriculum-block/blockpreview.jpg',
            // Add more data here that you want to access from `oercurr_cb_cgb_Global` object.
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
            'editor_script' => 'oercurr_cb_block-cgb-js',
            // Enqueue blocks.editor.build.css in the editor only.
            'editor_style'  => 'curriculum_block-cgb-block-editor-css',
            'attributes'      => array(
        			'custom'      => array(
        				'type'    => 'string',
        				'default' => '',
        			),
        			'width'       => array(
        				'type'    => 'string',
        				'default' => '',
        			),
        			'preview'     => array(
        				'type'    => 'boolean',
        				'default' => false,
        			),
        		),
            'render_callback' => 'oercurr_cb_render_posts_block'
        )
    );
}

function oercurr_cb_render_posts_block($attributes, $ajx=false){
    
        
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
                            'post_type' => 'oer-curriculum',
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
        $_content .= '<div class="oercurr-blk-row">';
            $featured_img_url = get_the_post_thumbnail_url($post->ID,'medium'); 
            $_content .= '<a href="'.esc_url(get_post_permalink($post->ID)).'" class="oercurr-blk-left"><img src="'.esc_url($featured_img_url).'" alt="" /></a>';
            $_content .= '<div class="oercurr-blk-right">';
                $_content .= '<div class="ttl"><a href="'.esc_url(get_post_permalink($post->ID)).'">'.$post->post_title.'</a></div>';
                $_content .= '<div class="oercurr-postmeta">';
                    if(count($post->oer_curriculum_grades)>1){
                        $_content .= '<span class="oercurr-postmeta-grades"><strong>Grades:</strong> '. $post->oer_curriculum_grades[0].'-'.$post->oer_curriculum_grades[count($post->oer_curriculum_grades)-1].'</span>';
                    }else{
                        if($post->oer_curriculum_grades[0] != ''){
                                $_content .= '<span class="oercurr-postmeta-grades"><strong>Grade:</strong> '. $post->oer_curriculum_grades[0].'</span>';
                        }
                    }
                $_content .= '</div>';                    
                if(trim($post->post_content," ") != ''){
                    $_content .= '<div class="desc">'.substr(wp_strip_all_tags($post->post_content),0,180).' ...</div>';
                }            
                $_arr_tag = get_the_tags($post->ID);
                $_content .= '<div class="oercurr-tags tagcloud">';
                if(!empty($_arr_tag)){
                    foreach($_arr_tag as $key => $tag) {
                        $_content .= '<span><a href="'.esc_url(get_home_url().'/tag/'.$tag->slug).'" alt="" class="button">'.$tag->name.'</a></span>';
                    }
                }
                $_content .= '</div">';                    
            $_content .= '</div>';
        $_content .= '</div>';
        $_content .= '</div>';
    }

    


    $_wrapper .= '<div class="oercurr-blk-main" blockid="'.$bid.'">';
        $_wrapper .= '<script>';
            //$_wrapper .= 'jQuery( document ).ready(function() {';
                $_wrapper .= 'localStorage.setItem("selectedCategory-'.$bid.'", "'.$attributes['selectedCategory'].'");';
                $_wrapper .= 'localStorage.setItem("postsPerPage-'.$bid.'", "'.$attributes['postsPerPage'].'");';
                $_wrapper .= 'localStorage.setItem("sortBy-'.$bid.'", "'.$attributes['sortBy'].'");';
            //$_wrapper .= '});';
        $_wrapper .= '</script>';
        $_wrapper .= '<div class="oercurr-blk-topbar">';    
            $_wrapper .= '<div class="oercurr-blk-topbar-left">';
                $_wrapper .= '<span>Browse All '.$_count.' Curriculums</span>';
            $_wrapper .= '</div>';
            $_wrapper .= '<div class="oercurr-blk-topbar-right">';    
                    $_wrapper .= '<div class="oercurr-blk-topbar-display-box">';
                        $_wrapper .= '<div class="oercurr-blk-topbar-display-text"><span>Show '.$attributes['postsPerPage'].'</span><a href="#"><i class="fa fa-th-list" aria-hidden="true"></i></a></div>';
                        $_wrapper .= '<ul class="oercurr-blk-topbar-display-option oercurr-blk-topbar-option" style="display:none;">';    
                                    for ($i=5; $i <=30; $i+=5){ 
                                         if($i == $attributes['postsPerPage']){
                                             $_wrapper .= '<li class="selected"><a href="#" ret="'.$i.'">'.$i.'</a></li>';
                                         }else{
                                             $_wrapper .= '<li><a href="#" ret="'.$i.'">'.$i.'</a></li>';
                                         }
                                    }
                        $_wrapper .= '</ul>';
                    $_wrapper .= '</div>';                    
                    $_wrapper .= '<div class="oercurr-blk-topbar-sort-box">';
                        $_wrapper .= '<div class="oercurr-blk-topbar-sort-text"><span>Sort by: '.$attributes['sortBy'].'</span><a href="#"><i class="fa fa-sort" aria-hidden="true"></i></a></div>';
                        $_wrapper .= '<ul class="oercurr-blk-topbar-sort-option oercurr-blk-topbar-option" style="display:none;">';
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
    
        $_wrapper .= '<div id="lp_cur_blk_content_wrapper"  class="oercurr-blk-wrapper">';
            $_wrapper .= '<div id="oercurr-blk-content_drop">';
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
add_action( 'init', 'oercurr_cb_block_assets' );



function oercurr_cb_rebuild_post_block(){
    $_arr = array();
    $_arr['selectedCategory'] = sanitize_text_field($_POST['sel']);
    $_arr['postsPerPage']     = sanitize_text_field($_POST['per']);
    $_arr['sortBy']           = sanitize_text_field($_POST['srt']);   
    echo oercurr_cb_render_posts_block($_arr, true);
    //echo json_encode($_arr);
    die();
}
add_action( 'wp_ajax_oercurr_cb_rebuild_post_block', 'oercurr_cb_rebuild_post_block' );
add_action('wp_ajax_nopriv_oercurr_cb_rebuild_post_block', 'oercurr_cb_rebuild_post_block');




// Register a REST route
add_action( 'rest_api_init', function () {
    //Path to meta query route
    register_rest_route( 'curriculum/v2', 'taxquery', array(
            'methods' => 'GET', 
            'callback' => 'oercurr_cb_tax_query',
                        'permission_callback' => '__return_true'
    ) );
        
        register_rest_route( 'curriculum/v2', 'catquery', array(
            'methods' => 'GET', 
            'callback' => 'oercurr_cb_cat_query',
                        'permission_callback' => '__return_true'
    ) );
        
        register_rest_route( 'curriculum/v2', 'tagsquery', array(
            'methods' => 'GET', 
            'callback' => 'oercurr_cb_tags_query',
                        'permission_callback' => '__return_true'
    ) );
});

function oercurr_cb_tax_query(){
    
    $_postperpage = sanitize_text_field($_GET['perpage']);
    $_taxterms = sanitize_text_field($_GET['terms']);
    $_ordertby = sanitize_text_field($_GET['orderby']);
    $_ord = sanitize_text_field($_GET['order']);

    $args = array(
        'posts_per_page' => $_postperpage,
        'post_type' => 'oer-curriculum',
        'tax_query' => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'resource-subject-area',
                'terms' => explode(',', $_taxterms),
                'field' => 'term_id',
                'include_children' => false,
            )
        ),
        'orderby' => $_ordertby,
      'order'   => $_ord,
    );
    
    $posts = get_posts( $args );

    
    $_ret = array(); $i=0;
    
        foreach($posts as $post){
            $_ret[$i]['title']              = $post->post_title;
            $_ret[$i]['content']            = substr(html_entity_decode(strip_tags($post->post_content)),0,180);
            $_ret[$i]['link']               = get_post_permalink($post->ID);
            $_ret[$i]['featured_image_url'] = get_the_post_thumbnail_url($post->ID,'medium');
            $_ret[$i]['oer_curriculum_grades']      = $post->oer_curriculum_grades;    
            $_ret[$i]['tags']               = wp_get_post_tags($post->ID, array('fields' => 'ids'));
            
            
            $_tmparr = array();
            $results = wp_get_post_tags($post->ID);
            if($results){
          foreach($results as $row){
                            array_push($_tmparr, $row->name.'|'.$row->slug);
          }
      }
            $_ret[$i]['tagsv2'] = $_tmparr;
            
            
            /*
            $results = wp_get_post_tags($post->ID);
            if($results){
                    $x = 0;
          foreach($results as $row){
                            $_ret[$i]['tags'][$x]['id'] = $row->term_id;
                            $_ret[$i]['tags'][$x]['name'] = $row->name;
                            $_ret[$i]['tags'][$x]['slug'] = $row->slug;
                            $x++;
          }
      }
            */
            
            $i++;
        }
    
    
    return $_ret;
}


function oercurr_cb_cat_query(){
    $_arr = array();
    $term_query = new WP_Term_Query( 
        array(
            'taxonomy' => 'resource-subject-area',
            'number' => 0,
            'parent' => 0,
            'hide_empty' => false
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

            
            
            $_arr[$cnt]['term_id'] = $term->term_id;
            $_arr[$cnt]['name'] = $term->name;
            $_arr[$cnt]['level'] = 'parent';
            $_arr[$cnt]['parent'] = $term->parent;
            $_arr[$cnt]['cnt'] = count(get_posts($args));
            //**************************************
            $cnt++;
            
            $childterm_query = new WP_Term_Query( array('taxonomy'=>'resource-subject-area','number'=>0,'parent'=>$term->term_id,'hide_empty'=>false) );    
            if ( ! empty( $childterm_query->terms ) ) {
                foreach ( $childterm_query->terms as $childterm ) {
                    
                    $args2 = array(
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
                        'fields'=> 'ids',  //get the ids only to cut down resources
                        'hide_empty' => false
                    );
                    
                    $_arr[$cnt]['term_id'] = $childterm->term_id;
                    $_arr[$cnt]['name'] = $childterm->name;
                    $_arr[$cnt]['level'] = 'child';
                    $_arr[$cnt]['parent'] = $childterm->parent;
                    $_arr[$cnt]['cnt'] = count(get_posts($args2));
                    $cnt++;
                }
            }
            //**************************************
            
    }
    } 
     
    return $_arr;
}


function oercurr_cb_tags_query(){
    $_arr = array();
    $tags_query = new WP_Term_Query( array('taxonomy' => 'post_tag','number' => 0, 'hide_empty' => false) );    
    if ( ! empty( $tags_query->terms ) ) {
        $cnt = 0;
        foreach ( $tags_query ->terms as $term ) {
            $_arr[$cnt]['id'] = $term->term_id;
            $_arr[$cnt]['name'] = $term->name;
            $_arr[$cnt]['link'] = get_home_url().'/tag/'.$term->slug;
            $cnt++;
        }
    }
    return $_arr;
}