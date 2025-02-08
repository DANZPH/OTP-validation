<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email is set
    if (isset($_POST["email"])) {
        $email = $_POST["email"];

        // Database credentials
        $host = "sql104.infinityfree.com"; // Change this to your database host
        $dbname = "if0_36048499_db_user"; // Change this to your database name
        $usernameDB = "if0_36048499"; // Change this to your database username
        $passwordDB = "LokK4Hhvygq"; // Change this to your database password

        // Database connection
        $conn = new mysqli($host, $usernameDB, $passwordDB, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user exists in the database
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            // User exists, generate reset token and send email
            $resetToken = generateResetToken();

            $stmt = $conn->prepare("UPDATE Users SET ResetToken = ? WHERE Email = ?");
            $stmt->bind_param("ss", $resetToken, $email);
            $stmt->execute();
            $stmt->close();

            // Send reset link via email
            $result = sendResetEmail($email, $resetToken);

            if ($result === true) {
                echo "Reset link sent to your email.";
            } else {
                echo "Error sending reset link: " . $result;
            }
        } else {
            echo "Error: Email not found.";
        }

        $conn->close();
    } else {
        echo "Error: Email is required.";
    }
}

function generateResetToken() {
    // Generate a random reset token
    return bin2hex(random_bytes(32));
}

function sendResetEmail($email, $resetToken) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Your gmail
        $mail->Password = 'nrgtyaqgymoadryg'; // Your gmail app password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('kentdancel20@gmail.com'); // Your gmail
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Reset Password';
        $mail->Body = 'Click the link below to reset your password: <a href="https://dazx.xyz/reset.php?email=' . $email . '&token=' . $resetToken . '">Reset Password</a>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        return $mail->ErrorInfo;
    }
}

?>

