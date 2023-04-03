<?php 
// Set up the cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:3000/api/upload');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Set the POST fields
$postData = [
  'name' => $_POST['name'],
  'email' => $_POST['email'],
  'file' => new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']),
];
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

// Send the request and get the response
$response = curl_exec($ch);

// Close the cURL request
curl_close($ch);

// Handle the response from the server
$responseData = json_decode($response, true);
if ($responseData['success']) {
  echo 'Form submitted successfully!';
} else {
  echo 'Error submitting form: ' . $responseData['message'];
}


?>