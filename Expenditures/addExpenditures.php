<?php
// Database connection settings
$host = "localhost";
$username = "root";
$password = "";
$dbname = "Expenditures_App";

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve amount and other from the form, set defaults if empty
        $amount = !empty($_POST['amount']) ? floatval($_POST['amount']) : 0.0;
        $other = !empty($_POST['other']) ? floatval($_POST['other']) : 0.0;
        $id = 1;  // Fixed ID

        // Get today's date
        $today = date('Y-m-d');

        // Fetch current available amount
        $stmt = $conn->prepare("SELECT Available_Amount FROM Users WHERE Id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Fetch available amount
            $availableAmount = $result['Available_Amount'];

            // Subtract the sum of amount and other from the available amount
            $newAvailableAmount = $availableAmount - ($amount + $other);

            // Check if today's date exists in the Calculate table
            $stmt = $conn->prepare("SELECT * FROM Calculate WHERE Id = :id AND Date = :today");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingRecord) {
                // Update the existing record for today
                $UpdateFood = $amount + $existingRecord['Food'];
                $UpdateExtra = $other + $existingRecord['Extra'];
                
                $stmt = $conn->prepare("UPDATE Calculate SET Food = :food, Extra = :extra, Available_Amount = :availableAmount WHERE Id = :id AND Date = :today");
                $stmt->bindParam(':food', $UpdateFood);
                $stmt->bindParam(':extra', $UpdateExtra);
                $stmt->bindParam(':availableAmount', $newAvailableAmount);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':today', $today);
                $stmt->execute();
            } else {
                // If no record for today, insert a new one
                $stmt = $conn->prepare("INSERT INTO Calculate (Id, Food, Extra, Available_Amount, Date)
                                        VALUES (:id, :food, :extra, :availableAmount, :today)");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':food', $amount);
                $stmt->bindParam(':extra', $other);
                $stmt->bindParam(':availableAmount', $newAvailableAmount);
                $stmt->bindParam(':today', $today);
                $stmt->execute();
            }

            // Prepare the SQL for updating the Available_Amount in Users table
            $stmt = $conn->prepare("UPDATE Users SET Available_Amount = :newAvailableAmount WHERE Id = :id");
            $stmt->bindParam(':newAvailableAmount', $newAvailableAmount);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Optionally, redirect or provide feedback (e.g., alert message)
            
        } else {
            echo "<script>
            alert('User not found.');</script>";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expenditures</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cc6fad79da.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div id="div1" class="X1 bg-[#117779] p-4 h-screen flex flex-col items-center justify-center">
        <div class="border border-white p-6 rounded-lg max-w-lg w-full">
            <div class="flex justify-center gap-4 items-center text-center mb-6">
                <a href="index.php"><i class="text-white fa-solid fa-arrow-left text-2xl"></i></a>
                <h1 class="text_style text-[#e59700] text-center text-2xl">Add Expenditures</h1>
                <a href="addExpenditures.php"><i class="text-white text-2xl fa-solid fa-arrows-rotate"></i></a>
            </div>
            <h1 id="time" class=" text_style text-white text-2xl text-center">Time</h1>
            <p id="date" class="text_style text-white text-center">Date</p>
            <form method="POST" class="w-full">
                <label for="amount" id="greeting" class="text_style text-center text-white text-2xl">Amount</label>
                <input
                    id="amount"
                    name="amount"
                    type="number"
                    placeholder="Type here"
                    class="input input-bordered input-warning w-full bg-[#117779]" 
                    required 
                />
                <label for="Other" id="greeting" class="text_style text-center text-white text-2xl">Others</label>
                <input
                    id="Other"
                    name="other"
                    type="number"
                    placeholder="Type here"
                    class="input input-bordered input-warning w-full bg-[#117779]" 
                    required 
                />
                <div class="w-full flex justify-center mt-5">
                    <button type="submit" class="text_style text-green-500 text-center text-2xl border-2 border-green-500 rounded-lg w-1/2 hover:bg-black/10">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function displayDateTime() {
            const date = new Date();

            // Format time in 24-hour format (HH:MM:SS)
            const time = date.toLocaleTimeString([], { hour12: false });
            document.getElementById("time").innerText = time;

            // Format date as Month Day, Year
            const formattedDate = date.toLocaleDateString();
            document.getElementById("date").innerText = formattedDate;
        }

        // Call the function once when the page loads
        displayDateTime();
        // Update the time every second (1000 milliseconds)
        setInterval(displayDateTime, 1000);

        function displayGreeting() {
            const date = new Date();
            const hours = date.getHours();

            let greeting;
            if (hours >= 5 && hours < 12) {
                greeting = "Breakfast";
            } else if (hours >= 12 && hours < 18) {
                greeting = "Lunch";
            } else {
                greeting = "Dinner";
            }
            document.getElementById("greeting").innerText = greeting;
        }
        // Call the function once when the page loads
        displayGreeting();
        
        // Update the greeting every second (1000 milliseconds)
        setInterval(displayGreeting, 1000);
        </script>
    
</body>
</html>