<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include("../config.php");
require('../service.php');

$line = new service($host, $user_db, $pass, $db);

if(isset($_GET['county'])) {
$country = $line->get_country($_GET['county']);

    if (isset($country)) {
        print "<h3>country</h3><br><select id='country' name='country' class='form-control form-select form-select-lg mb-3 w-50'>
        <option value='0'>please choose</option>";
        foreach ($country as $key => $item) {
            print "<option value='".$item."'>" . $item . "</option>";
        }
        print "</select>";
    }

}

if(isset($_GET['country'])) {
    $town = $line->get_town($_GET['country']);

    if (isset($town)) {
        print "<h3>town</h3><br><select id='town' name='town' class='form-control form-select form-select-lg mb-3 w-50'>
        <option value='0'>please choose</option>";
        foreach ($town as $key => $item) {
            print "<option value='".$item."'>" . $item . "</option>";
        }
        print "</select>";
    }

}
