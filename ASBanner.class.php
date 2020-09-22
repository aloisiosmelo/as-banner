<?php
defined('ABSPATH') or die('No script kiddies please!');
require_once ('ASBannerQuery.class.php');
require_once ('functions.php');

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
            $banners = $this->DB->getActiveBannersItensById($attributes['id']);
            return require_once('views/banner.php' );
        } else {
            return '';
        }
    }

    function renderView($view)
    {
        $view = empty($view) ? 'views/list': $view;
        return require_once($view.'.php' );
    }

    function admin_edit_view ($id)
    {
        $id = $this->DB->cleanNumber($id);

        global $banner;
        global $banner_itens;

        $banner = $this->DB->getBannerById($id);
        $banner_itens = $this->DB->getBannersItensById($id);

        require_once('views/edit.php' );
    }

    function admin_add_view()
    {
        self::renderView('views/add' );
    }

    function admin_edit()
    {
        if ((is_admin() || __asbanner_check_user_role('editor'))) {

            if (isset($_GET['eId']) && !isset($_POST['submit'])) {

                self::admin_edit_view($_GET['eId']);

            } else {

                $data = [
                    'id' => $this->DB->cleanNumber($_GET['eId']),
                    'title' => sanitize_text_field($_POST['banner']['title']),
                    'published' => $this->DB->cleanNumber($_POST['banner']['published']),
                ];

                if(updateBanner($data) === FALSE){
                    printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Save error.');
                    self::admin_edit_view($_GET['eId']);
                }

                foreach ($_POST['item'] as $item){
                    $data_item = [];
                    if(!empty($item['id'])){
                        $data_item = [
                            'id' => $this->DB->cleanNumber($item['id']),
                            'title' => sanitize_text_field($item['title']),
                            'description' => sanitize_text_field($item['description']),
                            'image_attachment_id' => sanitize_text_field($item['image_attachment_id']),
                            'banner_id' => $this->DB->cleanNumber($item['banner_id']),
                        ];
                        if($this->DB->updateBannerItem($data_item) === FALSE){
                            printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Update item error.');
                            self::admin_edit_view($_GET['eId']);
                        }
                    } else {
                        $data_item = [
                            'title' => sanitize_text_field($item['title']),
                            'description' => sanitize_text_field($item['description']),
                            'image_attachment_id' => sanitize_text_field($item['image_attachment_id']),
                            'banner_id' => $this->DB->cleanNumber($item['banner_id']),
                        ];
                        if(insertBannerItem($data_item) === FALSE){
                            printf('<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', 'Save item error.');
                            self::admin_edit_view($_GET['eId']);
                        }
                    }
                }

                printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful updated.');
                self::admin_edit_view($_GET['eId']);

            }
        }
    }

    function admin_delete()
    {
        if ((is_admin() || __asbanner_check_user_role('editor'))) {

            if (isset($_GET['eId'])) {

                $banner_id = $this->DB->cleanNumber($_GET['eId']);

                if($this->DB->deleteBanner($banner_id)){
                    printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful deleted.');
                } else {
                    printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Save error.');
                }

                self::renderView();

            } else {
                self::renderView();
            }
        }
    }

    function admin_add()
    {
        if ((is_admin() || __asbanner_check_user_role('editor'))) {

            if (!isset($_POST['submit'])) {

                self::admin_add_view();

            } else {

                $data = [
                    'title' => sanitize_text_field($_POST['banner']['title']),
                    'published' => $this->DB->cleanNumber($_POST['banner']['published']),
                    'created' => date('Y-m-d H:i:s')
                ];

                $insert_banner = insertBanner($data); // return last id if save

                if($insert_banner === FALSE){
                    printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Save error.');
                    self::renderView();
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
                            self::renderView();
                        }
                    }
                }

                printf('<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', 'Banner successful created.');
                self::renderView();
            }

        }
    }

}