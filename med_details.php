<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Details</title>
    <link href="lists.css" rel="stylesheet" type="text/css">
    <link href="main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E7FBFC">
    <?php
        session_start();
        if($_SESSION['status']!="Active") {
            header("location:index.php");
        }
        $PracID = $_SESSION['PracID'];
        $PracName = $_SESSION['PracName'];
    ?>
    <div class="PatientMed" id="header">
        <h1> Medication Details </h1>
    </div>
    <div class="tab" id="navigation">
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
    <div id="wrap_list">
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            
            $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            echo odbc_errormsg($conn);
            $sql = "SELECT * FROM Medication where ID = $id";
            $rs  = odbc_exec($conn,$sql);  
            echo odbc_errormsg($conn);
            echo "<table border-collapse: collapse>
            <tr>
            <th>ID</th>
            <th>Medication Name</th>
            <th>Dosage</th>
            <th>Route</th>
            <th>Morning</th>
            <th>Afternoon</th>
            <th>Evening</th>
            </tr>";
            while($row = odbc_fetch_array($rs)) {
                echo "<tr>";
                echo "<td>" . $row['ID']. "</td>";
                echo "<td>" . $row['MedName']. "</td>";
                echo "<td>" . $row['Dosage']. "</td>";
                echo "<td>" . $row['Route']. "</td>";
                echo "<td>" . $row['Morning']. "</td>";
                echo "<td>" . $row['Afternoon']. "</td>";
                echo "<td>" . $row['Evening']. "</td>";
                echo "</tr>";
            }
            echo "</table>";

            odbc_close($conn);
        ?>
    </div>
    <div id="Footer">
        <?php
            include('footer.php');
        ?>
    </div>
</body>
</html>