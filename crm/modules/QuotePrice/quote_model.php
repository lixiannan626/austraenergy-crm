<?php
/*
 * Quote Model, interaction with db
 */
/**
 * CRUD-Create
 * @param $post     posted data array $_POST
 * @return string   json format message in response to the AJAX request
 */
function create_item($post)
{
    $data = array(
        "item_name"     =>purify($post['item_name']),
        "item_category" =>purify($post['item_category']),
        "item_brand"    =>purify($post['item_brand']),
        "item_size"     =>purify($post['item_size']),
        "item_price"    =>purify($post['item_price']),
        "item_roof_type"=>purify($post['item_roof_type']),
        "item_service"  =>purify($post['item_service']),
        "item_available"=>1,
        "item_deleted"  =>0,
    );
    //Check for duplicate
    $dup_sql = "SELECT * FROM quote_item WHERE item_name = '".$data['item_name']."' AND item_deleted=0";
    if(result_row($dup_sql) == 0)
    {
        $insert_sql = "INSERT INTO quote_item (".implode(",", array_keys($data)).") VALUES ('".implode("','", array_values($data))."')";
        mysql_query($insert_sql);
        if(mysql_errno() == 0)
        {
            //Log the create operation----------------------------------------------------------//
            record_operation(get_item_id_with($data['item_name']), "create", $data);


            return json_msg(200, "Item created");
        }
        else
            return json_msg(300, "Query terminated unexpectedly");
    }
    else
        return json_msg(300, "Duplicate found, please rename.");
}

/**
 * CRUD-Retrieve
 * @param $post     posted data array $_POST from AJAX request
 * @return string   json format message in response to the AJAX request
 */
function retrieve_item($post)
{
    $item_id = purify($post['item_id']);
    $retrieve_sql = "SELECT * FROM quote_item WHERE item_deleted=0 AND item_id=$item_id";
    if(result_row($retrieve_sql) == 0)
        return json_msg(300, "Item not found.");
    else
        return json_msg(200, result_first_row($retrieve_sql));
}

/**
 * CRUD-Update
 * @param $post     posted data array $_POST from AJAX request
 * @return string   json format message in response to the AJAX request
 */
function update_item($post)
{
    $item_id = purify($post['item_id']);
    $data = array(
        "item_name"     =>purify($post['item_name']),
        "item_category" =>purify($post['item_category']),
        "item_brand"    =>purify($post['item_brand']),
        "item_size"     =>purify($post['item_size']),
        "item_price"    =>purify($post['item_price']),
        "item_roof_type"=>purify($post['item_roof_type']),
        "item_service"  =>purify($post['item_service']),
    );
    $retrieve_sql = "SELECT * FROM quote_item WHERE item_deleted=0 AND item_id=$item_id";
    $original = result_first_row($retrieve_sql);
    $updates = array();
    $updates_operation_purpose = array();
    foreach($data as $key=>$val)
    {
        if($data[$key] != $original[$key])
        {
            array_push($updates, "$key='$val'");
            $updates_operation_purpose[$key] = $val;
        }
    }
    //No updates found
    if(count($updates) == 0)
        return json_msg(300, "No change made");
    else
    {
        $update_sql = "UPDATE quote_item SET ".implode(",", $updates)." WHERE item_id=$item_id";
        //Record the operation----------------------------------------------------------------------------//
        record_operation($item_id, "update", $updates_operation_purpose);

        mysql_query($update_sql);
        if(mysql_errno() == 0)
            return json_msg(200, "Item info updated");
        else
            return json_msg(300, "Query terminated unexpectedly");
    }
}

/**
 * CRUD-Delete
 * @param $post     posted data array $_POST from AJAX request
 * @return string   json format message in response to the AJAX request
 */
function delete_item($post)
{
    $item_id = purify($post['item_id']);
    $delete_sql = "Update quote_item SET item_deleted=1 WHERE item_id=$item_id";
    //Record the operation-------------------------------------------------------------------------------//
    record_operation($item_id, "delete", array("item_deleted"=>1));

    mysql_query($delete_sql);
    if(mysql_errno() == 0)
        return json_msg(200, "Item deleted");
    else
        return json_msg(300, "Query terminated unexpectedly");
}
/**
 * CRUD-Update availability
 * @param $post     posted data array $_POST from AJAX request
 * @return string   json format message in response to the AJAX request
 */
