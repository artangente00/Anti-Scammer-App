<?php
// Verify CAPTCHA
$captcha_secret = 'YOUR_SECRET_KEY_HERE';
$response = $_POST['g-recaptcha-response'];

$verify_url = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => $captcha_secret,
    'response' => $response
];

$options = [
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($verify_url, false, $context);
$response_data = json_decode($result, true);

if ($response_data['success']) {
    // CAPTCHA verification passed, process form data
    // Perform form submission logic here
    echo "CAPTCHA verification passed!";
} else {
    // CAPTCHA verification failed
    echo "CAPTCHA verification failed!";
}
?>
