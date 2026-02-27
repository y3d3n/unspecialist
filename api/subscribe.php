<?php

$email = $_POST['email'];

$api_key = "ejYZFJMRgM9mnDR5DI71BTZUgHabNcUK2CsSLc7DbHiboFB35PVatJkOyemGIPoZ";
$publication_id = "pub_0214baa3-3786-456c-a3c5-6f64e475879c";

$url = "https://api.beehiiv.com/v2/publications/$publication_id/subscriptions";

$data = [
  "email" => $email,
  "reactivate_existing" => true,
  "send_welcome_email" => true
];

$options = [
  "http" => [
    "header"  => "Content-type: application/json\r\n" .
                 "Authorization: Bearer $api_key\r\n",
    "method"  => "POST",
    "content" => json_encode($data)
  ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

echo $result;
?>