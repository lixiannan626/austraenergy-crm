<?php
/*
 * Predefined variables
 */
$base_url = "index.php?module=QuotePrice&parenttab=Sales&action=index&page=";
//Authorised personal
$allowed = array(1, 5);
$item_id_field = 'item_id';

$panel_thds = array("Name"=>"item_name", "Brand"=>"item_brand", "Size (watt)"=>"item_size", "Price ($)"=>"item_price");
$inverter_thds = array("Name"=>"item_name", "Brand"=>"item_brand", "Size (watt)"=>"item_size", "Price ($)"=>"item_price");
$mounting_thds = array_merge($panel_thds, array("Roof"=>"item_roof_type"));
$installer_thds = array("Name"=>"item_name", "Size (watt)"=>"item_size", "Price ($)"=>"item_price", "Roof"=>"item_roof_type", "Service"=>"item_service");

$thds_arr = array('panel'=>$panel_thds, 'inverter'=>$inverter_thds, 'mounting'=>$mounting_thds, 'installer'=>$installer_thds);

?>