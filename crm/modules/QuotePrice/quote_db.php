<?php
    include_once('../../config.inc.php');
    $link = mysql_connect("localhost", $dbconfig['db_username'], $dbconfig['db_password']);
    mysql_select_db("goforsolar_crm");

?>