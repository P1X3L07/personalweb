<?php
// Database connection 
$servername = "localhost";
$user = "root";
$pass = ""; //diisi sesuai password
$dbname = "contact_pw";

$conn = new mysqli($servername, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $sql = "INSERT INTO contactlist (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message already saved! We will contact you soon!');</script>";
        header('Refresh: 1; url=http://localhost/personalweb/Contact.html', true, 303);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Save the form data as JSON if the "save_as_json" parameter is set to "true"
    if (isset($_POST["save_as_json"]) && $_POST["save_as_json"] == "true") {
        // Create an array with the form data
        $formData = array(
          'name' => $name,
          'email' => $email,
          'message' => $message,
          'timestamp' => date('Y-m-d H:i:s')
        );

        // Load existing JSON data from a file
        $jsonData = file_get_contents('message.json');
        $data = json_decode($jsonData, true);

        // Add the new form data to the array
        $data['messages'][] = $formData;

        // Save the updated data as JSON
        $jsonData = json_encode($data);
        file_put_contents('message.json', $jsonData);
    }

    $conn->close();
}

error_reporting(E_ALL);
ini_set('display_errors', '1');
?>