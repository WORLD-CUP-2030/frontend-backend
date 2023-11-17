<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "worldcup";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$searchTerm = '';

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'update') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the updated information from the form
            $updatedTeamID =  $_GET['teamId'];
            $updatedCountry = $_POST['update_country'];
            $updatedContinent = $_POST['update_continent'];
            $updatedCapital = $_POST['update_capital'];

            // Update the row in the database
            $updateQuery = "UPDATE `team` 
                            SET `Country`='".$updatedCountry."',
                            `Continent`='".$updatedContinent."',
                            `Capital`='".$updatedCapital."'
                            WHERE `TeamID`='".$updatedTeamID."'";
            $updateResult = $conn->query($updateQuery);

            header('location:index.php');

            if ($updateResult) {
                echo "Team information updated successfully!";
            } else {
                echo "Error updating team information: " . $conn->error;
            }
        }
        $teamId = $_GET['teamId'];
        $selectQuery = "SELECT * FROM team WHERE TeamID = '$teamId'";
        $selectResult = $conn->query($selectQuery);
        if ($selectResult->num_rows > 0) {
            $teamToUpdate = $selectResult->fetch_assoc();
            // Display the form with current data
            echo "<form method='POST' action=''>
                    <label for='country'>Country:</label>
                    <input type='text' name='update_country' value='{$teamToUpdate['Country']}'><br>
                    <label for='continent'>Continent:</label>
                    <input type='text' class='text-red-600' name='update_continent' value='{$teamToUpdate['Continent']}'><br>
                    <label for='capital'>Capital:</label>
                    <input type='text' name='update_capital' value='{$teamToUpdate['Capital']}'><br>
                    <label for='main_player'>Main Player:</label>
                    <button type='submit' name='submit_update'>Submit Update</button>
                  </form>";
        }

    } elseif ($_GET['action'] == 'delete') {
        $teamId = $_GET['teamId'];
        $sqlDelete = "DELETE FROM team WHERE TeamID = '$teamId'";
        $resDelete = mysqli_query($conn, $sqlDelete);
        header('location:index.php');
    }

} else {
    // Query to POST group and corresponding stadium data
    if ($searchTerm !== '') {
        // If there is a search term, use it in the query
        $groupQuery = "SELECT groups.Name AS GroupName, groups.StadiumName, GROUP_CONCAT(stadium.StadiumName) AS StadiumNames
                    FROM groups
                    LEFT JOIN stadium ON groups.StadiumName = stadium.StadiumName
                    WHERE groups.Name LIKE '%$searchTerm%'
                    GROUP BY groups.Name";
    } else {
        // If there is no search term, show all groups
        $groupQuery = "SELECT groups.Name AS GroupName, groups.StadiumName, GROUP_CONCAT(stadium.StadiumName) AS StadiumNames
                    FROM groups
                    LEFT JOIN stadium ON groups.StadiumName = stadium.StadiumName
                    GROUP BY groups.Name";
    }

    $groupResult = $conn->query($groupQuery);
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
    <div class='flex justify-center items-center mb-10 -ml-20 '>
        <img src="cup.png" alt="" class='h-[40vh] w-72 -mr-20'>
        <h2 class='text-center text-red-500 font-bold text-3xl'>FIFA WORLD CUP MOROCCO 2030</h2>
        <img src="mor.png" alt="" class='h-28 w-28 rounded-full ml-4'>
    </div>
    <form method="POST" action="">
        <div class="flex items-center justify-center">
            <div
                class="flex items-center justify-between border-solid border-2 border-green-600 border-opacity-50 p-1 px-2 rounded-3xl my-2">
                <input class="placeholder:text-white text-gray-800 text-sm focus:outline-none w-40 h-3 pr-10"
                    type="text" name="search" id="search" placeholder="Search" value="<?php echo $searchTerm; ?>" />
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25" fill="none"
                    class="ml-2">
                    <g clip-path="url(#clip0_1215_2490)">
                        <path
                            d="M17.35 21.5L11.05 15.2C10.55 15.6 9.975 15.9167 9.325 16.15C8.675 16.3833 7.98333 16.5 7.25 16.5C5.43333 16.5 3.896 15.8707 2.638 14.612C1.38 13.3533 0.750667 11.816 0.75 10C0.75 8.18333 1.37933 6.646 2.638 5.388C3.89667 4.13 5.434 3.50067 7.25 3.5C9.06667 3.5 10.604 4.12933 11.862 5.388C13.12 6.64667 13.7493 8.184 13.75 10C13.75 10.7333 13.6333 11.425 13.4 12.075C13.1667 12.725 12.85 13.3 12.45 13.8L18.75 20.1L17.35 21.5ZM7.25 14.5C8.5 14.5 9.56267 14.0623 10.438 13.187C11.3133 12.3117 11.7507 11.2493 11.75 10C11.75 8.75 11.3123 7.68733 10.437 6.812C9.56167 5.93667 8.49933 5.49933 7.25 5.5C6 5.5 4.93733 5.93767 4.062 6.813C3.18667 7.68833 2.74933 8.75067 2.75 10C2.75 11.25 3.18767 12.3127 4.063 13.188C4.93833 14.0633 6.00067 14.5007 7.25 14.5Z"
                            fill="white" />
                    </g>
                    <defs>
                        <clipPath id="clip0_1215_2490">
                            <rect width="24" height="24" fill="white" transform="translate(0.75 0.5)" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
        </div>
    </form>
    <?php
        $groupsName = [];
        $countGroupe = 0;
        if ($groupResult->num_rows > 0) {
            // Output data for each group
            echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10'>";
            $countGroupe = 0;

            while ($groupRow = $groupResult->fetch_assoc()) {
                array_push($groupsName, $groupRow["GroupName"]);
                echo "
            <div class='flex flex-col max-w-2xl border p-4'>
                <button class='border h-8 bg-green-600 pl-5 font-bold text-white text-sm text-start' onclick=\"window.dialog{$countGroupe}.showModal();\">Group {$groupRow["GroupName"]}:</button>
                <div class='flex flex-row gap-2 flex-wrap'>";
                $countGroupe++;
                $teamQuery = "SELECT Name, Country, drapeau FROM team WHERE Name = '" . $groupRow["GroupName"] . "'";
                $teamResult = $conn->query($teamQuery);

                while ($teamRow = $teamResult->fetch_assoc()) {
                    $country = trim($teamRow["Country"]);

                    echo "<button onclick=\"window.dialog.showModal();\" class='flex items-center  bg-gray-200 px-5 py-2 rounded my-0.5 text-start w-full max-w-2xl'>
                    <img src='" . $teamRow["drapeau"] . "' alt='' class='w-10 h-10 mr-2'>" . $country . "
                </button>";
                }
                echo "<p class='text-sm mt-2 font-semibold font-italic'> " . $groupRow["StadiumNames"] . "</p> ";
                echo "
                </div>
            </div>";
            }


            for ($i = 0; $i < count($groupsName); $i++) {
                echo "<dialog id='dialog{$i}' class='p-4 md:p-8 bg-white max-w-70vw pt-8 rounded-2xl border-0 shadow-md'>
            <button onclick=\"window.dialog{$i}.close();\" aria-label=\"close\" class='filter-grayscale border-none bg-none absolute top-5 right-5 transition-transform transition-filter ease duration-300 cursor-pointer transform-origin-center hover:filter-grayscale-0 hover:transform-scale-110'>❌</button>
            <h2>TEAM Information</h2>
            <table class='table-auto w-full'>
                <thead>
                    <tr>
                        <th class='border px-4 py-2'>Country</th>
                        <th class='border px-4 py-2'>Continent</th>
                        <th class='border px-4 py-2'>Capital</th>
                        <th class='border px-4 py-2'>Main Player</th>
                        <th class='border px-4 py-2   ' >Update</th>
                        <th class='border px-4 py-2'>Delete</th>
                    </tr>
                </thead>
                <tbody>";

                $teamQuery = "SELECT * FROM team WHERE Name = '" . $groupsName[$i] . "'";
                $teamResult = $conn->query($teamQuery);

                while ($teamRow = $teamResult->fetch_assoc()) {
                    echo "<tr>
                        <td class='border px-4 py-2'><img src='{$teamRow['drapeau']}' alt='Country Flag'></td>
                        <td class='border px-4 py-2'>{$teamRow['Continent']}</td>
                        <td class='border px-4 py-2'>{$teamRow['Capital']}</td>
                        <td class='border px-4 py-4'>player</td>
                        <td class='border px-4 py-4'>
                            <a href='./index2.php?action=update&teamId={$teamRow['TeamID']}'>Update</a>
                        </td>
                        <td class='border px-4 py-4'>
                        <a href='./index2.php?action=delete&teamId={$teamRow['TeamID']}'>Delete</a>
                        </td>
                    </tr>";
                }


                echo "</tbody></table></dialog>";
            }
            echo "</div>";
        } else {
            echo "0 results";
        }
}




$conn->close();
?>
</body>

</html>