<?php


/**
 * @param string $selection  the select group name
 * @param $data     the query result which is to be viewed
 */
function show_item_input_container($selection, $data)
{
    $title_arr = array(
        "panel_brand"=>"panel brand",
        "panel_model"=>"panel model",
        "inverter_brand"=>"inverter brand",
        "inverter_model"=>"inverter model",
        "mounting_size"=>"mounting size",
        "mounting_roof"=>"roof type",
        "mounting_brand"=>"mounting brand",
        "install_size"=>"install size",
        "install_roof"=>"roof type",
        "install_service"=>"service type",
    );
    $child_arr = array(
        "panel_brand"=>"panel_model",
        "panel_model"=>"",
        "inverter_brand"=>"inverter_model",
        "inverter_model"=>"",
        "mounting_size"=>"mounting_roof",
        "mounting_roof"=>"mounting_brand",
        "mounting_brand"=>"",
        "install_size"=>"install_roof",
        "install_roof"=>"install_service",
        "install_service"=>"",
    );
    $field_arr = array(
        "panel_brand"=>"item_brand",
        "panel_model"=>"item_id",
        "inverter_brand"=>"item_brand",
        "inverter_model"=>"item_id",
        "mounting_size"=>"item_size",
        "mounting_roof"=>"item_roof_type",
        "mounting_brand"=>"item_id",
        "install_size"=>"item_size",
        "install_roof"=>"item_roof_type",
        "install_service"=>"item_id",
    );
    $level_arr = array(
        "panel_brand"=>"0",
        "panel_model"=>"1",
        "inverter_brand"=>"0",
        "inverter_model"=>"1",
        "mounting_size"=>"0",
        "mounting_roof"=>"1",
        "mounting_brand"=>"2",
        "install_size"=>"0",
        "install_roof"=>"1",
        "install_service"=>"2",
    );
    $title = $title_arr[$selection];
    $child = $child_arr[$selection];
    $field = $field_arr[$selection];
    $level = $level_arr[$selection];

    echo "<div class='ItemInputContainer' data-child='$child' data-current='$selection' data-field='$field' data-level='$level'>
           <p class='SelectLabel'>Select $title :</p>
           <p>";
    if(count($data) == 0)
        echo "You don't have any option to choose!";
    else
        foreach($data as $d)
            show_option($d, $selection);
    echo "
           </p>
           </div>
    ";
}


/**
 * @param $row      the item row (associated array)
 * @param $selection    the select group name, different select group has slightly different presentation
 */
function show_option($row, $selection)
{
    //Panel & Inverter
    if($selection == 'panel_brand' || $selection == 'inverter_brand')
        echo "<a class='ItemOption' data-value='".$row['item_brand']."'>".$row['item_brand']."</a>";
    else if($selection == 'panel_model' || $selection == 'inverter_model')
        echo "<a class='ItemOption' data-value='".$row['item_id']."'>".$row['item_name']."</a>";
    //Mounting
    else if($selection == 'mounting_size' || $selection == 'install_size')
        echo "<a class='ItemOption' data-value='".$row['item_size']."'>".number_format($row['item_size']/1000, 1, '.','')."kW</a>";
    else if($selection == 'mounting_roof' || $selection == 'install_roof')
        echo "<a class='ItemOption' data-value='".$row['item_roof_type']."'>".$row['item_roof_type']."</a>";
    else if($selection == 'mounting_brand')
        echo "<a class='ItemOption' data-value='".$row['item_id']."'>".$row['item_brand']."</a>";
    else if($selection == 'install_service')
        echo "<a class='ItemOption' data-value='".$row['item_id']."'>".$row['item_service']."</a>";
    else
        echo "<span>Strange!</span>";
}


/**
 * @param $item  the info array of an item
 */
function show_item($item)
{
    $fields = array('size', 'category', 'name','brand', 'id');
    $value = $item['item_name'];
    if(strtolower($item['item_category']) == 'installer')
        $value = $item['item_service']." $value";
    echo "<input type='hidden' class='ItemObj' value='$value' ";
    if($item != NULL)
        foreach($fields as $field)
            echo " data-$field='".$item['item_'.$field]."' ";
    else
        echo " data-price='0' size='0' ";
    echo "/>";
}


/**
 * Show a new inverter to be configured
 */
