<?php
/*
 Plugin Name:  Curriculum Plugin
 Plugin URI:   https://www.wp-oer.com
 Description:  Educational curriculum organizing and publishing, integrating with OER and Academic Standards
 Version:      0.3.2
 Author:       Navigation North
 Author URI:   https://www.navigationnorth.com
 Text Domain:  wp-curriculum
 License:      GPL3
 License URI:  https://www.gnu.org/licenses/gpl-3.0.html

 Copyright (C) 2019 Navigation North

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//defining the url,path and slug for the plugin
define( 'OER_LESSON_PLAN_URL', plugin_dir_url(__FILE__) );
define( 'OER_LESSON_PLAN_PATH', plugin_dir_path(__FILE__) );
define( 'OER_LESSON_PLAN_SLUG','lesson-plan' );
define( 'OER_LESSON_PLAN_FILE',__FILE__);
// Plugin Name and Version
define( 'OER_LESSON_PLAN_PLUGIN_NAME', 'Curriculum Plugin' );
define( 'OER_LESSON_PLAN_ADMIN_PLUGIN_NAME', 'Curriculum Plugin');
define( 'OER_LESSON_PLAN_VERSION', '0.3.2' );

include_once(OER_LESSON_PLAN_PATH.'includes/oer-lp-functions.php');
include_once(OER_LESSON_PLAN_PATH.'includes/init.php');

global $oer_lp_default_structure;
$oer_lp_default_structure = array(
    'lp_authors_order',
    'lp_standard_order',
    'lp_iq',
    'lp_primary_resources',
    // 'oer_lp_custom_editor_teacher_background',
    // 'oer_lp_custom_editor_student_background',
    'oer_lp_custom_editor_historical_background',
    //'lp_oer_materials'
);

/**
 * Parent plugin (WP OER) required to activate Curriculum Plugin
 * Check if WP OER plugin already installed or not
 * If WP OER not installed then show the error message
 * And stop the installation process of Curriculum Plugin
 */
