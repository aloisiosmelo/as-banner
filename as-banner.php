<?php

defined('ABSPATH') or die('No script kiddies please!');

/**
 * Plugin Name:       AS-Banner
 * Plugin URI:        https://github.com/aloisiosmelo/as-banner
 * Description:       Basic incredible AS-Banner plugin.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Aloisio Soares
 * Author URI:       https://github.com/aloisiosmelo
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       as-banner
 * Domain Path:       /languages
 */

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

require_once('install.php' );
require_once('db.php' );
require_once('functions.php' );


// Register actions
add_action('admin_menu', 'theme_options_panel');
add_action( 'plugins_loaded', 'as_banner_update_db_check' );

register_activation_hook( __FILE__, 'jal_install' );


function load_admin_libs() {
    wp_enqueue_media();
    wp_enqueue_script( 'wp-media-uploader',  plugin_dir_url(__FILE__).'admin/js/plupload.full.min.js', array( 'jquery' ), 1.0 );
}
add_action( 'admin_enqueue_scripts', 'load_admin_libs' );

add_shortcode( 'as_banner', 'as_banner_process_shortcode' );
function as_banner_process_shortcode( $attributes, $content = null ) {
    extract( shortcode_atts( array(
        'id' => ''
    ), $attributes ) );

    if(!empty($attributes['id'])){
        global $banners;
        $banners = getActiveBannersItensById($attributes['id']);
        return require_once('views/banner.php' );
    } else {
        return '';
    }
}


/*
 * Functions
 * */

// Admin - Menu
function theme_options_panel()
{
    add_menu_page('AS Banner - List', 'AS Banner', 'manage_options', 'banner-default-list', 'initialize_asbanner','dashicons-format-image');
    add_submenu_page( 'banner-default-list', 'AS Banner - Add', 'Add new', 'manage_options', 'banner/add', 'as_banner_admin_add');
    add_submenu_page('_doesnt_exist', __('AS Banner - Edit', 'textdomain'), '', 'manage_categories', 'banner/edit', 'as_banner_admin_edit');
    add_submenu_page('_doesnt_exist', __('AS Banner - Delete', 'textdomain'), '', 'manage_categories', 'banner/delete', 'as_banner_admin_delete');
}


// Admin - Pages
function initialize_asbanner()
{
    require_once('views/list.php' );
}

function as_edit_render ($id)
{
    $id = cleanNumber($id);

    global $banner;
    global $banner_itens;

    $banner = getBannerById($id);
    $banner_itens = getBannersItensById($id);

    require_once('views/edit.php' );
}

function as_add_render()
{
    require_once('views/add.php' );
}

function as_banner_admin_edit()
{
    if ((is_admin() || __enquetes_check_user_role('editor'))) {

        if (isset($_GET['eId']) && !isset($_POST['submit'])) {

            as_edit_render($_GET['eId']);

        } else {

            $data = [
                'id' => cleanNumber($_GET['eId']),
                'title' => sanitize_text_field($_POST['banner']['title']),
                'published' => cleanNumber($_POST['banner']['published']),
            ];

            if(updateBanner($data) === FALSE){
                printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Save error.');
                as_edit_render($_GET['eId']);
            }

            foreach ($_POST['item'] as $item){
                $data_item = [];
                if(!empty($item['id'])){
                    $data_item = [
                        'id' => cleanNumber($item['id']),
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_text_field($item['description']),
                        'image_attachment_id' => sanitize_text_field($item['image_attachment_id']),
                        'banner_id' => cleanNumber($item['banner_id']),
                    ];
                    if(updateBannerItem($data_item) === FALSE){
                        printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Update item error.');
                        as_edit_render($_GET['eId']);
                    }
                } else {
                    $data_item = [
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_text_field($item['description']),
                        'image_attachment_id' => sanitize_text_field($item['image_attachment_id']),
                        'banner_id' => cleanNumber($item['banner_id']),
                    ];
                    if(insertBannerItem($data_item) === FALSE){
                        printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Save item error.');
                        as_edit_render($_GET['eId']);
                    }
                }
            }

            printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful updated.');
            as_edit_render($_GET['eId']);

        }
    }
}

function as_banner_admin_delete()
{
    if ((is_admin() || __enquetes_check_user_role('editor'))) {

        if (isset($_GET['eId'])) {

            $banner_id = cleanNumber($_GET['eId']);

            if(deleteBanner($banner_id)){
                printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful deleted.');
            } else {
                printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Save error.');
            }

            initialize_asbanner();

        } else {
            initialize_asbanner();
        }
    }
}

function as_banner_admin_add()
{
    if ((is_admin() || __enquetes_check_user_role('editor'))) {

        if (!isset($_POST['submit'])) {

            as_add_render();

        } else {

            $data = [
                'title' => sanitize_text_field($_POST['banner']['title']),
                'published' => cleanNumber($_POST['banner']['published']),
                'created' => wp_date('Y-m-d H:i:s')
            ];

            $insert_banner = insertBanner($data); // return last id if save

            if($insert_banner === FALSE){
                printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Save error.');
                initialize_asbanner();
            } else if(!empty($_POST['item'])){
                foreach ($_POST['item'] as $item){
                    $data_item = [
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_text_field($item['description']),
                        'image_attachment_id' => sanitize_text_field($item['image_attachment_id']),
                        'banner_id' => $insert_banner,
                    ];
                    if(insertBannerItem($data_item) === FALSE){
                        printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Save item error.');
                        initialize_asbanner();
                    }
                }
            }

            printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful created.');
            initialize_asbanner();
        }

    }
}