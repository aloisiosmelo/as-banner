<?php

global $jal_db_version;
$jal_db_version = '1.0';

// Update
function as_banner_update_db_check() {
    global $jal_db_version;
    if ( get_site_option( 'jal_db_version' ) != $jal_db_version ) {
        jal_install();
    }
}

// Install
function jal_install() {
    global $wpdb;
    global $jal_db_version;

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix . 'as_banners';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user_id bigint(20) NOT NULL,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		published tinyint DEFAULT 0 NOT NULL,
		title tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    dbDelta( $sql );

    $table_name_2 = $wpdb->prefix . 'as_banners_itens';
    $sql_2 = "CREATE TABLE $table_name_2 (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
	    banner_id mediumint(9) NOT NULL,
	    url varchar(55) DEFAULT '',
	    title varchar(250) DEFAULT '',
	    description varchar(500) DEFAULT '',
	    image_attachment_id mediumint(9) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    dbDelta( $sql_2 );

    add_option( 'jal_db_version', $jal_db_version );
}

function jal_install_data() {
    global $wpdb;

    $welcome_name = 'Mr. WordPress';
    $welcome_text = 'Congratulations, you just completed the installation!';

    $table_name = $wpdb->prefix . 'as_banners';

    $wpdb->insert(
        $table_name,
        array(
            'time' => current_time( 'mysql' ),
            'name' => $welcome_name,
            'text' => $welcome_text,
        )
    );
}