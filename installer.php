<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set("allow_url_fopen", 1);

include("config.php");
require('service.php');

$service = new service($host, $user_db, $pass, $db);

$sql = "CREATE TABLE `propertys` (
  `id` int(11) NOT NULL,
  `uuid` varchar(36) NOT NULL,
  `property_type_id` int(5) NOT NULL,
  `county` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `town` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `image_full` varchar(255) NOT NULL,
  `image_thumbnail` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `num_bedrooms` tinyint(3) NOT NULL,
  `num_bathrooms` tinyint(3) NOT NULL,
  `price` int(9) NOT NULL,
  `type` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $sql1 = "
ALTER TABLE `propertys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `county` (`county`),
  ADD KEY `country` (`country`),
  ADD KEY `town` (`town`),
  ADD KEY `num_bedrooms` (`num_bedrooms`),
  ADD KEY `price` (`price`); ";

$sql2 = "
ALTER TABLE `propertys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";

$sql3 = "
CREATE TABLE `property_types` (
  `id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; ";

$sql4 = "
ALTER TABLE `property_types`
  ADD UNIQUE KEY `id` (`id`);
";

$service->query($sql);
$service->query($sql1);
$service->query($sql2);
$service->query($sql3);
$service->query($sql4);

$service->get_api();










