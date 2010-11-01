<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_location . '/resource/b90e61e0-e5cf-11df-9b11-4040e8acc39d.php');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$resources = unserialize(curl_exec($ch));

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $resources );
print_r("</pre>");

