<!--
Author: Michael Bulmer
Comp 519 Assignment 4
Submission 25/12/2017

Connect to database and setup connection.
-->

<?php
$db_hostname = "mysql";
$db_database = "m7mb";
$db_username = "m7mb";
$db_password = "pass123";
$db_charset = "utf8mb4";
$dsn = "mysql:host=$db_hostname;dbname=$db_database;charset=$db_charset";


// Note ATTR_EMULATE_PREPARES set to true as false was producing errors.
$opt = array(
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => TRUE);

try {
    $pdo = new PDO($dsn,$db_username,$db_password, $opt);

} catch (PDOException $e) {
    exit("PDO Error: ".$e->getMessage()."<br>");
}
?>
