<?php
defined('ABSPATH') or die('No script kiddies please!');
require_once ('ASBannerQuery.class.php');

class ASBanner {
    public function __construct()
    {

        add_action('admin_menu', array(&$this,'admin_menu_options_panel'));
        add_action( 'admin_enqueue_scripts', array(&$this,'load_admin_libs' ));
        add_shortcode( 'as_banner', array(&$this,'process_shortcode' ));
        $this->DB = new ASBannerQuery();

    }

    function load_admin_libs()
    {
        wp_enqueue_media();
    }

    function admin_menu_options_panel()
    {
        add_menu_page('AS Banner - List', 'AS Banner', 'manage_options', 'banner-default-list', array(&$this,'renderView'),'dashicons-format-image');
        add_submenu_page( 'banner-default-list', 'AS Banner - Add', 'Add new', 'manage_options', 'banner/add', array(&$this,'admin_add'));
        add_submenu_page('_doesnt_exist', __('AS Banner - Edit', 'textdomain'), '', 'manage_categories', 'banner/edit', array(&$this,'admin_edit'));
        add_submenu_page('_doesnt_exist', __('AS Banner - Delete', 'textdomain'), '', 'manage_categories', 'banner/delete', array(&$this,'admin_delete'));
    }

    function process_shortcode( $attributes, $content)
    {
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

    function renderView($view = 'views/list')
    {
        return require_once($view.'.php' );
    }

    function admin_edit()
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

    function admin_delete()
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

    function admin_add()
    {
        if ((is_admin() || __enquetes_check_user_role('editor'))) {

            if (!isset($_POST['submit'])) {

                as_add_render();

            } else {

                $data = [
                    'title' => sanitize_text_field($_POST['banner']['title']),
                    'published' => cleanNumber($_POST['banner']['published']),
                    'created' => date('Y-m-d H:i:s')
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

}