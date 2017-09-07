<?php


/**
 * @param string $selection   the name of the selection, be it panel brand or panel model or.....
 * @param array $post         posted data from client
 * @return array|string       the row(s) of items
 */
function get_options($selection, $post)
{
    /*selection             depend                              used by child       viewed
     * panel brand          category=panel                      brand               brand
     * panel name           category=panel&brand=brand          id                  name
     *
     * inverter brand       category=inverter                   brand               brand
     * inverter name        category=inverter&brand=brand       id                  name
     *
     * mounting size        category=mounting                   size                size(formatted)  | DEPRECATED
     * mounting roof        category=mounting&size=size         size&roof           roof
     * mounting brand       category=mounting&size=size&roof=roof   id              brand
     *
     * install size         category=installer                  size                size(formatted)
     * install roof         category=installer&size=size        size&roof           roof
     * install service      category=installer&size=size&roof=roof  id              service
     *
     *
     *
     * 5 sizes x 3 roofs x 2 brands = 30
     * 5 sizes x 3 roofs x 2 services = 30
     */
    $shared_where = " item_deleted=0 AND item_available=1 ";
    //Panels
    if($selection == 'panel_brand')
    {
        $sql = "SELECT DISTINCT item_brand FROM quote_item WHERE $shared_where AND item_category='panel'";
    }
    else if($selection == 'panel_model')
    {
        $sql = "SELECT item_id, item_name FROM quote_item WHERE $shared_where AND item_category='panel' AND item_brand='".$post['item_brand']."'";
    }
    //Inverters
    else if($selection == 'inverter_brand')
    {
        $sql = "SELECT DISTINCT item_brand FROM quote_item WHERE $shared_where AND item_category='inverter'";
    }
    else if($selection == 'inverter_model')
    {
        $sql = "SELECT item_id, item_name FROM quote_item WHERE $shared_where AND item_category='inverter' AND item_brand='".$post['item_brand']."'";
    }
    //Mounting
    else if($selection == 'mounting_size')      //Never going to be used
    {
        $sql = "SELECT DISTINCT item_size FROM quote_item WHERE  $shared_where AND item_category='mounting'";
    }
    else if($selection == 'mounting_roof')      //Fix the size to 1000
    {
        $sql = "SELECT DISTINCT item_roof_type FROM quote_item WHERE $shared_where AND item_category='mounting' AND item_size='1000'";
    }
    else if($selection == 'mounting_brand')     //Fix the size to 1000
    {
        $sql = "SELECT item_brand, item_id FROM quote_item WHERE $shared_where AND item_category='mounting' AND item_size='1000' AND item_roof_type='".$post['item_roof_type']."'";
    }
    //Installer
    else if($selection == 'install_size')
    {
        $sql = "SELECT DISTINCT item_size FROM quote_item WHERE $shared_where AND item_category='installer'";
    }
    else if($selection== 'install_roof')
    {
        $sql = "SELECT DISTINCT item_roof_type FROM quote_item WHERE $shared_where AND item_category='installer' AND item_size='".$post['item_size']."'";
    }
    else if($selection == 'install_service')
    {
        $sql = "SELECT item_service, item_id FROM quote_item WHERE $shared_where AND item_category='installer' AND item_size='".$post['item_size']."' AND item_roof_type='".$post['item_roof_type']."'";
    }
    else
        return "Sth not right";
    //echo $sql;
    return result_array($sql." ORDER BY item_name ASC");


}


/**
 * @param array $post       posted data array
 * @return array|null       item associated array or NULL
 */
function get_item($post)
{
    $id = purify($post['item_id']);
    $select_sql = "SELECT * FROM quote_item WHERE item_deleted=0 AND item_available=1 AND item_id='$id'";
    if(result_row($select_sql) == 0)
        return NULL;
    else
    {
        return result_first_row($select_sql);
    }
}


/**
 * @param string $field     the field holding specific value
 * @return int|string       the value, e.g. markup percentage, stc price
 */