function update_ava_item($post)
{
    $item_id = purify($post['item_id']);
    $retrieve_sql = "SELECT * FROM quote_item WHERE item_deleted=0 AND item_id=$item_id";
    if(result_row($retrieve_sql) == 0)
        return json_msg(300, "Item not found");
    else
    {
        $msg = "The item now become ";
        $row = result_first_row($retrieve_sql);
        if($row['item_available'] == 0)
        {
            $update_sql = "UPDATE quote_item SET item_available=1 WHERE item_id=$item_id";
            $msg .= "available";
        }
        else
        {
            $update_sql = "UPDATE quote_item SET item_available=0 WHERE item_id=$item_id";
            $msg .= "unavailable";
        }
        //Record the operation---------------------------------------------------------------------
        record_operation($item_id, "update", array("item_available"=>(1-$row['item_available'])));

        mysql_query($update_sql);

        if(mysql_errno() == 0)
            return json_msg(200, $msg);
        else
            return json_msg(300, "Query terminated unexpectedly");
    }

}
















/**
 * @param $cat      the name of the category: panel, inverter, mounting, installer
 * @return array    the array of items belonging to the category $cat
 */
function get_item_list($cat)
{
    $sql = "
    SELECT * FROM quote_item
    WHERE item_deleted = 0
    AND item_category = '$cat'
    ORDER BY item_name ASC
    ";
    return result_array($sql);
}

/**
 * @param $id       the item id
 * @return array    the row (associated array) that containing all the info for that item id
 */
function get_item($id)
{
    $sql = "
    SELECT * FROM quote_item
    WHERE item_deleted = 0
    AND item_id=$id";

    return result_first_row($sql);
}

/**
 * internal use
 * @param string $name     the name of the item, very specific
 * @return int string   the id of the item
 */
function get_item_id_with($name)
{
    $select_sql = "SELECT * FROM quote_item WHERE item_deleted=0 AND item_name='$name'";
    if(result_row($select_sql) == 0)
        return 0;
    else
    {
        $row = result_first_row($select_sql);
        return $row['item_id'];
    }
}

function get_user_with($user_id)
{
    $select_sql = "SELECT user_name FROM vtiger_users WHERE id=$user_id";
    if(result_row($select_sql) == 0)
        return "Intruder!";
    else
    {
        $row = result_first_row($select_sql);
        return $row['user_name'];
    }
}
/**
 * @param string $sql      the query
 * @return array            the array of rows, each row is an associated array
 */
function result_array($sql)
{
    $o = array();
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result))
        array_push($o, $row);
    return $o;
}

/**
 * @param string $sql      the query
 * @return array            the first row of the query, usually used when it's certain that there's only one row responding to the query
 */
function result_first_row($sql)
{
    return mysql_fetch_assoc(mysql_query($sql));
}

/**
 * @param string $sql      the query
 * @return int              the number of rows responding to the query
 */
function result_row($sql)
{
    return mysql_num_rows(mysql_query($sql));
}

/**
 * @param $var      the string which might be intrusive to database operation
 * @return string   the purified string
 */
function purify($var)
{
    return mysql_real_escape_string(trim($var));
}

/**
 * @param $code         200 or 300, 200 indicates all good, 300 indicates there's an error in database operation
 * @param string $msg   error message, if the code is 200, this parameter is useless
 * @return string       json formatted message containing the code and the message
 */
function json_msg($code, $msg="")
{
    return json_encode(array("code"=>$code, "msg"=>$msg));
}

/**
 * @param $arr
 * @return string
 */
function toString($arr)
{
    $o = "";
    if(count($arr) == 0) {}
    else
        foreach($arr as $key=>$val)
            $o .= "$key ==>> $val \n";
    return $o;
}

/**
 * @param int $item_id                  the item_id
 * @param string $item_operation       CRUD name, create, update, delete
 * @param array $oper_arr                     associated array, each key is the field and the val is the new value
 */
function record_operation($item_id, $item_operation, $oper_arr) {
    $time = date("Y-m-d H:i:s");
    $operator = get_user_with($_COOKIE['ck_login_id_vtiger']);
    //If no operation, then no insertion
    if(count($oper_arr) == 0){}
    else
        foreach($oper_arr as $field=>$value)
        {
            $insert_sql = "INSERT INTO quote_operation (item_id, item_operation, item_field, item_value, item_time, item_operator) VALUES ('$item_id', '$item_operation', '$field', '$value', '$time', '$operator')";
            mysql_query($insert_sql);
        }
}