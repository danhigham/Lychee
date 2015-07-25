<?php

###
# @name			Configuration
# @author		Tobias Reich
# @copyright	2015 Tobias Reich
###

if(!defined('LYCHEE')) exit('Error: Direct access is not allowed!');

// ** Read MySQL service properties from _ENV['VCAP_SERVICES']
$services = json_decode($_ENV['VCAP_SERVICES'], true);
$service = $services['cleardb'][0];  // pick the first MySQL service

# Database configuration
$dbHost = $service['credentials']['hostname'] . ':' . $service['credentials']['port']; # Host of the database
$dbUser = $service['credentials']['username']; # Username of the database
$dbPassword = $service['credentials']['password']; # Password of the database
$dbName = $service['credentials']['name']; # Database name
$dbTablePrefix = ''; # Table prefix

?>
