<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "Expenditures_App";

        try {
                $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $id = 1; // Hardcoded ID
                $amount = $_POST['amount'] ?? null;
                $action = $_POST['action'] ?? null;

                // Fetch current balance
                $stmt = $conn->prepare("SELECT Available_Amount FROM Users WHERE Id = :id");
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $availableAmount = $result['Available_Amount'];

                if ($action === "add") {
                    $newAmount = $availableAmount + $amount;
                    $stmt = $conn->prepare("UPDATE Users SET Available_Amount = :newAmount WHERE Id = :id");
                    $stmt->bindParam(':newAmount', $newAmount);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    
                    
                    
                } elseif ($action === "remove") {
                    if ($availableAmount >= $amount) {
                        $newAmount = $availableAmount - $amount;
                        $stmt = $conn->prepare("UPDATE Users SET Available_Amount = :newAmount WHERE Id = :id");
                        $stmt->bindParam(':newAmount', $newAmount);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
        } catch (PDOException $e) {}
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Money</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/cc6fad79da.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="div1" class="X1 bg-[#117779] p-4 h-screen flex flex-col items-center justify-center">
        <div class="border border-white p-6 rounded-lg max-w-lg w-full">
            <div class="flex justify-center gap-4 items-center text-center mb-6">
                <a href="index.php"><i class="text-white fa-solid fa-arrow-left text-2xl"></i></a>
                <h1 class="text_style text-[#e59700] text-center text-4xl">Add Money</h1>
                <a href="addmoney.php"><i class="text-white text-2xl fa-solid fa-arrows-rotate"></i></a>
            </div>
            <form method="POST" class="w-full">
                <label for="amount" class="text-white text-xl">Amount</label>
                <input
                    name="amount"
                    type="number"
                    placeholder="Type here"
                    class="input input-bordered input-warning w-full bg-[#117779] mb-4"
                    required 
                />
                <input type="hidden" name="action" id="action">

                <div class="w-full flex gap-9 mt-5">
                    <button type="submit" id="AddMoney" class="text_style text-green-500 text-center text-2xl border-2 border-green-500 rounded-lg w-1/2 hover:bg-black/10">
                        Add
                    </button>
                    <button type="submit" id="RemoveMoney" class="text_style text-red-500 text-center text-2xl border-2 border-red-500 rounded-lg w-1/2 hover:bg-white/10">
                        Remove
                    </button>
                </div>
                <p id="result" class="text-white text-center mt-4"></p>
            </form>
        </div>
    </div>

    <form action="addmoney.php" method="POST">


    <script>
        document.getElementById("AddMoney").addEventListener("click", function (e) {
            document.getElementById("action").value = "add";
        });
        document.getElementById("RemoveMoney").addEventListener("click", function (e) {
            document.getElementById("action").value = "remove";
        });
    </script>

    
</body>
</html>
