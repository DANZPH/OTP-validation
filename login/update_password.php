<?php
session_start();
require '../database/connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email, token, and password are set
    if (isset($_POST["email"]) && isset($_POST["token"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $token = $_POST["token"];
        $password = $_POST["password"];

        // Check if user exists and token is valid
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ? AND ResetToken = ?");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows == 1) {
            // User exists and token is valid, update password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE Users SET Password = ?, ResetToken = NULL WHERE Email = ?");
            $stmt->bind_param("ss", $hashedPassword, $email);
            $stmt->execute();
            $stmt->close();

            echo "Password updated successfully.";
        } else {
            echo "Invalid token or email.";
        }
    } else {
        echo "Error: Email, token, and password are required.";
    }
} else {
    echo "Invalid request.";
}

?>

