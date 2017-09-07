<?php
error_reporting(0);
include_once('quote_config.php');
include_once('quote_db.php');
include_once('quote_main_model.php');

//1. Read from db with id
$sales_id = $_GET['sid'];
$ss_id = $_GET['qid'];
$select_sql = "SELECT * FROM quote_snapshot WHERE ss_sales_id='$sales_id' AND ss_id='$ss_id'";
if(result_row($select_sql) != 1)
    $body = "something is not right.....";
//2. Show info
else
{
    $quote = result_first_row($select_sql);
    $sales = $quote['ss_sales_name'];
    $time = $quote['ss_time'];
    $sid = $quote['ss_id'];
    $discounted = stripslashes($quote['ss_discounted_price']);
    $customer_id = $quote['ss_customer_id'];
    $customer = get_customer_with($customer_id);
    $name = $customer['firstname']." ".$customer['lastname'];
    $body = "<div class='snapshotp'>".stripslashes($quote['ss_snapshot'])."</div>";
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <head>
        <title>Quote snapshot</title>
        <link rel="stylesheet" type="text/css" href="qp/stylesheets/single_view.css">
        <link REL="SHORTCUT ICON" HREF="../../themes/images/favicon48.png" type="image/png">
        <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1" />
        <link rel="apple-touch-icon-precomposed" href="../../themes/images/ios.png"/>
        <meta name="viewport" content="width=device-width; initial-scale=1.0;" />
        <script src="js/jquery-1.8.3.min.js"></script>
    </head>
    <body>
        <div class='snap-header'>
            <p>Quote Snapshot Q<?php echo str_pad((int) $sid,4,"0",STR_PAD_LEFT);?></p>
            <p>Sales: <?php echo $sales; ?></p>
            <p>Time: <?php echo $time;?></p>
            <p>Customer: <?php echo $name; ?></p>
        </div>
        <div class='snap-wrapper'>
        <?php echo $body; ?>
        </div>
        <div class='snap-header'>
            <?php if(in_array($_COOKIE['ck_login_id_vtiger'], $allowed) == true)
                    echo "<button class='ExecutiveSnapshot'>More Detailed Snapshot</button>";
            //Only when the user is authorised, can he see the detailed snapshot button and proceed
            ?>
        </div>

    <script>
        $('.DiscountPrice').val("<?php echo $discounted;?>");
        $('.ExecutiveSnapshot').live('click', function(){
            var thi = $(this).parent();
            $.post(
                    "quote_main_controller.php",
                    {sid: <?php echo $sales_id;?>, qid: <?php echo $ss_id;?>, preventCache: Math.random(), action: 'get-detailed-snapshot'},
                    function(Response){
                        $("#detailed-snapshot").remove();
                        thi.after("<div class='snap-wrapper' id='detailed-snapshot'>"+Response+"</div>");
                        $('.DiscountPrice').val("<?php echo $discounted;?>");
                    },
                    "html"
            )
        })
    </script>
    </body>

</html>