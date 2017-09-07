$(document).ready(function() {
    var item_data;

    //One-line-listeners
    $('.Increase').live('click', function(){var iq = $(this).parent().find('.ItemQty'); iq.val(parseInt(iq.val(),10)+1); iq.trigger("keyup");})
    $('.Decrease').live('click', function(){var iq = $(this).parent().find('.ItemQty'); iq.val(parseInt(iq.val(),10)-1); iq.trigger("keyup");})
//    $('.UseSuggestion').live('click', function(){$(this).toggleClass('Selected'); $(this).parent().parent().find('.ItemQty').toggleClass('UseSystemSize');updateSystemSize();})
//    $('.STCUseSuggestion').live('click', function(){$(this).toggleClass('Selected'); $('#CalculatedSystemSize').toggleClass('UseSystemSize');updateSystemSize(); calculateStc();})
    $('.UseSuggestion').live('click', function(){$(this).addClass('Selected'); $(this).parent().parent().find('.ItemQty').addClass('UseSystemSize');updateSystemSize();})
    $('.STCUseSuggestion').live('click', function(){$(this).addClass('Selected'); $('#CalculatedSystemSize').addClass('UseSystemSize');updateSystemSize(); calculateStc();})
    $('#Postcode, #CalculatedSystemSize').live('focusout', function(){calculateStc();});
    $('.Summarize').live('click', function(){getSelected($(this));});
    //Pre-click
    $('.UseSuggestion').trigger('click');
    $('.STCUseSuggestion').trigger('click');

    //Listeners
    $('.NewInverter').live('click', function(){var thi = $(this);$.post("modules/QuotePrice/quote_main_controller.php",{preventCache: Math.random(),action: "new-inverter"},function(Response){thi.before(Response);},"html");});
    $('.NewExtra').live('click', function(){$(this).before("<div class='ItemQtyContainer'><p> <input type='text' class='ItemObj ExtraObj' data-price='1' value='' data-id='-1' />$<input type='text' class='ItemQty StcUnrelated'/></p></div>");})

    $('.ItemQty').live('keyup change', function(){
        if(parseInt($(this).val(), 10) < 0) {
            Debug("Invalid quantity");
        }
        else {
            updateSystemSize();
            //Only get STC if it's automated
            if($(this).hasClass('StcUnrelated') === false && $('#CalculatedSystemSize').hasClass('UseSystemSize') === true) {
                calculateStc();
            }
        }
    });

    /*
        0. Deselect
        1. Display more options
        2. Load the final option object
     */
    $('.ItemOption').live('click', function(){
        //Variables
        var opt = $(this);
        var select_group = $(this).parent().parent();           //ItemInputContainer
        var item_container = select_group.parent();             //SingleItemContainer
        var level = select_group.attr('data-level');
        var options = "preventCache="+Math.random()+"&selection="+select_group.attr('data-child');
        $("#Waiting").remove();

        // 0. Deselect
        if($(this).hasClass('Selected')) {
            //Remove higher level selections and generate query
            item_container.find('.ItemObj').remove();
            item_container.find('.ItemInputContainer').each(function(){
                if($(this).attr('data-level') > level) {$(this).remove();}
            });
            //Deselect the option
            $(this).removeClass("Selected");
            updateSystemSize();
            calculateStc();
        }
        else {
            //Change Selection Status
            $(this).parent().find('.ItemOption').removeClass('Selected');
            $(this).addClass('Selected');
            select_group.after("<div id='Waiting'>loading...</div>");
            //1. Display more options
            if(select_group.attr('data-child') !== '') {
                item_container.find('.ItemObj').remove();
                item_container.find('.ItemInputContainer').each(function(){
                    //Remove child select groups
                    if($(this).attr('data-level') > level) {$(this).remove();}
                    //Gather parent select group data for new query
                    else {options += "&"+$(this).attr('data-field')+"="+$(this).find('.ItemOption.Selected').attr('data-value');}
                });
                //Call the page
                $.post(
                    "modules/QuotePrice/quote_main_controller.php",
                    options+"&action=retrieve",
                    function(Response) {
                        $("#Waiting").remove();
                        select_group.after(Response);
                        updateSystemSize();
                        calculateStc();
                    },"html");
            }
            //2. Load the final option object
            else {
                item_container.find('.ItemQtyContainer:not(.DoNotRemove)').remove();
                var id = opt.attr('data-value');
                $.post(
                    "modules/QuotePrice/quote_main_controller.php",
                    {
                        item_id: id, preventCache: Math.random(), action: "get-cost"},
                        function(Response){
                        $("#Waiting").remove();
                        //Panel & Inverter
                        if(item_container.find('.ItemQtyContainer').length === 0 && select_group.attr('data-current') !== 'install_service') {
                            select_group.after("<div class='ItemQtyContainer ItemInputContainer' data-level='5'><p class='SelectLabel'>Quantity: </p><p><a class='Decrease'>-</a><input type='number' value='1' class='ItemQty'/><a class='Increase'>+</a>"+Response+"</p> </div>");
                            updateSystemSize();
                            calculateStc();
                        }
                        //Installer's qty is always 1
                        else if(select_group.attr('data-current') === 'install_service') {
                            select_group.after("<div class='ItemQtyContainer ItemInputContainer' data-level='5'><p><input type='hidden' value='1' class='ItemQty' /> "+Response+"</p> </div>");
                        }
                        //Mounting
                        else
                        {
                            item_container.find('.ItemObj').remove();
                            item_container.find('.ItemQtyContainer').append(Response);
                        }
                    },
                    "html");


            }
        }
    });
    $('.CustomerSearch').live("click", function(){
        var but = $(this);
        $('.SaveQuote').hide();
        $('.SelectFlag').html("");
        var key = $('.CustomerName').val();
        if(key === "") {
        } else {
            $('.SearchResultContainer').remove();
            but.parent().after("<div id='Waiting'>loading...</div>");
            $.post(
                "modules/QuotePrice/quote_main_controller.php",
                {preventCache: Math.random(), key: key, action: "search-customer"},
                function(Response) {
                    $("#Waiting").remove();
                    but.parent().after("<div class='SearchResultContainer'>"+Response+"</div>");
                    $('.SearchResultContainer').jScrollPane();
                },
                "html"
            )
        }
    })
    $('.ToggleCustomerInfo').live("click", function(){
        $(this).parent().parent().toggleClass("MinimumDisplay");
        if($(this).text() === "show") {
            $(this).html("hide");
        } else {
            $(this).html("show");
        }
    });
    $('.CustomerEntry').live('click', function(){
        $(this).parent().find('.CustomerEntry').removeClass("SelectedCustomer");
        $(this).addClass("SelectedCustomer");
        $('.SelectFlag').html('selected');
        $("#Postcode").val($(this).find('.CustomerPostcode').val());
        calculateStc();
    })
    $('button.SaveQuote').live('click', function(){
        var button_container = $(this).parent();
        button_container.html("<div id='Waiting'>Saving...</div> ");
        //Data for quote....
        //Discount price by sales
        var discounted = $('.DiscountPrice').val();
        var crmid = $('.SelectedCustomer').find('.CustomerId').val();
        if($('.SelectedCustomer').length !== 0) {
            $.post(
                "modules/QuotePrice/quote_main_controller.php",
                {preventCache: Math.random(), postcode: $("#Postcode").val(), data: item_data, discounted: discounted, crmid: crmid, action: 'save-quote'}, function(Response){
                    button_container.html(Response);
            },"html");
        } else {
            Debug("No customer has been selected.");
        }

    })




    //--------------------------------------functions--------------------------------------------------------//
    /*
        Gather all the product information
     */
    function getSelected(button) {
        var collected = [];
        $('.QuoteSummary').html("<div id='Waiting'>loading...</div> ");
        $('.ItemQtyContainer').each(function(){
            var qty = $(this).find('.ItemQty').val();
            var obj = $(this).find('.ItemObj').val();
            var id = $(this).find('.ItemObj').attr('data-id');
            if(qty !== undefined && obj != undefined && qty > 0){
                //$('.QuoteSummary').append('<p>'+id+'|'+obj+' &times; '+qty+'</p>');
                collected.push({id: id, displayName: obj, qty:qty});
            }
        });
        //Save collected data to item_data for future usage
        item_data = collected;
        //Send to office
        $.post("modules/QuotePrice/quote_main_controller.php",{preventCache: Math.random(), postcode: $("#Postcode").val(), data: collected, action: 'calculate', view: button.attr('data-view')}, function(Response){
            $('.QuoteSummary').html(Response).hide().slideDown(500);
            if($('.SelectedCustomer').length === 1) {
                $('.SaveQuote').show();
            }

        },"html");
     }
    /*
        Calculate suggested system size
     */
    function updateSystemSize() {
        //Panel
        var panelsum = 0;
        $('.PanelContainer .ItemQtyContainer').each(function(){
            var size = $(this).find('.ItemObj').attr('data-size');
            var qty = $(this).find('.ItemQty').val();
            panelsum += size*qty;
        })
        //Inverter
        //        var invertersum = 0;
        //        $('.InverterContainer .ItemQtyContainer').each(function(){
        //            var size = $(this).find('.ItemObj').attr('data-size');
        //            var qty = $(this).find('.ItemQty').val();
        //            invertersum += size*qty;
        //        })
        //Compare and update
        var sugsize = panelsum;

        sugsize = (sugsize/1000).toFixed(2);
        $('.SystemSize').html(sugsize+"kW");
        $('.UseSystemSize').val(sugsize);

    }
    /*
        Get STC
     */
    function calculateStc() {
        var postcode = $("#Postcode").val();
        var size = $("#CalculatedSystemSize").val();
        if(parseInt(postcode, 10) > 0 && (parseInt(postcode, 10) < 10000) && size > 0) {
            $.post(
                "modules/QuotePrice/quote_main_controller.php",
                {preventCache: Math.random(), action: "get-stc", postcode: postcode, size: size},
                function(Response){$("#STCQty").html(Response); $(".STCQty").val(Response);},
                "html"
            );
        }
        else {
            $('#STCQty').html("0");
            $('.STCQty').val("0");
        }
    }
    /*
        Debug
     */
    function Debug(str) {
        $('body').append('<div class="Debug" style="position: fixed; bottom: 0; background: #CCCCCC; padding: 10px; width: 100%;">'+str+'</div>');
        $('.Debug').fadeOut(6000);
    }
});