<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    http_response_code(301);
    header("Location: /");
} else {
    $name = "collabore link";
    $url = "clb.re";
    $version = "12272022";
    $char_per_id = 8;
    $char_per_deleteId = 24;
    $mysql_address = "localhost";
    $mysql_port = "3306";
    $mysql_db = "";
    $mysql_table = "";
    $mysql_username = "";
    $mysql_password = "";
}
?>