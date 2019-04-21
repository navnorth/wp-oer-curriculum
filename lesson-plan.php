<?php
/*
 Plugin Name:  Curriculum Plugin
 Plugin URI:   https://www.wp-oer.com
 Description:  Open Educational Resource management and curation, metadata publishing, and alignment to Common Core State Standards.
 Version:      0.0.1
 Author:       Navigation North
 Author URI:   https://www.navigationnorth.com
 Text Domain:  wp-oer
 License:      GPL3
 License URI:  https://www.gnu.org/licenses/gpl-3.0.html

 Copyright (C) 2017 Navigation North

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
define( 'OER_LESSON_PLAN_PLUGIN_NAME', 'WP OER Curriculum Plugin' );
define( 'OER_LESSON_PLAN_ADMIN_PLUGIN_NAME', 'WP OER Curriculum Plugin');
define( 'OER_LESSON_PLAN_VERSION', '0.0.1' );

include_once(OER_LESSON_PLAN_PATH.'includes/oer-lp-functions.php');
include_once(OER_LESSON_PLAN_PATH.'includes/init.php');

global $oer_lp_default_structure;
$oer_lp_default_structure = array(
    'lp_authors_order',
    'lp_standard_order',
    'lp_iq',
    'lp_primary_resources',
    'oer_lp_custom_editor_teacher_background',
    'oer_lp_custom_editor_student_background',
    'lp_oer_materials'
);

/**
 * Parent plugin (WP OER) required to activate WP OER Lesson Plan Plugin
 * Check if WP OER plugin already installed or not
 * If WP OER not installed then show the error message
 * And stop the installation process of WP OER Lesson Plan Plugin
 */
register_activation_hook( __FILE__, 'check_parent_plugin' );
function check_parent_plugin()
{
    // Require parent plugin
    if(
        ! is_plugin_active( 'wp-oer-teaching_california/open-educational-resources.php' ) and
        current_user_can( 'activate_plugins' )
    )
    {
        wp_die('Sorry, but this plugin requires the Parent Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
    }
    
    // Require WP Curriculum plugin
    if ( !is_plugin_active( 'wp-academic-standards/wp-academic-standards.php')
        && current_user_can( 'activate_plugins' )){
        wp_die('Sorry, but this plugin requires the WP Academic Standards Plugin to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
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
            <p>Thank you for installing <strong>WP OER Lesson Plan</strong> plugin.</p>
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
	add_rewrite_rule( '^'.$root_slug.'/([^/]*)/source/([^&]+)/?$', 'index.php?post_type=lesson-plans&curriculum=$matches[1]&source=$matches[2]', 'top' );
        add_rewrite_endpoint( 'curriculum', EP_PERMALINK | EP_PAGES );
	add_rewrite_endpoint( 'source', EP_PERMALINK | EP_PAGES );

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
        }
        return $template;
}