function show_inverter()
{
    echo "
    <div class='QuoteSectionContainer InverterContainer'>
        <h1>Inverter</h1>
        <div class='SingleItemContainer'>
        ";
        show_item_input_container("inverter_brand", get_options("inverter_brand", ""));
    echo "</div>
    </div>";
}


/**
 * @param array $items    the items included in a quote
 * @param string $environment   production|development
 * @return string summary result
 */
function show_item_summary($items, $environment='production')
{
    $total = 0; $production = false;
    $view = $iroof = $mroof = "";
    $psize = $isize = $msize = $ssize = $stc = 0;
    if($environment == 'production')
        $production = true;
    $colspan = ($production == true) ? 2 : 4;

    //1. Summary Table
    $view .= "<table class='QuoteSummaryTable'>
            <tr>
                <th>Category</th>
                <th>Item</th>
                <th>Quantity</th>";
    if(!$production)
        $view .= "
                <th class='Remove'>Price</th>
                <th class='Remove'>Subtotal</th>";
    $view .= "
            </tr>";
    foreach($items as $item)
    {
        //Data collection
        $psize += (strtolower($item['cat']) == 'panel') ? ($item['qty']*$item['size']) : 0;
        $msize += (strtolower($item['cat']) == 'mounting') ? ($item['qty']*$item['size']) : 0;
        $isize += (strtolower($item['cat']) == 'inverter') ? ($item['qty']*$item['size']) : 0;
        $ssize += (strtolower($item['cat']) == 'installer') ? ($item['qty']*$item['size']) : 0;
        //$stc += ($item['cat'] == 'Rebates') ? ($item['qty']) : 0;             Do not use client side stc, calculate again using postcode and size;
        if(strtolower($item['cat']) == 'mounting')
            $mroof = $item['roof'];
        if(strtolower($item['cat']) == 'installer')
            $iroof = $item['roof'];

        //Table row
        $view .= "<tr>
                <td>".$item['cat']."</td>
                <td>".$item['display']."</td>
                <td class='qty'>".$item['qty']."</td>";
        if(!$production)
            $view .= "
                <td class='Remove'>".$item['price']."</td>
                <td class='Subtotal Remove'>$".format_subtotal($item['subtotal'])."</td>";
        $view .= "
               </tr>";
        $total += $item['subtotal'];
    }

    $stc += get_stc(array("postcode"=>$_POST['postcode'], "size"=>$psize/1000));
    $vip_commission = $psize/20;
    $normal_commission = $psize/10;

    $raw                    = $total;                                          //Hide
    $vip                    = $raw*(1+get_config_value('markup_vip')/100);     //Hide
    $vip_with_commission    = $vip + $vip_commission;                          //Hide (New)
    $vip_gst                = $vip_with_commission*1.1;                        //Show
    $vip_finance            = $vip_gst*1.15;                                   //Show
    $normal                 = $raw*(1+get_config_value('markup_normal')/100);  //Hide
    $normal_with_commission = $normal + $normal_commission;                    //Hide (New)
    $normal_gst             = $normal_with_commission*1.1;                     //Show
    $normal_finance         = $normal_gst*1.15;                                //Show

    //Price summary
    //Raw, VIP, VIP+Commission
    if(!$production)
        $view .= "  <tr class='Remove'>
                    <th>Raw</th>
                    <th colspan='$colspan' class='Subtotal'>$".number_format($raw, 2)."</th></tr>
                <tr class='Remove'>
                    <th>VIP = Raw &times; (1+".get_config_value('markup_vip')."%)</th>
                    <th colspan='$colspan' class='Subtotal'>$".number_format($vip, 2)."</th>
                <tr class='Remove'>
                    <th>VIP &amp; Commission = VIP + $$vip_commission</th>
                    <th colspan='$colspan' class='Subtotal'>$".number_format($vip_with_commission, 2)."</th>
                </tr>";

    //VIP + GST
    $view .= "<tr class=''>
            <th>VIP (incl. GST)</th>
            <th colspan='$colspan' class='Subtotal'>$".number_format($vip_gst, 2)."</th></tr>";

    //VIP + GST + Finance
    $view .= "<tr class=''>
            <th>VIP Lombard Finance</th>
            <th colspan='$colspan' class='Subtotal'>$".number_format($vip_finance, 2)."</th></tr>";

    //Normal, Normal+Commission
    if(!$production)
        $view .= "<tr class='Remove'>
                <th>Normal = Raw &times; (1+".get_config_value('markup_normal')."%)</th>
                <th colspan='$colspan' class='Subtotal'>$".number_format($normal, 2)."</th></tr>
                <tr class='Remove'>
                <th>Normal &amp; Commission = Normal +$$normal_commission</th>
                <th colspan='$colspan' class='Subtotal'>$".number_format($normal_with_commission, 2)."</th></tr>";

    //Normal + GST
    $view .= "<tr class=''>
            <th>Normal (incl. GST)</th>
            <th colspan='$colspan' class='Subtotal'>$".number_format($normal_gst, 2)."</th></tr>";

    //Normal Price + GST + Finance
    $view .= "<tr class=''>
            <th>Normal Lombard Finance</th>
            <th colspan='$colspan' class='Subtotal'>$".number_format($normal_finance, 2)."</th></tr>";

    //Discount Price + GST
    $view .= "<tr class=''>
            <th>Discounted (incl. GST)</th>
            <th colspan='$colspan' class='Subtotal'>$<input class='DiscountPrice' type='text' value='".number_format($normal_gst, 2)."'/></th></tr>";
    $view .= "</table>";

    //2. Warning
    $warnings = array(); $warn = "";
        //1. Panel > Inverter
    if($psize > $isize)
        array_push($warnings, "Panel total $psize W > Inverter total $isize W");
        //2. Panel > Mounting
    if($psize > $msize)
        array_push($warnings, "Panel total $psize W > Mounting total $msize W");
        //3. Panel > Install
    if($psize > $ssize)
        array_push($warnings, "Panel total $psize W > Installer total $ssize W");
        //4. Panel = 0
    if($psize == 0)
        array_push($warnings, "Panel total size = 0 W");
        //5. Inverter = 0
    if($isize == 0)
        array_push($warnings, "Inverter total size = 0 W");
      //6. Mounting = 0
    if($msize == 0)
        array_push($warnings, "Mounting total size = 0 W");
       //7. Installer = 0
    if($ssize == 0)
        array_push($warnings, "Installer total size = 0 W");
      //8. STC double check
   // if(get_stc(array("postcode"=>$_POST['postcode'], "size"=>$psize/1000)) != $stc)
   //     array_push($warnings, "STC amount might be inconsistent, try <a href='https://www.rec-registry.gov.au/sguCalculatorInit.shtml' target='_blank'>STC Calculator</a>. <!-- ".$_POST['postcode']."|$psize|$stc -->");
      //9. Mounting and Install different roof
    if($iroof != "" && $mroof != "" && $iroof != $mroof)
        array_push($warnings, "Installer roof $iroof does not match mounting roof $mroof");

    if(count($warnings) != 0)
    {
        $warn = "<div class='SummaryWarning'><h5>Warning</h5>";
        foreach($warnings as $w)
            $warn .= "<p>$w</p>";
        $warn .= "</div>";
    }

    //3. Save Summary
    $save = "
        <p><button class='SaveQuote Hidden'>Save Quote</button></p>
        <p class='SaveQuote Hidden'>Before save the quote, check the summary carefully.</p>
    ";

    //Negative price does not make any sense
    if($total > 0)
        return $view.$warn.$save;
    else
        return "<p>Are you sure the system size to calculate STC is correct?</p>";
}


/**
 * @param array $customers
 * @return string
 */
function show_customer_list($customers)
{
    $o = "<p class='Bold'>".count($customers)." customer(s) found.</p>";
    if(count($customers) != 0)
        foreach($customers as $c)
        {
            //Customer Name
            //Customer No
            //Customer Address
            $name = $c['firstname']." ".$c['lastname'];
            $conno = $c['contact_no'];
            $id = $c['crmid'];
            $address = $c['mailingstreet'].", ".$c['mailingcity'].", ".$c['mailingstate']." ".$c['mailingzip'];
            $postcode = ($c['mailingzip'] >= 0 && $c['mailingzip'] < 10000) ? $c['mailingzip'] : "";
            $o .= "<div class='CustomerEntry'>
            <p class='CustomerCon'>$conno</p>
            <p class='CustomerName'>$name</p>
            <p class='CustomerAdd'>$address</p>
            <input type='hidden' class='CustomerPostcode' value='$postcode' />
            <input type='hidden' class='CustomerId' value='$id' />
            </div>
            ";

        }

    sleep(1);

    return $o;
}


/**
 * @param int $sub
 * @return string
 */
function format_subtotal($sub)
{
    return number_format($sub, 2, '.', ',');
}

?>