<?php
defined('ABSPATH') or die('No script kiddies please!');

global $asbanner_db_version;
$asbanner_db_version = '1.0';

// Install
function asbanner_install()
{
    global $wpdb;
    global $asbanner_db_version;
    $charset_collate = $wpdb->get_charset_collate();
    $asbanner_db_version = '1.0';

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

    $sql .= "
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

    add_option('asbanner_db_version', $asbanner_db_version);
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