function get_config_value($field)
{
    $select_sql = "SELECT config_val FROM quote_config WHERE config_key='$field'";
    if(result_row($select_sql) == 0)
        return 0;
    else
    {
        $row = result_first_row($select_sql);
        return $row['config_val'];
    }
}


/**
 * @param array $post  posted data
 * @return array    the customer list with specified keyword in their names
 */
function search_customer($post)
{
    $names = $post['key'];
    $names = str_replace(",", "", trim($names));
    if($names == "")        //Empty name, not worth doing the search
        return array();

    //1. Search name query
    $names = explode(" ", $names);
    $ors = array();
    foreach($names as $name)
    {
        if($name != "")
        {
            array_push($ors, "firstname LIKE '%$name%'");
            array_push($ors, "lastname LIKE '%$name%'");
        }
    }
    //2. Restriction of contact owner
    //If the user is not Sysadmin, Admin, Support, then he/she could only search the customer which has been assigned to him/her.
    $ownerissue = "";
    $search_all = array("H1", "H10", "H2");
    if(in_array(get_role_with($_COOKIE['ck_login_id_vtiger']), $search_all) == false)
        $ownerissue = " AND smownerid = '".$_COOKIE['ck_login_id_vtiger']."'";
    $select_sql = "SELECT crmid, firstname, lastname, contact_no, mailingstreet, mailingcity, mailingstate, mailingzip
                    FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
                    WHERE crmid = vtiger_contactdetails.contactid
                    AND crmid = vtiger_contactscf.contactid
                    AND crmid = contactaddressid
                    AND deleted = 0
                    AND (".implode(" OR ", $ors).")
                    $ownerissue
                    LIMIT 0,30
    ";
    if(result_row($select_sql) == 0)
        return array();
    else
        return result_array($select_sql);
}


/**
 * @param array $post  posted data
 * @return string       response of the quote saving, success, or fail
 */
function save_quote($post)
{
    /*
     * The quote snapshot contains following info.
     * 1. normal snapshot
     * 2. discounted price
     * 3. customer id
     * 4. sales id
     * 5. sales name
     * 6. quote date
     * 7. executive summary
     */
    //Get data from $_POST
    $ss = array();
    $data                       = $post['data'];
    $ss['ss_snapshot']          = purify(show_item_summary(preprocess_item($data), 'production'));
    $ss['ss_sales_id']          = $_COOKIE['ck_login_id_vtiger'];
    $ss['ss_sales_name']        = get_user_with($ss['ss_sales_id']);
    $ss['ss_time']              = date("Y-m-d H:i:s");
    $ss['ss_discounted_price']  = purify($post['discounted']);
    $ss['ss_customer_id']       = $post['crmid'];
    $ss['ss_executive']         = purify(show_item_summary(preprocess_item($data), 'development'));

    //Check for duplicate based on customer_id, sales_id, snapshot content, discounted price
    $select_sql = "SELECT * FROM quote_snapshot WHERE ss_customer_id='".$ss['ss_customer_id']."' AND ss_sales_id='".$ss['ss_sales_id']."' AND ss_snapshot='".$ss['ss_snapshot']."' AND ss_discounted_price='".$ss['ss_discounted_price']."'";
    if(result_row($select_sql) > 0)
        return "<div class='SaveFail'>similar quote in the system already</div>";
    //Insert
    else
    {
        $insert_sql = "INSERT INTO quote_snapshot (".implode(",", array_keys($ss)).") VALUES ('".implode("','", array_values($ss))."')";
        mysql_query($insert_sql);
        if(mysql_errno() == 0)
        {
            $select_sql = "SELECT ss_id FROM quote_snapshot WHERE ss_time='".$ss['ss_time']."' AND ss_sales_id='".$ss['ss_sales_id']."' ORDER BY ss_id DESC";
            $q = result_first_row($select_sql);
            return "<div class='SaveSuccess'>Quote Q".str_pad((int)$q['ss_id'], 4, "0", STR_PAD_LEFT)." Saved</div>";
        }
        else
            return "<div class='SaveFail'>Sth prevents saving from happening, report the incident to IT.</div>";
    }
}

