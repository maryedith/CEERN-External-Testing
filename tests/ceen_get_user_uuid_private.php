<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = 'a545766537012063cce4aafef3e137f2';
$private_key = 'e4c746388aeceed2338474a56438bc7e';
$nonce = uniqid(mt_rand());
$timestamp = time() + (60 * 60 * 4);
$resource_name = 'user_resource.private_info';

$hash_parameters = array($timestamp, $public_key, $nonce);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$ceen_posturl = sprintf($ceen_location.'user/44516d04-cd72-11df-a638-4040e8acc39d/private_info.php?hash=%s&public_key=%s&timestamp=%s&nonce=%s', $hash, $public_key, $timestamp, $nonce);

print $ceen_posturl;

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_posturl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-type: application/vnd.php.serialized',
  'Accept: application/vnd.php.serialized',
));

// grab URL and pass it to the browser
$data = unserialize(curl_exec($ch));

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $data );
print_r("</pre>");

//foreach ($users['users'] as $user) {
//  print "<p>" . $user['name'] . ' - ' . $user['uuid'] . "</p>\n";
//}

//with resource.php this comes out as an array with elements 'stats' and 'resources'
// without hte .php unserialized fails.  And can't find array in ln 28.