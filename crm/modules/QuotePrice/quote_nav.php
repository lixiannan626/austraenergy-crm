<?php
//Quote Navigation

//Default Quote Page
$nav_arr = array("Quote Page"=>"quote_main");

//Admin Area
$admin_arr = array(
    "Set Prices"=>"quote_admin",
    "Set Markup &amp; STC"=>"quote_markup_stc");

//If it's admin, then show the nav
if(in_array($current_user->id, $allowed))
    $nav_arr = array_merge($nav_arr, $admin_arr);


//view of navigation - level 3
echo "<div id='QuoteNav'><ul>";
foreach($nav_arr as $key=>$val)
{
    if($page_name != $val)
        echo "<li><a href='$base_url".$val."'>$key</a></li>";
    else
        echo "<li><a class='Current' href='$base_url".$val."'>$key</a></li>";
}
echo "</ul></div>";
?>