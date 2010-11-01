<?php

/**
 * Saves a new user in the CEEN API.
 */

$resource = array(
  'first_name' => 'Mary27',
  'last_name' => 'LastNow27',
  'bio' => 'This is a test using the example and only changing the $resource fields.',
  'contact' => array(
    'mail' => 'mary74@maryedith.com',
  ),
);
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = 'a545766537012063cce4aafef3e137f2';
$private_key = 'e4c746388aeceed2338474a56438bc7e';
// brandons':
//$public_key = '9665857d322055b074a977fe44686d47';
//$private_key = '7352d97ef533d567e55c83f99b3c5cac';
$nonce = uniqid(mt_rand());
$timestamp = time() + (60 * 60 * 4);
$resource_name = 'user_resource.create';

$hash_parameters = array($timestamp, $public_key, $nonce);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$ch = curl_init();

$ceen_posturl = sprintf($ceen_location . 'user.php?hash=%s&timestamp=%d&public_key=%s&nonce=%s', $hash, $timestamp, $public_key, $nonce);

print $ceen_posturl;

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_posturl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, serialize($resource));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-type: application/vnd.php.serialized',
  'Accept: application/vnd.php.serialized',
));

// grab URL and pass it to the browser
//$postinfo = unserialize(curl_exec($ch));
$postinfo = curl_exec($ch);
$post = unserialize( $postinfo );

// close cURL resource, and free up system resources
curl_close($ch);
print "\n\nthe result: \n\n";
print "<pre>";
print_r($postinfo);
print_r("<br><br>the ser result:<br><br>");
print_r($post);
print "</pre>";

?>