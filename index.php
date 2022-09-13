<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include("config.php");
require('service.php');

$line = new service($host, $user_db, $pass, $db);
$county = $line->get_county();
$bedrooms = $line->get_bedrooms();
$price = $line->get_price();
$property_types = $line->get_property_types();

$where = array();

if (isset($_GET['county'])) {
    $where['county'] = $_GET['county'];
}
if (isset($_GET['country'])) {
    $where['country'] = $_GET['country'];
}
if (isset($_GET['town'])) {
    $where['town'] = $_GET['town'];
}
if (isset($_GET['num_bedrooms'])) {
    $where['num_bedrooms'] = $_GET['num_bedrooms'];
}
if (isset($_GET['price'])) {
    $where['price'] = $_GET['price'];
}

if (empty($_GET['id'])) {
    $propertys = $line->get_propertys($where);
} else {
    $property = $line->get_property($_GET['id']);
}

include __DIR__ . '/templates/home.php';
