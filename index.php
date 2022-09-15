<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
use JasonGrimes\Paginator;
require __DIR__ . '/config.php';
require __DIR__ . '/service.php';
require __DIR__ . '/vendor/autoload.php';

$data = array();
$accepted_keys = array('county', 'country', 'town', 'num_bedrooms', 'price_min', 'price_max', 'page', 'type');


foreach ($_GET as $key => $value)
{
    if(in_array($key, $accepted_keys)) {
        $data[$key] = $value;
    }

}
unset($data['form_submit']);

$line = new service($host, $user_db, $pass, $db);
$county = $line->get_county($data);
$country = $line->get_country($data);
$town = $line->get_town($data);
$bedrooms = $line->get_bedrooms($data);
$price = $line->get_price($data);
$property_types = $line->get_property_types($data);

if (empty($_GET['id'])) {
    $propertys = $line->get_propertys($data);
} else {
    $property = $line->get_property($_GET['id']);
}

$totalItems = $propertys['count'] ?? 0;
$itemsPerPage = 90;
$currentPage = $data['page'] ?? 0;
unset($data['page']);
$urlPattern = '?' . http_build_query($data) . '&page=(:num)';
$paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);

include __DIR__ . '/templates/home.php';