register_activation_hook( __FILE__, 'check_parent_plugin' );
function check_parent_plugin()
{
    // Require WP-OER plugin
    if(! nn_check_dependencies() and current_user_can( 'activate_plugins' ) )
    {
        wp_die('Sorry, but this plugin requires the <a href="https://wordpress.org/plugins/wp-oer/" target="_blank">WP OER</a> and <a href="https://github.com/navnorth/wp-academic-standards" target="_blank">Academic Standards</a> plugins to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }


}

/**
 * Runs only when the plugin is activated.
 * @since 0.1.0
 */
function oer_lp_plugin_activate()
{
    //Activation code
}
register_activation_hook( __FILE__, 'oer_lp_plugin_activate' );

/**
 * Admin Notice on Activation.
 * @since 0.1.0
 */
add_action( 'admin_notices', 'my_plugin_activation_notice');
function my_plugin_activation_notice()
{
    global $post;
    if(
        (isset($post->post_type) && $post->post_type=='lesson-plans') &&
        !get_option('lp_setup_notification')
    )
    {?>
        <div class="notice notice-success is-dismissible" id="oep-lp-dismissible">
            <p>Thank you for installing <strong>Curriculum</strong> plugin.</p>
        </div>
    <?php }

}

/**
 * Filter for adding Template for Plugin.
 * @since 0.1.0
 * @param $single_template
 * @return string
 */
function get_single_lesson_plans_template($single_template)
{
    global $post;

    if ($post->post_type == 'lesson-plans') {
        $single_template = dirname( __FILE__ ) . '/templates/single-lesson-plans.php';
    }
    return $single_template;
}
add_filter( 'single_template', 'get_single_lesson_plans_template' );

// Add rewrite rule for substandards
add_action( 'init', 'lp_add_rewrites', 10, 0 );
function lp_add_rewrites()
{
        $root_slug = "inquiry-sets";
    global $wp_rewrite;
        add_rewrite_tag( '%curriculum%', '([^/]*)' );
    add_rewrite_tag( '%source%', '([^&]+)' );
        add_rewrite_tag( '%topic%', '([^&]+)' );
    add_rewrite_rule( '^'.$root_slug.'/([^/]*)/source/([^&]+)/?$', 'index.php?post_type=lesson-plans&curriculum=$matches[1]&source=$matches[2]', 'top' );
        add_rewrite_rule( '^'.$root_slug.'/topic/([^&]+)/?$', 'index.php?post_type=lesson-plans&topic=$matches[1]', 'top' );
        add_rewrite_endpoint( 'curriculum', EP_PERMALINK | EP_PAGES );
    add_rewrite_endpoint( 'source', EP_PERMALINK | EP_PAGES );
        add_rewrite_endpoint( 'topic', EP_PERMALINK | EP_PAGES );

    $flush_rewrite = get_option('lp_rewrite_rules');
        if (empty($flush_rewrite)){
            add_option('lp_rewrite_rules', false);
        }

        $wp_rewrite->init();
        $wp_rewrite->flush_rules();
        update_option('lp_rewrite_rules', true);
}

add_filter( 'query_vars', 'lp_add_query_vars' );
function lp_add_query_vars( $vars ){
    $vars[] = "source";
        $vars[] = "topic";
    return $vars;
}

add_action( 'template_include' , 'lp_assign_standard_template' );
function lp_assign_standard_template($template) {
    global $wp_query;

        $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');

        status_header(200);

        $root_slug = "inquiry-sets";

        if ( strpos( $url_path, $root_slug ) !== false && get_query_var('curriculum') && get_query_var('source')) {
            $wp_query->is_404 = false;
            $template = locate_template('templates/primary-source.php', true);
            if (!$template) {
                    $template = dirname(__FILE__) . '/templates/primary-source.php';
            }
        } elseif ( strpos( $url_path, $root_slug ) !== false && get_query_var('topic')) {
            $wp_query->is_404 = false;
            $template = locate_template('templates/lesson-plan-tag.php', true);
            if (!$template) {
                    $template = dirname(__FILE__) . '/templates/lesson-plan-tag.php';
            }
        }
        return $template;
}

add_action( 'init', 'lp_add_inquiry_set_rest_args', 30 );
function lp_add_inquiry_set_rest_args() {
    global $wp_post_types;

    $wp_post_types['lesson-plans']->show_in_rest = true;
    $wp_post_types['lesson-plans']->rest_base = 'inquiryset';
    $wp_post_types['lesson-plans']->rest_controller_class = 'WP_REST_Posts_Controller';
}

/* Enqueue script and css for Gutenberg Inquiry Set Thumbnail block */
add_action('enqueue_block_editor_assets', 'lp_enqueue_inquiry_set_block');
function lp_enqueue_inquiry_set_block(){
    wp_enqueue_script(
            'inquiry-set-thumbnail-block-js',
            OER_LESSON_PLAN_URL . "/assets/js/backend/inquiry-set-thumbnail-block.build.js",
            array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-api')
    );
        wp_localize_script(
            'inquiry-set-thumbnail-block-js',
            'wp_nn_theme',
            array(
                "theme_path" => get_template_directory_uri()
            )
        );
    wp_enqueue_style(
            'inquiry-set-thumbnail-block-css',
            OER_LESSON_PLAN_URL . "/assets/css/backend/inquiry-set-thumbnail-block.css",
            array('wp-edit-blocks')
    );
    /* Register Thumbnail Block */
    register_block_type('wp-curriculum/inquiry-set-thumbnail-block', array(
            'editor_script' => 'inquiry-set-thumbnail-block-js',
            'editor_style' => 'inquiry-set-thumbnail-block-css'
    ));
}

add_action( 'rest_api_init', 'lp_add_meta_to_api');
function lp_add_meta_to_api() {
    // Register Grade Levels to REST API
    register_rest_field( 'lesson-plans',
                'oer_lp_grades',
                array(
                'get_callback' => 'lp_rest_get_meta_field',
                'update_callback' => null,
                'schema' => null
                  ) );

    // Register Featured Image to REST API
    register_rest_field( 'lesson-plans',
            'featured_image_url',
            array(
                'get_callback'    => 'lp_get_rest_featured_image',
                'update_callback' => null,
                'schema'          => null,
            ) );

}

function lp_rest_get_meta_field($inquiryset, $field, $request){
    if ($field=="oer_lp_grades") {
        $grades = get_post_meta($inquiryset['id'], $field, true);
                if (isset($grades[0]))
                    $grades = $grades[0];
        $grade_level = "";

                if ($grades == "pre-k")
                    $grade_level = "Pre-Kindergarten";
                elseif ($grades == "k")
                    $grade_level = "Kindergarten";
                else
                    $grade_level = "Grade ".$grades;

                return $grade_level;
    } else
        return get_post_meta($inquiryset['id'], $field, true);
}

function lp_get_rest_featured_image($inquiryset, $field, $request) {
    if( $inquiryset['featured_media'] ){
        return lp_get_resource_thumbnail_url($inquiryset['featured_media'],'resource-thumbnail');
    }
    return false;
}

// Custom function to get intermediate size thumbnail url
function lp_get_resource_thumbnail_url($attachment_id, $size){
    global $_wp_additional_image_sizes;

    $l = null;
    $upload_folder = wp_upload_dir()['path'];
    $img_url = wp_get_attachment_image_url( $attachment_id, 'resource-thumbnail');
    $upload_dir = pathinfo($img_url, PATHINFO_DIRNAME);
    $ext = pathinfo($img_url, PATHINFO_EXTENSION);
    $filename = pathinfo($img_url, PATHINFO_FILENAME);
    $index = 0;

    if ($img_url){
        foreach($_wp_additional_image_sizes as $image_size=>$meta){
            if ($image_size==$size){
                if (strpos($img_url,'-'.$meta['width'].'x'.$meta['height'].".".$ext))
                    return $img_url;

                $img_name = $filename.'-'.$meta['width'].'x'.$meta['height'].".".$ext;
                $file_path = $upload_folder . '/resource-images/' . $img_name;
                $l[$index]['orig_url'] = $img_url;
                $l[$index]['upload_dir'] = wp_upload_dir();
                $l[$index]['img_url'] = $upload_dir.'/'.$img_name;
                $l[$index]['img_path'] = $file_path;
                $l[$index]['ext'] = $ext;
                $l[$index]['filename'] = $filename;
                $l[$index]['size'] = $image_size;
                $l[$index]['height'] = $meta['height'];
                $l[$index]['width'] = $meta['width'];

                return $l[$index]['img_url'];
            }
            $index++;
        }
    } else {
        $img_url = "";
    }

    return $img_url;
}
