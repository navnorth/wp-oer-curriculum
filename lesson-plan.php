<?php
/*
 Plugin Name:  CURRICULUM PLUGIN
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
define( 'OER_LESSON_PLAN_PLUGIN_NAME', 'WP OER Lesson Plan Plugin' );
define( 'OER_LESSON_PLAN_ADMIN_PLUGIN_NAME', 'WP OER Lesson Plan Plugin');
define( 'OER_LESSON_PLAN_VERSION', '0.0.1' );

include_once(OER_LESSON_PLAN_PATH.'includes/oer-lp-functions.php');
include_once(OER_LESSON_PLAN_PATH.'includes/init.php');

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
   ?>
    <div class="notice notice-success is-dismissible">
        <p>Thank you for installing <strong>WP OER Lesson Plan</strong> plugin.</p>
    </div>
    <?php
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