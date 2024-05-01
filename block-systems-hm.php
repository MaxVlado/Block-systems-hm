<?php
/**
* Plugin Name: Manage blocks in WordPress.
* Plugin URI: http://housemagik.com/plugins/block-systems-hm
* Description: A plugin to manage blocks in WordPress.
* Version: 1.1
* Author: V. Kirillov HousemagiK
* Author URI: hhttp://housemagik.com/author
 *
* Copyright 2024  V. Kirillov  (email: netdesopgame@gmail.com)
 *
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
 *
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
 *
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('BLOCK_SYSTEMS_HM_VERSION', '1.0.0');

/**
 * Class Block_Systems_HM
 */
class Block_Systems_HM
{

    /**
     * Initialize the plugin.
     */
    public function __construct()
    {
        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies.
     */
    private function load_dependencies()
    {
        require_once plugin_dir_path(__FILE__) . 'includes/models/Block.php';
        require_once plugin_dir_path(__FILE__) . 'includes/controllers/BlocksController.php';

        require_once plugin_dir_path(__FILE__) . 'includes/models/Template.php';
        require_once plugin_dir_path(__FILE__) . 'includes/models/Anchor.php';

        require_once plugin_dir_path(__FILE__) . 'includes/models/BlockType.php';
        require_once plugin_dir_path(__FILE__) . 'includes/controllers/BlockTypesController.php';

        require_once plugin_dir_path(__FILE__) . 'includes/Admin.php';
    }

    /**
     * Register all of the hooks related to the admin area functionality.
     */
    private function define_admin_hooks() {
        $plugin_admin = new Block_Systems_HM_Admin();
        add_action('admin_menu', array($plugin_admin, 'add_plugin_admin_menu'));

        $blocks_controller = new Block_Systems_HM_Blocks_Controller();
        add_action('admin_init', array($blocks_controller, 'handle_actions'));

        $block_types_controller = new Block_Systems_HM_Block_Types_Controller();
        add_action('admin_init', array($block_types_controller, 'handle_actions'));
    }



    /**
     * Create the necessary database tables.
     */
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Create the `wp_blocks` table
        $blocks_table_name = $wpdb->prefix . 'blocks';
        $blocks_table_sql = "CREATE TABLE $blocks_table_name (
        id INT NOT NULL AUTO_INCREMENT,
        block_type_id INT NOT NULL,
        css TEXT DEFAULT NULL,
        description TEXT DEFAULT NULL,
        shortcode VARCHAR(255),
        current_fields TEXT,
        place VARCHAR(255),        
        selected_posts TEXT,
        selected_rubrics TEXT,
        selected_tags TEXT,
        excluded_posts TEXT,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY block_type_id (block_type_id)
    ) $charset_collate;";

        // Create the `wp_block_types` table
        $block_types_table_name = $wpdb->prefix . 'block_types';
        $block_types_table_sql = "CREATE TABLE $block_types_table_name (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        check_code INT NOT NULL DEFAULT 0,
        check_css INT NOT NULL DEFAULT 0,
        fields TEXT,
        PRIMARY KEY (id)
    ) $charset_collate;";

        // Create the `wp_block_articles` table
        $block_articles_table_name = $wpdb->prefix . 'block_articles';
        $block_articles_table_sql = "CREATE TABLE $block_articles_table_name (
        id INT NOT NULL AUTO_INCREMENT,
        post_id BIGINT UNSIGNED NOT NULL,
        views INT NOT NULL DEFAULT 0,
        clicks INT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY post_id (post_id)
    ) $charset_collate;";

        // Create the `wp_block_articles_blocks` table
        $block_articles_blocks_table_name = $wpdb->prefix . 'block_articles_blocks';
        $block_articles_blocks_table_sql = "CREATE TABLE $block_articles_blocks_table_name (
        block_id INT NOT NULL,
        article_id INT NOT NULL,
        position INT NOT NULL DEFAULT 0,
        PRIMARY KEY (block_id, article_id),
        KEY article_id (article_id)
    ) $charset_collate;";


        $block_anchors_table = $wpdb->prefix . 'block_anchors';

        $block_anchors_table_sql = "CREATE TABLE  $block_anchors_table (
        id INT(11) NOT NULL AUTO_INCREMENT,
        block_id INT(11) NOT NULL,
        tag VARCHAR(255) NOT NULL,
        position INT(11) NOT NULL,
        priority INT(11) NOT NULL,
        flag VARCHAR(255) NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (block_id) REFERENCES {$wpdb->prefix}blocks(id)
    ) $charset_collate;";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $blocks_table_sql );
        dbDelta( $block_types_table_sql );
        dbDelta( $block_articles_table_sql );
        dbDelta( $block_articles_blocks_table_sql );
        dbDelta( $block_anchors_table_sql );
    }
}



/**
 * Activate the plugin.
 */
function activate_block_systems_hm() {
    // Call the `create_tables` method of the `Block_Systems_HM` class
    Block_Systems_HM::create_tables();
}
register_activation_hook( __FILE__, 'activate_block_systems_hm' );

function block_systems_hm_admin_enqueue_styles() {
    wp_enqueue_style('block-systems-hm-admin', plugin_dir_url(__FILE__) . 'css/block-systems-hm-admin.css', array(), '1.0.0', 'all');
    wp_enqueue_script('block-systems-hm-fields', plugin_dir_url(__FILE__) . 'js/block-systems-hm-fields.js', array(), '1.0', true);
    wp_enqueue_script('block-systems-hm-filter', plugin_dir_url(__FILE__) . 'js/block-systems-hm-filter.js', array(), '1.0', true);
    wp_enqueue_script('block-systems-hm-main', plugin_dir_url(__FILE__) . 'js/block-systems-hm-main.js', array(), '1.0', true);

}
add_action('admin_enqueue_scripts', 'block_systems_hm_admin_enqueue_styles');
//function enqueue_prism() {
//    $current_url = $_SERVER['REQUEST_URI'];
//    if (strpos($current_url, 'block-systems-hm-edit-block') !== false) {
//        wp_enqueue_style('prism-css', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css');
//        wp_enqueue_script('prism-js', 'https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js', array(), '1.24.1', true);
//    }
//}
////add_action('admin_enqueue_scripts', 'enqueue_prism');
/**
 * Begin execution of the plugin.
 */

define('BLOCK_SYSTEMS_SHORTCODE_PREFIX', 'hm_');
function run_block_systems_hm()
{
    $plugin = new Block_Systems_HM();
}
run_block_systems_hm();