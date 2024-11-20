
<?php
require 'database.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cc6fad79da.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-[#e59700] max-w-screen-xl mx-auto">

    <div class="bg-[#e59700] h-screen  flex flex-col relative">
        <div class="flex-grow max-w-screen-xl mx-auto p-4">
            <!-- Content here -->
            <h1 id="greeting" class="text_style text-center text-white text-2xl">Hello! Sadid Ahammed</h1>
            <img class="logo" src="logo.png" alt="Logo">
            <h1 class=" text_style text-green-950 text-2xl text-center">Remaining Amount: 100$</h1>
            <h1 id="time" class=" text_style text-white text-2xl text-center">Time</h1>
            <p id="date" class="text_style text-white text-center">Date</p>
        </div>
        <div id="div1" class="X1 bg-[#117779] p-4  h-full border-t-2 border-white rounded-t-lg">
            <h1 class="text_style text-[#e59700] text-center text-4xl mb-2">Services</h1>

            <div class="grid grid-cols-1 gap-2 m-10">

                <a href="addmoney.php" class="text_style text-white text-center text-2xl border-b-2 border-white rounded-b-lg p-2 hover:text-[#e59700] hover:border-[#e59700]">Add Money</a>
                <a href="addExpenditures.php" class="text_style text-white text-center text-2xl border-b-2 border-white rounded-b-lg p-2 hover:text-[#e59700] hover:border-[#e59700]">Add Expenditures</a>
                <a href="seeExpenditures.php" class="text_style text-white text-center text-2xl border-b-2 border-white rounded-b-lg p-2 hover:text-[#e59700] hover:border-[#e59700]">See Expenditures</a>
                <a href="updateExpenditures.php" class="text_style text-white text-center text-2xl border-b-2 border-white rounded-b-lg p-2 hover:text-[#e59700] hover:border-[#e59700]">Update Expenditures</a>
            </div>
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
                greeting = "Good Morning, Sadid Ahammed! Hope you have a productive day ahead.";
            } else if (hours >= 12 && hours < 15) {
                greeting = "Good Afternoon, Sadid Ahammed! Hope you're enjoying your day.";
            } else if (hours >= 15 && hours < 18) {
                greeting = "Good Evening, Sadid Ahammed! How was your day?";
            } else {
                greeting = "Good Night, Sadid Ahammed! Time to relax and recharge.";
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