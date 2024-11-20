<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>See Money</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.14/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cc6fad79da.js" crossorigin="anonymous"></script>
</head>
<body >
    <div id="div1" class="X1 bg-[#117779] p-4 h-screen flex flex-col items-center justify-center">
        <div class="border border-white p-6 rounded-lg max-w-lg w-full">
            <div class="flex justify-center gap-4 items-center text-center mb-6">
                <a href="index.php"><i class="text-white fa-solid fa-arrow-left text-2xl"></i></a>
                <h1 class="text_style text-[#e59700] text-center text-2xl">See Expenditures</h1>
                <a href="seeExpenditures.php"><i class="text-white text-2xl fa-solid fa-arrows-rotate"></i></a>
            </div>
            <p class="text-white">Select a range of date.</p>
            <input
                    id="date1"
                    type="date"
                    class="input input-bordered input-warning w-full bg-[#117779] mb-5" 
                    required 
            />
            <input
                id="date2"
                type="date"
                class="input input-bordered input-warning w-full bg-[#117779] mb-5" 
                required 
            />
            <div class="flex justify-center">
                <button id="calculate" class="btn btn-outline btn-warning ">See Expenditures</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("calculate").addEventListener("click", function () {
        const date1 = document.getElementById("date1").value;
        const date2 = document.getElementById("date2").value;
        const warning = document.getElementById("warning");

        // Remove existing warning if any
        if (warning) {
            warning.remove();
        }

        // Create a new warning element
        const warningMessage = document.createElement("p");
        warningMessage.id = "warning";
        warningMessage.classList.add("text-red-500", "mt-2", "text-center");

        // Validation checks
        if (!date1 || !date2) {
            warningMessage.textContent = "Please select both dates.";
        } else if (new Date(date1) > new Date(date2)) {
            warningMessage.textContent = "Start date cannot be greater than the end date.";
        } else if (new Date(date2) < new Date(date1)) {
            warningMessage.textContent = "End date cannot be earlier than the start date.";
        } else {
            warningMessage.textContent = "Dates are valid!";
            warningMessage.classList.remove("text-red-500");
            warningMessage.classList.add("text-green-500");
        }

        // Append the warning message below the second date input
        const date2Input = document.getElementById("date2");
        date2Input.parentNode.insertBefore(warningMessage, date2Input.nextSibling);
    });

    </script>
</body>
</html>