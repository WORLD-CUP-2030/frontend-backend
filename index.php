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

$sql = "SELECT Name, GROUP_CONCAT(Country) AS Countries, Continent FROM team GROUP BY Name";
$result = $conn->query($sql);
$stadium = "SELECT * FROM stadium  ";
$results = $conn->query($stadium);


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
    <div class='flex justify-center items-center mb-10 '>
    <img src="cup.png" alt="" class='h-[40vh] w-72 -mr-20'>
    <h2 class='text-center text-red-500 font-bold text-3xl'>FIFA WORLD CUP Morocco 2030</h2>
    </div>
 

    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        echo "<div class='grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10'>";
        while ($row = $result->fetch_assoc()) {
            // Explode the "Countries" field into an array means split string to an array based on specific delimiter
            $countriesArray = explode(",", $row["Countries"]);

            echo "
            <div class='flex flex-col max-w-2xl border p-4'>
                <button class='border h-8 bg-green-600 pl-5 font-bold text-white text-sm' onclick=\"window.dialog.showModal();\">Group " . $row["Name"] . ":</button>

                <div class='flex flex-row gap-2 flex-wrap'>";
                foreach ($countriesArray as $country) {
                    echo "<button onclick=\"window.dialog.showModal();\" class='bg-gray-200 px-5 py-2 rounded my-0.5 text-start w-full max-w-2xl'>" . trim($country) . "</button>";
                }
                
            echo "
                </div>
            </div>";
        }
        echo "</div>";
    } else {
        echo "0 results";
    }

    echo "
    <dialog id='dialog' class='p-4 md:p-8 bg-white max-w-40vw pt-8 rounded-2xl border-0 shadow-md'>
        <button onclick=\"window.dialog.close();\" aria-label=\"close\" class='filter-grayscale border-none bg-none absolute top-5 right-5 transition-transform transition-filter ease duration-300 cursor-pointer transform-origin-center hover:filter-grayscale-0 hover:transform-scale-110'>❌</button>
        <h2>Stadium Information</h2>";

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        echo "
            <div>
                <h3>{$row['StadiumName']}</h3>
                <p>Location: {$row['City']}</p>

                <p>Capacity: {$row['Capacity']}</p>
            </div>";
    }
} else {
    echo "<p>No stadium data available.</p>";
}

echo "</dialog>";
    
    $conn->close();
    ?>

</body>
</html>
