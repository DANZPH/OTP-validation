<?php
require '../database/connection.php'; // Use require instead of include if necessary

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['link'])) {
    $link = $_GET['link'];

    // Retrieve paste based on shortened random link
    $sql = "SELECT * FROM UserPastes WHERE RandomLink = ?";
    $stmt = $conn2->prepare($sql);
    $stmt->bind_param("s", $link);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pasteTitle = $row['PasteTitle'];
        $pasteText = $row['PasteText'];

        // Check if paste has a password
        if (!empty($row['PastePassword'])) {
            echo "<h2>This paste is password protected.</h2>";
            // Add form here to prompt for password and verify
        } else {
            echo "<h2>Paste Title: $pasteTitle</h2>";
            echo "<pre>$pasteText</pre>";
        }
    } else {
        echo "Paste not found!";
    }

    $stmt->close();
}
?>
