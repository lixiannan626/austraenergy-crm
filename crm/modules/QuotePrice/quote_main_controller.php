<?php
    include_once('quote_db.php');
    include_once('quote_config.php');
    include_once('quote_main_model.php');
    include_once('quote_main_views.php');

    error_reporting(0);
    //Avoid Intruder
    if(!isset($_COOKIE['ck_login_id_vtiger']))
        die("Forbidden City");


    if($_POST['action'] == 'retrieve')              //Get select groups
    {
        show_item_input_container($_POST['selection'], get_options($_POST['selection'], $_POST));
    }
    else if($_POST['action'] == 'get-cost')         //Get select object (no cost at all)
    {
        show_item(get_item($_POST));
    }
    else if($_POST['action'] == 'new-inverter')     //Get a new inverter
    {
        show_inverter();
    }
    else if($_POST['action'] == 'get-stc')          //Calculate STC
    {
        echo get_stc($_POST);
    }
    else if($_POST['action'] == 'calculate')        //Calculate
    {
        $items = preprocess_item($_POST['data']);
        //pass the items to view
        //Re-enforce
        if(in_array($_COOKIE['ck_login_id_vtiger'], $allowed) == false)
            $_POST['view'] = 'production';
        $quote = show_item_summary($items, $_POST['view']);
        //Log Quote Request
        mysql_query("INSERT INTO quote_request (req_time, req_user, req_detail) VALUES ('".date("Y-m-d H:i:s")."','".$_COOKIE['ck_login_id_vtiger']."','".mysql_real_escape_string($quote)."')");

        echo $quote;
    }
    else if($_POST['action'] == 'update-markup-stc' && in_array($_COOKIE['ck_login_id_vtiger'], $allowed))      //Change Markup & STC
    {
        //Only digits and authorised personal is allowed to change mark-ups and stc price.
        if(preg_match("/^\d+\.?\d*$/", $_POST['value']) == false || $_POST['value'] < 0)
            echo "<span class='Error Message'>The input ".$_POST['value']." is smaller than 0, try another figure.</span>";
        else
            echo update_markup_stc($_POST);
    }
    else if($_POST['action'] == 'search-customer')
    {
        $customers = search_customer($_POST);

        echo show_customer_list($customers);
    }
    else if($_POST['action'] == 'save-quote')
    {
        echo save_quote($_POST);
    }
    else if($_POST['action'] == 'get-detailed-snapshot')
    {
        $sid = $_POST['sid'];
        $qid = $_POST['qid'];
        //Only when the user is authorised, can he retrieve the detailed snapshot
        if(in_array($_COOKIE['ck_login_id_vtiger'], $allowed) == true)
        {
            $select = "SELECT ss_executive FROM quote_snapshot WHERE ss_id=$qid AND ss_sales_id='$sid'";
            if(result_row($select) != 0)
            {
                $row = result_first_row($select);
                    echo $row['ss_executive'];
            }
        }
    }



?>