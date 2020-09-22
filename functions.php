<?php
defined('ABSPATH') or die('No script kiddies please!');

//Check user role
function __asbanner_check_user_role($role_name)
{
    if( is_user_logged_in() ){ // check if user is logged in
        $get_user_id = get_current_user_id(); // get user ID
        $get_user_data = get_userdata($get_user_id); // get user data
        $get_roles = implode($get_user_data->roles);
        if( $role_name == $get_roles ){ // check if role name == user role
            return true;
        }
    }
}