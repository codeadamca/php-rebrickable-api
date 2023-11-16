<?php

// Register for a Rebrickable account and palce your API key here
// https://rebrickable.com/api/

define('REBRICKABLE_API_KEY', <REBRICKABLE_API_KEY>);

$url = 'https://rebrickable.com/api/v3/lego/colors/';

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: key '.REBRICKABLE_API_KEY,
]);

$response = curl_exec($curl);
curl_close($curl);

$response = json_decode($response, true);

// echo '<pre>';
// print_r($response);
// echo '</pre>';
    
foreach($response['results'] as $colour)
{

    echo '<pre>';
    print_r($colour);
    echo '</pre>';

}

