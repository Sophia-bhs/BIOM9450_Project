<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link href="CSS/lists.css" rel="stylesheet" type="text/css">
    <link href="CSS/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E7FBFC">
    <div class="tab" id="navigation">
        <!-- Display all tabs -->
        <a class="tablinks" href="home.php">Home</a>
        <a class="tablinks" href="management.php">Patient Management</a>
        <!-- Lists and details -->
        <div class="dropdown">
            <button class="tablinks">Details</button>
            <div class="dropdown-content">
                <a href="medication.php">Medication List</a>
                <a href="diet.php">Diet List</a>
                <a href="patients.php">Patient List</a>
            </div>
        </div>             
        <!-- Summary reports -->
        <div class="dropdown">
            <button class="tablinks">Summary</button>
            <div class="dropdown-content">
                <a href="summary_med_date_centre.php">Medication</a>
                <a href="summary_diet_date_centre.php">Diet</a>
            </div>
        </div>             
    </div>
</body>
</html>