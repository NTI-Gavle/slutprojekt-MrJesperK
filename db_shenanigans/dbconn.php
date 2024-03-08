<?php
session_start();

$dbname = 'Onlyfans';
$hostname = 'localhost';
$DB_USER = 'root';
$DB_PASSWORD = '';

$options  = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'");

try {
    $dbconn = new PDO("mysql:host=$hostname;dbname=$dbname;", $DB_USER, 
    $DB_PASSWORD, $options);
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    // For debug purpose, shows all connection details
    echo 'Connection failed: '.$e->getMessage()."<br />";

}