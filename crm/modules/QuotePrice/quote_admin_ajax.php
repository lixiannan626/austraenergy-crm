<?php
    include_once('quote_db.php');
    include_once('quote_config.php');
    include_once('quote_model.php');
    include_once('quote_view_list.php');

    error_reporting(0);
    //Avoid Intruder
    if(!in_array($_COOKIE['ck_login_id_vtiger'], $allowed))
        die("Forbidden City");

    //Serious Business
    if($_POST['crud'] == 'create')
    {
        echo create_item($_POST);
    }
    else if($_POST['crud'] == 'retrieve')
    {
        echo retrieve_item($_POST);
    }
    else if($_POST['crud'] == 'update')
    {
        echo update_item($_POST);
    }
    else if($_POST['crud'] == 'delete')
    {
        echo delete_item($_POST);
    }
    else if($_POST['crud'] == 'update_ava')
    {
        echo update_ava_item($_POST);
    }
    else if($_POST['crud'] == 'reload')
    {
        show_item_list(get_item_list($_POST['category']), $thds_arr[$_POST['category']]);
    }
    else if($_POST['crud'] == 'reload_row')
    {
        show_item_row(get_item($_POST['item_id']), $thds_arr[$_POST['category']]);
    }
?>