<?php
include_once('quote_config.php');
include_once('quote_view_list.php');
include_once('quote_model.php');
//Only authorised user is allowed to access admin page
//Intruder will not be let in
if(!in_array($_COOKIE['ck_login_id_vtiger'], $allowed))
    die("Forbidden City");
?>
<script src="modules/QuotePrice/js/waypoints.min.js"></script>
<script src="modules/QuotePrice/js/waypoints-sticky.min.js"></script>
<script src="modules/QuotePrice/js/qp.js"></script>
<div id='QuoteAdmin'>
    <div id='QuoteLeftContainer'>
        <div id='QuoteNewContainer'>
            <h1>New/Edit Item</h1>
            <?php
            $ItemFields = array(
                                "Category"=>"item_category",
                                "Name"=>"item_name",
                                "Price"=>"item_price",
                                "Brand (Optional)"=>"item_brand",
                                "Size (watt) (Optional)"=>"item_size",
                                "Roof Material (Optional)"=>"item_roof_type",
                                "Service Level (Optional, Standard or Premium)"=>"item_service",
            );
            $ItemFieldsHidden = array(
                                "QuotePrice"=>"module",
                                "index"=>"action",
                                "quote_admin_ajax"=>"page",
            );
            foreach($ItemFields as $key=>$val)
            {
                echo "
                    <div class='ItemFieldContainer'>
                        <p><label for='$val'>$key</label></p>
                        <p><input type='text' autocomplete='off' id='$val' name='$val' class='ItemFieldInput'/> </p>
                        <p class='ItemFieldWarning'></p>
                    </div>
                ";
            }
            ?>
            <input type='hidden' id='item_id' name='item_id' class='ItemFieldInput'/>
            <?php
                foreach($ItemFieldsHidden as $key=>$val)
                    echo "<input type='hidden' id='$val' name='$val' class='ItemFieldInput' value='$key'/>";
            ?>
            <p class='QuoteButtons'>
                <button class='Reset'>Reset</button>
                <button class='ItemCreate ItemAjax' data-crud='create'>New</button>
                <button class='ItemUpdate Hidden ItemAjax' data-crud='update'>Update</button>
            </p>
        </div>
    </div>

    <div id='QuoteRightContainer'>
        <h1>Item List</h1>

        <div class='ComponentList'>
            <h2>Panels</h2>
            <div class='ReloadArea' data-cat='panel' id='reloadpanel'>
                <?php show_item_list(get_item_list('panel'), $panel_thds); ?>
            </div>
        </div>

        <div class='ComponentList'>
            <h2>Inverters</h2>
            <div class='ReloadArea' data-cat='inverter' id='reloadinverter'>
                <?php show_item_list(get_item_list('inverter'), $inverter_thds); ?>
            </div>
        </div>

        <div class='ComponentList'>
            <h2>Mounting Kits</h2>
            <div class='ReloadArea' data-cat='mounting' id='reloadmounting'>
                <?php show_item_list(get_item_list('mounting'), $mounting_thds); ?>
            </div>
        </div>

        <div class='ComponentList'>
            <h2>Install Cost</h2>
            <div class='ReloadArea' data-cat='installer' id='reloadinstaller'>
                <?php show_item_list(get_item_list('installer'), $installer_thds); ?>
            </div>
        </div>

    </div>
<?php


?>

</div>