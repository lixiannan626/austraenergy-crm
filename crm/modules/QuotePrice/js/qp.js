$(document).ready(function() {
    //Auto-complete
    $('#item_category').autocomplete({source: ["Panel", "Inverter", "Mounting", "Installer"]});
    $('#item_service').autocomplete({source: ["Standard", "Premium"]});
    $('#item_roof_type').autocomplete({source: ["Tin", "Tile", "Tilt"]});
    $('#item_brand').autocomplete({source: ["Simax", "Winaico", "Hanover", "Growatt", "Delta Solivia", "APS", "Effekta", "Macsolar", "Antai", "Radiant"]});


    //Sticky
    $("#QuoteLeftContainer").waypoint('sticky');




    //Listeners
    $("#QuoteNewContainer .Reset").on('click', function(){ClearInput();});

    $('.ItemAjax').live('click', function(){
        var thi = $(this);
        var data = "preventCache="+Math.random()+"&crud="+thi.attr('data-crud');
        //Create & Update
        if(thi.attr('data-crud') === 'create' || thi.attr('data-crud') === 'update') {
            //Validate
            if(ItemValidate() === true)
            {
                //Gather data
                $(".ItemFieldInput").each(function(){data += "&"+$(this).attr("id")+"="+encodeURIComponent($(this).val());})
                $.post(
                    "modules/QuotePrice/quote_admin_ajax.php",
                    data,
                    function(Response)
                    {
                        if(Response.code === 200) {
                            //Reload
                            Reload($('#reload'+$('#item_category').val().toLowerCase()));
                            ClearInput();
                        }
                        Debug(Response.msg);
                    },
                    "json"
                )

            }
            else
            {
                Debug(ItemValidate());
            }
        }
        //Retrieve
        if(thi.attr('data-crud') === 'retrieve') {
            data += "&item_id="+thi.attr("data-id");
            $.post(
                "modules/QuotePrice/quote_admin_ajax.php",
                data,
                function(Response)
                {
                    if(Response.code === 200)
                        DisplayRetrieved(Response.msg);
                    else
                        Debug(Response.msg);
                },
                "json"
            )
        }
        //Delete
        if(thi.attr('data-crud') === 'delete') {
            data += "&item_id="+thi.attr("data-id");
            var roottr = thi.parent().parent();
            $('<div></div>').appendTo('body')
                .html('<div style="z-index: 9000;"><h6>Delete '+thi.parent().parent().find('td:first-child').text()+'?</h6></div>')
                .dialog({
                    modal: true, title: 'You are going to:', zIndex: 10000, autoOpen: true,
                    width:400, resizable: false,
                    buttons: {
                        'Do It': function () {
                            $.post("modules/QuotePrice/quote_admin_ajax.php",data,function(Response){Debug(Response.msg); ReloadRow(roottr);},"json");
                            $(this).dialog("close");

                            $(this).dialog("close");
                        },
                        Cancel: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });

        }
        //Update Availability
        if(thi.attr('data-crud') === 'update_ava') {
            data += "&item_id="+thi.attr("data-id");
            var roottr = thi.parent().parent();
            $.post(
                "modules/QuotePrice/quote_admin_ajax.php",
                data,
                function(Response) {
                    Debug(Response.msg);
                    ReloadRow(roottr);
                },
                "json"
            )


        }
    })








    //Functions
    function ItemValidate() {
        var valid = true;
        //Cat OK
        var cat = $.trim($("#item_category").val()).toLowerCase();
        if(cat === "" || $.inArray(cat, ["panel", "inverter", "mounting", "installer"]) === -1)
            return "Category is incorrect";
        //Name not empty
        var name = $.trim($("#item_name").val());
        if(name === "")
            return "Name is empty";

        return valid;
    }

    function ClearInput() {
        $('#QuoteNewContainer .ItemFieldInput').val("");
        //Buttons
        $(".ItemUpdate").hide();
        $(".ItemCreate").show();
        //Focus
        $("#item_category").focus();
    }

    function Debug(str) {
        $('body').append('<div class="Debug" style="position: fixed; bottom: 0; background: #CCCCCC; padding: 10px; width: 100%;">'+str+'</div>');
        $('.Debug').fadeOut(6000);
    }

    function DisplayRetrieved(data) {
        //Values
        $.each(data, function(key, value) {
            $("#QuoteNewContainer #"+key).val(value);
        });
        //Buttons
        $(".ItemUpdate").show();
        $(".ItemCreate").hide();
    }

    function Reload(ReloadArea) {
        //Cat OK
        var cat = ReloadArea.attr('data-cat').toLowerCase();
        if(cat === "" || $.inArray(cat, ["panel", "inverter", "mounting", "installer"]) === -1) {
        }
        else {
            $.post("modules/QuotePrice/quote_admin_ajax.php", {preventCache: Math.random(), crud: "reload", category: cat},function(Response){ReloadArea.html(Response);},"html");
        }
    }
    function ReloadRow(ReloadRow) {
        var cat = ReloadRow.attr('data-cat').toLowerCase();
        var id = ReloadRow.attr('data-id');
        $.post("modules/QuotePrice/quote_admin_ajax.php", {preventCache: Math.random(), crud: "reload_row", category: cat, item_id: id}, function(Response){ReloadRow.after(Response).remove();},"html");
    }

});