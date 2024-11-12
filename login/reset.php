<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require '../database/connection.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["email"]) && isset($_GET["token"])) {
    $email = $_GET["email"];
    $token = $_GET["token"];

    // Check if user exists and token is valid
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Email = ? AND ResetToken = ?");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 1) {
        // User exists and token is valid, show password reset form
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
        </head>
        <body>
            <h2>Reset Password</h2>
            <form action="update_password.php" method="post">
                <input type="hidden" name="email" value="<?php echo $email; ?>">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <label for="password">Enter your new password:</label><br>
                <input type="password" id="password" name="password" required><br><br>
                <button type="submit">Reset Password</button>
            </form>
        </body>
        </html>

        <?php
    } else {
        echo "Invalid token or email.";
    }
} else {
    echo "Invalid request.";
}

?>
