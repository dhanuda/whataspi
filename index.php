<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "whatsapp_api"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch departments
$sql = "SELECT id, dept_name, whatsapp_number FROM department";
$result = $conn->query($sql);
$departments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send WhatsApp Messages</title>
</head>
<body>

<h1>Select Departments to Send WhatsApp Messages</h1>
<form method="POST" action="send_whatsapp.php">
    <table border="1">
        <thead>
            <tr>
                <th>Select</th>
                <th>Department Name</th>
                <th>WhatsApp Number</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departments as $department): ?>
                <tr>
                    <td><input type="checkbox" name="whatsapp_numbers[]" value="<?= $department['whatsapp_number'] ?>"></td>
                    <td><?= htmlspecialchars($department['dept_name']) ?></td>
                    <td><?= htmlspecialchars($department['whatsapp_number']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <textarea name="message" rows="4" cols="50" placeholder="Enter your message here..."></textarea><br>
    <button type="submit">Send WhatsApp Messages</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define your API key
    $apiKey = 'c21c395abde469f857df723f6b8ae2a7264b4ad52ae7c843ddd24210db5fc64afb3445a442158190'; // Replace with your actual API key

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

</body>
</html>