<?php
require '../database/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form submission
    $title = $_POST['title'];
    $paste = $_POST['paste'];
    $password = $_POST['password'];

    $randomLink = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);

    // Insert paste into database
    $sql = "INSERT INTO UserPastes (AccountID, PastePassword, PasteTitle, PasteText, RandomLink) VALUES (1, ?, ?, ?, ?)";
    $stmt = $conn2->prepare($sql);
    $stmt->bind_param("ssss", $password, $title, $paste, $randomLink);
    $stmt->execute();
    $stmt->close();

    echo "Paste created successfully! Random Link: <a href='$randomLink'>$randomLink</a>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Paste</title>
</head>
<body>
    <h2>Create a Paste</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Title: <input type="text" name="title"><br><br>
        Paste: <textarea name="paste"></textarea><br><br>
        Password (optional): <input type="password" name="password"><br><br>
        <input type="submit" value="Create Paste">
    </form>
</body>
</html>
