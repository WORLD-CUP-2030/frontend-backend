<?php
// Start or resume a session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worldcup";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $countryToUpdate = $_POST["country_to_update"];

    if (isset($_POST["update_team"])) {
        // Fetch existing team information
        $teamQuery = "SELECT * FROM team WHERE Country = '$countryToUpdate'";
        $teamResult = $conn->query($teamQuery);

        if ($teamRow = $teamResult->fetch_assoc()) {
            echo "<form method='POST' action='{$_SERVER["PHP_SELF"]}'>
                    <input type='hidden' name='country_to_update' value='$countryToUpdate'>
                    <label for='new_country'>New Country:</label>
                    <input type='text' name='new_country' value='$countryToUpdate' required>
                    <label for='new_continent'>New Continent:</label>
                    <input type='text' name='new_continent' value='{$teamRow["Continent"]}' required>
                    <label for='new_capital'>New Capital:</label>
                    <input type='text' name='new_capital' value='{$teamRow["Capital"]}' required>
                    <button type='submit' name='confirm_update'>Confirm Update</button>
                  </form>";

        } else {
            echo "Error fetching team information: " . mysqli_error($conn);
        }
    } elseif (isset($_POST["confirm_update"])) {
        $newCountry = $_POST["new_country"];
        $newContinent = $_POST["new_continent"];
        $newCapital = $_POST["new_capital"];

        $sqlUpdate = "UPDATE team SET Country = '$newCountry', Continent = '$newContinent', Capital = '$newCapital' WHERE Country = '$countryToUpdate'";
        $resUpdate = mysqli_query($conn, $sqlUpdate);

        if ($resUpdate) {
            echo "Team information updated successfully!";
        } else {
            echo "Error updating team information: " . mysqli_error($conn);
        }
    }
}
?>