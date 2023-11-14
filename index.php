<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worldcup";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// $sql = "SELECT * FROM team ";
// $result = $conn->query($sql);

$sql = "SELECT Name, GROUP_CONCAT(Country) AS Countries, Continent FROM team GROUP BY Name";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Teams</title>
</head>
<body>
    <h2>Teams Data</h2>

    <?php
    // Check if there are rows in the result
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>" . $row["Name"] . ":</strong> " . $row["Countries"] . " - " . $row["Continent"] . "</p>";
        }
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>
</body>
</html>
