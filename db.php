<?php

 function getBanners()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'as_banners';

    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC");
}

function cleanNumber($id)
{
    return (int) preg_replace( '/[^0-9]/', '', $id );
}

function getActiveBannersItensById($id=null)
{
    if(empty($id)) return [];
    $id = preg_replace( '/[^0-9]/', '', $id );

    global $wpdb;
    $as_banners = $wpdb->prefix . 'as_banners';
    $as_banners_itens = $wpdb->prefix . 'as_banners_itens';

    $query = "SELECT $as_banners_itens.* FROM $as_banners 
    INNER JOIN $as_banners_itens ON $as_banners_itens.banner_id = $as_banners.id
    where $as_banners.id=$id and $as_banners.published = 1";

    $exit = $wpdb->get_results($query,ARRAY_A );

    return (!empty($exit) ? $exit : [] );
}

function getBannersItensById($id=null)
{
    if(empty($id)) return [];
    $id = preg_replace( '/[^0-9]/', '', $id );

    global $wpdb;
    $as_banners = $wpdb->prefix . 'as_banners';
    $as_banners_itens = $wpdb->prefix . 'as_banners_itens';

    $query = "SELECT $as_banners_itens.* FROM $as_banners 
    INNER JOIN $as_banners_itens ON $as_banners_itens.banner_id = $as_banners.id
    where $as_banners.id=$id";

    $exit = $wpdb->get_results($query,ARRAY_A );

    return (!empty($exit) ? $exit : [] );
}

function getBannerById($id=null)
{
    if(empty($id)) return [];
    $id = preg_replace( '/[^0-9]/', '', $id );

    global $wpdb;
    $as_banners = $wpdb->prefix . 'as_banners';

    $query = "SELECT $as_banners.* FROM $as_banners where $as_banners.id=$id";

    $exit = $wpdb->get_results($query,ARRAY_A );

    return (!empty($exit) ? end($exit) : [] );
}

function insertBanner($data = null)
{
    global $wpdb;
    if(empty($data)) return true;

    $table_name = $wpdb->prefix . 'as_banners';
    $insert_banner = $wpdb->insert( $table_name, $data, ['%s','%s','%s','%s']);
    if($insert_banner === FALSE){
        return $insert_banner;
    } else {
        return $wpdb->insert_id;
    }
}

function updateBanner($data = null)
{
    global $wpdb;
    if(empty($data)) return true;

    $table_name = $wpdb->prefix . 'as_banners';

    return $wpdb->update(
        $table_name,
        $data,
        array("id" => $data['id'])
    );
}

function updateBannerItem($data = null)
{
    global $wpdb;
    if(empty($data)) return true;

    $table_name = $wpdb->prefix . 'as_banners_itens';

    return $wpdb->update(
        $table_name,
        $data,
        array("id" => $data['id'])
    );
}

function insertBannerItem($data = null)
{
    global $wpdb;
    if(empty($data)) return true;

    $table_name = $wpdb->prefix . 'as_banners_itens';
    return $wpdb->insert( $table_name, $data, ['%s','%s','%s','%s']);
}



function deleteBanner($id = null)
{
    global $wpdb;
    if(empty($id)) return true;

    $table_name = $wpdb->prefix . 'as_banners';

    return $wpdb->delete(
        $table_name,
        array('id' => $id)
    );
}

function my_print_error(){

    global $wpdb;

    if($wpdb->last_error !== '') :

        $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
        $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

        print "<div id='error'>
        <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
        <code>$query</code></p>
        </div>";

    endif;

}