<?php
    include_once('quote_config.php');
    include_once('quote_main_model.php');
    if(!in_array($_COOKIE['ck_login_id_vtiger'], $allowed))
        die("Forbidden City");
?>

<div id='QuoteMainContainer'>
    <div class='QuoteSectionContainer MarkupStcContainer'>
        <h1>Markup and STC price</h1>
        <div class='SingleItemContainer'>
            <p><label>Markup for VIP price (%): </label><input type='text' value='<?php echo get_config_value('markup_vip'); ?>' class='MarkupStc' name='markup_vip'/></p>
            <p><label>Markup for Normal price (%): </label><input type='text' value='<?php echo get_config_value('markup_normal'); ?>' class='MarkupStc' name='markup_normal'/></p>
            <p><label>STC price ($): </label><input type='text' value='<?php echo get_config_value('stc_price'); ?>' class='MarkupStc' name='stc_price'/></p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.MarkupStc').live('focusout', function(){
            var thi = $(this);
            if(parseFloat(thi.val()) < 0) {
                Debug("value should not be negative or not digits");
            }
            else {
                $.post(
                        "modules/QuotePrice/quote_main_controller.php",
                        {action: "update-markup-stc", preventCache: Math.random(), field: thi.attr('name'), value: thi.val()},
                        function(Response) {
                            thi.after(Response);
                            thi.next().fadeOut(6000);
                        },
                        "html"
                );
            }
        })





        /*
            Debug
         */
        function Debug(str) {
            $('body').append('<div class="Debug" style="position: fixed; bottom: 0; background: #CCCCCC; padding: 10px; width: 100%;">'+str+'</div>');
            $('.Debug').fadeOut(6000);
        }
    });
</script>