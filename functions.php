<?php
defined('ABSPATH') or die('No script kiddies please!');

//Check user role
function __asbanner_check_user_role($role_name){
    if( is_user_logged_in() ){ // check if user is logged in
        $get_user_id = get_current_user_id(); // get user ID
        $get_user_data = get_userdata($get_user_id); // get user data
        $get_roles = implode($get_user_data->roles);
        if( $role_name == $get_roles ){ // check if role name == user role
            return true;
        }
    }
}

function asbanner_install()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $as_banners = $wpdb->prefix . 'as_banners';
    $as_banners_itens = $wpdb->prefix . 'as_banners_itens';

    $sql = "CREATE TABLE IF NOT EXISTS $as_banners (
		id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
		user_id BIGINT(20) NOT NULL,
		created DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
		published TINYINT DEFAULT 0 NOT NULL,
		title TINYTEXT NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    $sql2 = "
        CREATE TABLE IF NOT EXISTS $as_banners_itens (
		id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
	    banner_id MEDIUMINT(9) NOT NULL,
	    url VARCHAR(55) DEFAULT '',
	    title VARCHAR(250) DEFAULT '',
	    description VARCHAR(500) DEFAULT '',
	    image_attachment_id MEDIUMINT(9) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);

    add_option('asbanner_db_version', '1.0');
}

function asbanner_remove_database() {
    global $wpdb;
    $as_banners = $wpdb->prefix . 'as_banners';
    $as_banners_itens = $wpdb->prefix . 'as_banners_itens';

    $sql = "DROP TABLE IF EXISTS $as_banners";
    $sql .=  "DROP TABLE IF EXISTS $as_banners_itens";;
    $wpdb->query($sql);

    delete_option("jal_db_version");
}

register_deactivation_hook( __FILE__,'asbanner_remove_database' );
register_activation_hook( __FILE__, 'asbanner_install' );