function get_customer_with($customer_id)
{
    $select = "SELECT crmid, firstname, lastname, contact_no, mailingstreet, mailingcity, mailingstate, mailingzip
                    FROM vtiger_crmentity, vtiger_contactdetails, vtiger_contactscf, vtiger_contactaddress
                    WHERE crmid = vtiger_contactdetails.contactid
                    AND crmid = vtiger_contactscf.contactid
                    AND crmid = contactaddressid
                    AND deleted = 0
                    AND crmid = $customer_id";
    if(result_row($select) == 0)
        return array();
    else
        return result_first_row($select);
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

function result_array($sql)
{
    $o = array();
    $result = mysql_query($sql);
    while($row = mysql_fetch_assoc($result))
        array_push($o, $row);
    return $o;
}
function result_first_row($sql)
{
    return mysql_fetch_assoc(mysql_query($sql));
}
function result_row($sql)
{
    return mysql_num_rows(mysql_query($sql));
}
function result_arr($sql)
{
    $o = array();
    $result = mysql_query($sql);
    while($row = mysql_fetch_row($result))
        array_push($o, $row);
    return $o;
}
function get_role_with($id)
{
    $select_sql = "SELECT roleid FROM vtiger_user2role WHERE userid=$id";
    if(result_row($select_sql) == 0)
        return "H11";
    else
    {
        $row = result_first_row($select_sql);
        return $row['roleid'];
    }
}
function purify($var)
{
    return mysql_real_escape_string(trim($var));
}

/**
 * @param int $code     200 or 300
 * @param string $msg   error message
 * @return string       json encode message
 */
function json_msg($code, $msg="")
{
    return json_encode(array("code"=>$code, "msg"=>$msg));
}


/**
 * @param array $post  postcode of the installation address
 * @return int $stc    calculated stc
 */
function get_stc($post)
{
    $postcode = $post['postcode'];
    $size = $post['size'];
    $zone = 0;
    $zones = array(
    0=>3,
    800=>2,
    870=>1,
    880=>2,
    1001=>3,
    2357=>2,
    2358=>3,
    2385=>2,
    2394=>3,
    2396=>2,
    2399=>3,
    2400=>2,
    2401=>3,
    2405=>2,
    2408=>3,
    2411=>2,
    2415=>3,
    2537=>4,
    2538=>3,
    2545=>4,
    2558=>3,
    2627=>4,
    2630=>3,
    2631=>4,
    2640=>3,
    2821=>2,
    2843=>3,
    2873=>2,
    2875=>3,
    2877=>2,
    2890=>3,
    2898=>3,
    2900=>3,
    3000=>4,
    3391=>3,
    3399=>4,
    3414=>3,
    3427=>4,
    3475=>3,
    3515=>4,
    3517=>3,
    3521=>4,
    3525=>3,
    3539=>4,
    3540=>3,
    3550=>4,
    3561=>3,
    3570=>4,
    3571=>3,
    3607=>4,
    3618=>3,
    3623=>4,
    3629=>3,
    3658=>4,
    3685=>3,
    3688=>4,
    3725=>3,
    3732=>4,
    4000=>3,
    4417=>2,
    4418=>3,
    4423=>2,
    4424=>3,
    4427=>2,
    4474=>1,
    4477=>2,
    4479=>1,
    4486=>2,
    4491=>1,
    4493=>2,
    4500=>3,
    4722=>2,
    4723=>3,
    4724=>2,
    4736=>1,
    4737=>3,
    4825=>2,
    4828=>3,
    4829=>2,
    4830=>3,
    5262=>4,
    5264=>3,
    5271=>4,
    5301=>3,
    5430=>2,
    5451=>3,
    5654=>2,
    5670=>3,
    5680=>2,
    5700=>3,
    5710=>2,
    5723=>1,
    5725=>2,
    5734=>1,
    5800=>3,
    6244=>4,
    6251=>3,
    6255=>4,
    6271=>3,
    6316=>4,
    6358=>3,
    6394=>4,
    6401=>3,
    6431=>2,
    6432=>3,
    6434=>2,
    6440=>1,
    6442=>3,
    6445=>4,
    6460=>3,
    6468=>2,
    6470=>3,
    6472=>2,
    6475=>3,
    6507=>2,
    6556=>3,
    6574=>2,
    6603=>3,
    6608=>2,
    6642=>1,
    6725=>2,
    6751=>1,
    6798=>2,
    6800=>3,
    7000=>4,
    9000=>3,
    9999=>3
    );
    $keys = array_keys($zones);

    //$postcode between 0 and 9999
    if($postcode > 10000 || $postcode < 0 || $postcode == "")
    {
        $rating = 0;
    }
    else
    {
        for($i = 0; $i < count($keys); $i++)
        {
            if($keys[$i] == 9999)
            {
                $zone = 3;
                break;
            }
            else if($postcode >= $keys[$i] && $postcode < $keys[$i+1])
            {
                $zone = $zones[$keys[$i]];
                break;
            }
        }
        //Got the rating, work out STC
        $ratings = array(1=>1.622, 2=>1.536, 3=>1.382, 4=>1.185);
        $rating = $ratings[$zone];
    }
    $stc = $size*$rating*1*15;
    $stc = floor($stc);
    return $stc;
}


/**
 * @param array $data the item info from client
 * @return array    the preprocess item info
 */
function preprocess_item($data)
{
    $items = array();
    if(count($data) == 0)
        die("No item selected");
    //Process individual item
    foreach($data as $dat)
    {
        //id, displayName, qty
        $id = $dat['id'];
        $displayName = $dat['displayName'];
        $qty = $dat['qty'];
        $cat = $price = $size = $roof = "";

        //Extra
        if($id == -1)
        {
            $cat = "Extra";
            $displayName .= " \$".$qty;
            $price = $qty;
            $qty = 1;
        }
        //STC
        else if($id == -5)
        {
            $cat = "Rebates";
            $price = -1*get_config_value('stc_price');
        }
        //Panel & inverter & mounting & installer
        else if($id > 0)
        {
            $item = get_item(array('item_id'=>$id));
            $cat = $item['item_category'];
            $size = $item['item_size'];
            $displayName = (strtolower($cat) != 'installer') ? $item['item_name'] : ($item['item_service']." ".$item['item_name']);
            $price = $item['item_price'];
            $roof = $item['item_roof_type'];
        }

        if($id > 0 || $id == -1 || $id == -5)
            array_push($items, array('id'=>$id, 'cat'=>$cat, 'display'=>$displayName, 'price'=>$price, 'qty'=>$qty, 'subtotal'=>$price*$qty, 'size'=>$size, 'roof'=>$roof));
    }
    return $items;
}


/**
 * @param array $post  posted array from client
 * @return string   message back to client, either error or success
 */
function update_markup_stc($post)
{
    $field = purify($post['field']);
    $value = purify($post['value']);
    //1. Update value
    $update_sql = "UPDATE quote_config SET config_val='$value' WHERE config_key='$field'";
    mysql_query($update_sql);
    //2. Update log
    if(mysql_errno() == 0)
    {
        $time = date("Y-m-d H:i:s");
        $who = $_COOKIE['ck_login_id_vtiger'];
        $where = $_SERVER['REMOTE_ADDR'];
        mysql_query("UPDATE quote_config SET config_history=CONCAT(config_history, '\n', '$value', '|', '$time', '|', '$who', '|', '$where') WHERE config_key='$field'");
        return "<span class='Success Message'>Updated successfully.</span>";
    }
    else
        return "<span class='Error Message'>Can not update, try later or contact IT.</span>";
}
?>