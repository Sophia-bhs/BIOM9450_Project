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
        <!-- <p><strong>Navigation Menu</strong></p> -->
        <a class="tablinks" href="home.php">Home</a>
        <a class="tablinks" href="medication.php">Medication</a>
        <a class="tablinks" href="diet.php">Diet</a>
        <a class="tablinks" href="patients.php">Patients</a>
        <a class="tablinks" href="management.php">Patient Management</a>
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