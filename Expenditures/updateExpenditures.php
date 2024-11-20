<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Expenditures_App";

    try {
        // Database connection
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Get the data from the form
        $id = 1; // Fixed user ID
        $foodAmount = isset($_POST['amount']) ? $_POST['amount'] : 0;
        $extraAmount = isset($_POST['other']) ? $_POST['other'] : 0;
        $selectedDate = $_POST['date'];
        $action = isset($_POST['action']) ? $_POST['action'] : 'add'; // Determine action (add/remove)

        // Validate date: ensure it is not in the future
        $currentDate = date('Y-m-d');
        if ($selectedDate > $currentDate) {
            echo "Error: Selected date cannot be in the future.";
            exit;
        }

        // Check if the date already exists in the database for the given ID
        $stmt = $conn->prepare("SELECT * FROM Calculate WHERE Id = :id AND Date = :date");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $selectedDate);
        $stmt->execute();
        $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

        // Logic for adding or removing food and extra amounts
        if ($existingRecord) {
            // Record exists, so update the record
            if ($action == 'add') {
                // Add the new amounts to the existing ones
                $newFoodAmount = $existingRecord['Food'] + $foodAmount;
                $newExtraAmount = $existingRecord['Extra'] + $extraAmount;
                $newAbiable = $existingRecord['Available_Amount'] - ($newFoodAmount + $newExtraAmount);
            } else if ($action == 'remove') {
                // Remove the amounts (ensure not to go negative)
                $newFoodAmount = max(0, $existingRecord['Food'] - $foodAmount);
                $newExtraAmount = max(0, $existingRecord['Extra'] - $extraAmount);
                $newAbiable = max(0, $existingRecord['Available_Amount'] + $newFoodAmount + $newExtraAmount);
            }

            // Update the record in the database
            $stmt = $conn->prepare("UPDATE Calculate SET Food = :food, Extra = :extra, Available_Amount = :availableAmount WHERE Id = :id AND Date = :date");
            $stmt->bindParam(':food', $newFoodAmount);
            $stmt->bindParam(':extra', $newExtraAmount);
            $stmt->bindParam(':availableAmount', $newAbiable); // Sum of Food + Extra
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':date', $selectedDate);
            $stmt->execute();
        } else {
            // If no record exists, create a new one
            $newFoodAmount = $foodAmount;
            $newExtraAmount = $extraAmount;

            $stmt = $conn->prepare("INSERT INTO Calculate (Id, Food, Extra, Available_Amount, Date) VALUES (:id, :food, :extra, :availableAmount, :date)");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':food', $newFoodAmount);
            $stmt->bindParam(':extra', $newExtraAmount);
            $stmt->bindParam(':availableAmount', $newFoodAmount + $newExtraAmount);
            $stmt->bindParam(':date', $selectedDate);
            $stmt->execute();
        }

        // Return the updated expenditures (Food + Extra) to the front end
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Expenditures</title>
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
                <a href="index.php"><i class="text-white fa-solid fa-arrow-left"></i></a>
                <h1 class="text_style text-[#e59700] text-center text-2xl ">Update Expenditures</h1>
                <a href="updateExpenditures.php"><i class="text-white text-2xl fa-solid fa-arrows-rotate"></i></a>
            </div>
            
            <form action="updateExpenditures.php" method="POST" class="w-full">
                <label class="text-white">Select a date:</label>
                <input
                        id="date"
                        type="date"
                        name="date"
                        class="input input-bordered input-warning w-full bg-[#117779] mb-5" 
                        required 
                />
                <h1 id="expenditures" class="text-white text-center border border-[#e59700] rounded-full ">$</h1>
                <label for="amount" class="text-white">Amount</label>
                <input
                    id="amount"
                    name="amount"
                    type="number"
                    placeholder="Type here"
                    class="input input-bordered input-warning w-full bg-[#117779]" 
                    required 
                />

                <label for="Other" class="text-white">Other</label>
                <input
                    id="Other"
                    name="other"
                    type="number"
                    placeholder="Type here"
                    class="input input-bordered input-warning w-full bg-[#117779]" 
                    required 
                />
                <div class="w-full flex gap-9 mt-5">
                    <button type="submit" class="text_style text-green-500 text-center text-2xl border-2 border-green-500 rounded-lg w-1/2 hover:bg-black/10">
                        Add
                    </button>
                    <button type="submit" name="action" value="remove" class="text_style text-red-500 text-center text-2xl border-2 border-red-500 rounded-lg w-1/2 hover:bg-white/10">
                        Remove
                    </button>
                </div>
            </form>
        </div>
    </div>


    
</body>
</html>
