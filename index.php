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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <h2>Teams Data</h2>

    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            // Explode the "Countries" field into an array
            $countriesArray = explode(",", $row["Countries"]);

            echo "
            <div class='flex flex-col'>
                <p><strong>" . $row["Name"] . ":</strong></p>

                <div class='flex flex-col'>";
            
            // Iterate over each country and display
            foreach ($countriesArray as $country) {
                echo "<p>" . trim($country) . "</p>";
            }

            echo "
                </div>
                <p><strong>Continent:</strong> " . $row["Continent"] . "</p>
            </div>";
        }
    } else {
        echo "0 results";
    }

    $conn->close();
    ?>

    <button class="text-red-400">See more</button>
</body>
</html>
