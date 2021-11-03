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
    wp_localize_script( 'curriculum_block-front-js', 'curriculum_block_ajax_object', array( 
       'ajaxurl' => admin_url( 'admin-ajax.php' ),
       'Posts Per Page' => __('Posts Per Page',OERCURR_CURRICULUM_SLUG),
   		 'Sort By' => __('Sort By',OERCURR_CURRICULUM_SLUG),
   		 'Date Added' => __('Date Added',OERCURR_CURRICULUM_SLUG),
   		 'Date Updated' => __('Date Updated',OERCURR_CURRICULUM_SLUG),
   		 'Title a-z' => __('Title a-z',OERCURR_CURRICULUM_SLUG),
   		 'Browse All' => __('Browse All',OERCURR_CURRICULUM_SLUG),
   		 'Curriculums' => __('Curriculums',OERCURR_CURRICULUM_SLUG),
   		 'Show' => __('Show',OERCURR_CURRICULUM_SLUG),
    ) );
}
add_action( 'wp_enqueue_scripts', 'oercur_cb_enqueue_script_function' );

function oercurr_cb_block_assets() { // phpcs:ignore
    
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
    
    wp_localize_script('oercurr_cb_block-cgb-js','oercurr_clb_Script',
      [
        "txtclbCurriculumBlockSettings" => esc_html__("Curriculum Block Settings", OERCURR_CURRICULUM_SLUG),
        "txtclbSubjects" => esc_html__("Subjects", OERCURR_CURRICULUM_SLUG),
      ]
    );

    // Register block editor styles for backend.
    if(get_current_post_type() == 'oer-curriculum' || get_current_post_type() == 'page' || get_current_post_type() == 'post') {
      wp_register_style(
          'curriculum_block-cgb-block-editor-css', // Handle.
          plugins_url( '/curriculum-block/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
          array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
          null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
      );
    }
    
    wp_localize_script(
  		'oercurr_cb_block-cgb-js',
  		'oercurr_clb_translations', // Array containing dynamic data for a JS Global.
  		[
  			'Curriculum Block settings' => __('Curriculum Block Settings',OERCURR_CURRICULUM_SLUG),
  			'Subjects' => __('Subjects',OERCURR_CURRICULUM_SLUG),
  			'Add Subjects' => __('Add Subjects',OERCURR_CURRICULUM_SLUG),
  			'Posts Per Page' => __('Posts Per Page',OERCURR_CURRICULUM_SLUG),
  			'Sort By' => __('Sort By',OERCURR_CURRICULUM_SLUG),
  			'Date Added' => __('Date Added',OERCURR_CURRICULUM_SLUG),
  			'Date Updated' => __('Date Updated',OERCURR_CURRICULUM_SLUG),
  			'Title a-z' => __('Title a-z',OERCURR_CURRICULUM_SLUG),
  			'Browse All' => __('Browse All',OERCURR_CURRICULUM_SLUG),
  			'Curriculums' => __('Curriculums',OERCURR_CURRICULUM_SLUG),
  			'Show' => __('Show',OERCURR_CURRICULUM_SLUG),
  		]
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
        'oer-curriculum/block-curriculum-block', array(
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
            //'render_callback' => 'oercurr_cb_render_posts_block'
        )
    );
}

function get_current_post_type() {
  $pstyp = '';
  if( (isset($_GET['action']) && $_GET['action'] == 'edit')  &&  isset($_GET['post']) ){ //Edit age
    $pstyp = get_post_type($_GET['post']);
  }elseif( isset($_GET['post_type']) ){
    $pstyp = $_GET['post_type'];
  }
  return $pstyp;
}

function oercurr_cb_render_posts_block($attributes){
    
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
    $_content = get_curriculum_block_content($posts,$attributes);
    
    $_arr['cnt'] = esc_html($_count);
    $_arr['data'] = wp_kses_post($_content);
    $_ret = json_encode($_arr);

    return $_ret;
    
}

// Hook: Block assets.
add_action( 'init', 'oercurr_cb_block_assets' );


function get_curriculum_block_content($posts, $attributes){
  $bid = $attributes['blockid'];
  $ord = ($attributes['sortBy'] == 'title')? 'ASC': 'DESC'; 
  ob_start();
  foreach($posts as $post){
      ?>
      <div class="oercurr-blk-row">
          <?php $featured_img_url = get_the_post_thumbnail_url($post->ID,'medium'); ?>
          <a href="<?php echo esc_url(get_post_permalink($post->ID)) ?>" class="oercurr-blk-left"><img src="<?php echo esc_url($featured_img_url) ?>" alt="" /></a><div class="oercurr-blk-right">
              <div class="ttl"><a href="<?php echo esc_url(get_post_permalink($post->ID)) ?>"><?php echo esc_html($post->post_title) ?></a></div>
              <div class="oercurr-postmeta">
                  <?php
                  if(is_array($post->oer_curriculum_grades)){
                    if(count($post->oer_curriculum_grades)>1){ ?>
                        <span class="oercurr-postmeta-grades"><strong>Grades:</strong> <?php echo esc_html($post->oer_curriculum_grades[0]) ?> - <?php echo esc_html($post->oer_curriculum_grades[count($post->oer_curriculum_grades)-1]) ?></span>
                    <?php }else{
                        if($post->oer_curriculum_grades[0] != ''){  ?>
                                <span class="oercurr-postmeta-grades"><strong>Grade:</strong> <?php echo esc_html($post->oer_curriculum_grades[0]) ?></span>
                        <?php }
                    }
                  }
                  ?>
              </div>
              <?php                  
              if(trim($post->post_content," ") != ''){
                ?>
                  <div class="desc"><?php echo substr(esc_html(wp_strip_all_tags($post->post_content)),0,180) ?> ...</div>
                <?php
              }            
              $_arr_tag = get_the_tags($post->ID);
              ?>
              <div class="oercurr-tags tagcloud">
              <?php
              if(!empty($_arr_tag)){
                  foreach($_arr_tag as $key => $tag) {
                      ?>
                      <span><a href="<?php echo esc_url(get_home_url()) ?>/tag/<?php echo esc_html($tag->slug) ?>" alt="" class="button"><?php echo esc_html($tag->name) ?></a></span>
                      <?php
                  }
              }
              ?>
              </div>                   
          </div>
      </div>
      <?php
  }
  
  return ob_get_clean();
  
}


function oercurr_cb_rebuild_post_block(){
    $_arr = array();
    $_arr['selectedCategory'] = sanitize_text_field($_POST['sel']);
    $_arr['postsPerPage']     = sanitize_text_field($_POST['per']);
    $_arr['sortBy']           = sanitize_text_field($_POST['srt']);   
    echo oercurr_cb_render_posts_block($_arr);
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