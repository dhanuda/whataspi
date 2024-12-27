<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define your API key
    $apiKey = 'c21c395abde469f857df723f6b8ae2a7264b4ad52ae7c843ddd24210db5fc64afb3445a442158190'; 
    // Replace with your actual API key

    // Get selected phone numbers and message
    $whatsappNumbers = $_POST['whatsapp_numbers'] ?? [];
    $message = $_POST['message'] ?? '';

    foreach ($whatsappNumbers as $phone) {
        // Initialize cURL
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.wassenger.com/v1/messages', // API endpoint
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'phone' => $phone,
                'message' => $message
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Token: ' . $apiKey // Add your API key here
            ],
        ]);

        // Execute the request
        $response = curl_exec($curl);
        $err = curl_error($curl);

        // Close cURL
        curl_close($curl);

        // Check for errors and display the response
        if ($err) {
            echo "cURL Error #: " . $err . "<br>";
        } else {
            echo "Message sent to $phone: " . $response . "<br>";
        }
    }
}
?>