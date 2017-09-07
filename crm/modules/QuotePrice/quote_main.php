<?php
    include_once('quote_main_model.php');
    include_once('quote_main_views.php');
//In Case Customer Id is passed from Detail View Page
if(isset($_GET['record']))
    $customer = get_customer_with($_GET['record']);
?>
<script src="modules/QuotePrice/js/jquery.mousewheel.js"></script>
<script src="modules/QuotePrice/js/jquery.jscrollpane.min.js"></script>
<script src="modules/QuotePrice/js/qp_main.js"></script>
<div id='QuoteMainContainer'>

    <!-- Panel -->
    <div class='QuoteSectionContainer PanelContainer'>
        <h1>Panels</h1>
        <div class='SingleItemContainer'>
        <?php show_item_input_container("panel_brand", get_options("panel_brand", "")); ?>
        </div>
    </div>


    <!-- Inverter -->
    <div class='QuoteSectionContainer InverterContainer'>
        <h1>Inverter</h1>
        <div class='SingleItemContainer'>
        <?php show_item_input_container("inverter_brand", get_options("inverter_brand", "")); ?>
        </div>
    </div>
    <a class='NewInverter ExtraItem'>+ another inverter</a>


    <!-- Mounting -->
    <?php
    //Mounting is special, so ItemQtyContainer is not appended after the final selection
    ?>
    <div class='QuoteSectionContainer MountingContainer'>
        <h1>Mounting</h1>
        <div class='SingleItemContainer'>
            <p><a class='UseSuggestion'>Use suggested size <span class='SystemSize'></span></a></p>
            <div class='ItemQtyContainer DoNotRemove'>
<!--            <p><label>System Size: </label><input type='text' class='ItemQty'/>kW</p>  -->
                <input type='hidden' class='ItemQty'/>
            </div>
        <?php show_item_input_container("mounting_roof", get_options("mounting_roof", array("item_size"=>1000))); ?>
        </div>
    </div>


    <!-- Installer -->
    <div class='QuoteSectionContainer'>
        <h1>Installer</h1>
        <div class='SingleItemContainer'>
            <p class='Suggestion'>Suggested system size: <span class='SystemSize'></span></p>
        <?php show_item_input_container("install_size", get_options("install_size", "")); ?>
        </div>
    </div>

    <!--STC -->
    <div class='QuoteSectionContainer STCContainer'>
        <h1>Government Rebate</h1>
        <div class='SingleItemContainer'>
            <p><a class='STCUseSuggestion'>Use suggested size <span class='SystemSize'></span></a></p>
            <p><label>Postcode:</label><input type='text' id='Postcode'/></p>
<!--        <p><label>System Size:</label><input type='text' id='CalculatedSystemSize'/>kW</p> -->
            <input type='hidden' id='CalculatedSystemSize' value='0'/>
            <div class='ItemQtyContainer'>
                <p>
                <label>STC amount:</label>
                <span id='STCQty'></span>
                <input type='hidden' class='ItemQty STCQty'/>
                <input type='hidden' data-price='-26' class='ItemObj' data-id='-5' value='STC'/>
                </p>
            </div>
        </div>
    </div>

    <!-- Extra -->
    <div class='QuoteSectionContainer ExtraContainer'>
        <h1>Extra</h1>
        <div class='SingleItemContainer'>
            <!-- First Extra -->
            <div class='ItemQtyContainer'><p><input type='text' class='ItemObj ExtraObj' data-price='1' value='Travel cost' data-id='-1' />$<input type='text' class='ItemQty StcUnrelated'/></p></div>
            <div class='ItemQtyContainer'><p><input type='text' class='ItemObj ExtraObj' data-price='1' value='Double Storey' data-id='-1' />$<input type='text' class='ItemQty StcUnrelated'/></p></div>
            <div class='ItemQtyContainer'><p><input type='text' class='ItemObj ExtraObj' data-price='1' value='Split Array' data-id='-1' />$<input type='text' class='ItemQty StcUnrelated'/></p></div>
            <a class='ExtraItem NewExtra'>+ New Extra</a>
        </div>
    </div>

    <!-- Customer -->
    <div class='CustomerContainer QuoteSectionContainer MinimumDisplay'>
        <h1>Customer <span class='SelectFlag'></span><button class='ToggleCustomerInfo'>show</button></h1>
        <div class='CustomerInfo'>
            <p class='CustomerExplanation'>If you want to save the quote for the customer, complete this part FIRST, then get quote, after that Save Quote button will appear.</p>
            <p>Search with customer name: <input type='text' class='CustomerName'/><button class='CustomerSearch'>Search</button></p>
            <div class='SearchResultContainer'>
<?php
//In Case Customer Id is passed from Detail View Page
if(isset($_GET['record']))
    echo show_customer_list(array(get_customer_with($_GET['record'])));
?>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                if($('.CustomerEntry').length === 1) {
                    $('.CustomerEntry').trigger('click');
                }
            })
        </script>
    </div>

    <!-- Summary -->
    <div class='QuoteSummaryContainer'>
        <h1>Summary</h1>
        <p>
            <a class='Summarize' data-view='production'>Get Quote</a>
            <?php
            //Only when the user is authorised, can he see the admin quote button
            if(in_array($current_user->id, $allowed)) { ?>
                <a class='Summarize' data-view='development'>Get Admin Quote</a>
            <?php } ?>
        </p>
        <div class='QuoteSummary'>

        </div>
    </div>


</div>