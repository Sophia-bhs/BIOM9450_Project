<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
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
    <div class="PatientMedAd" id="header">
        <h1>
            Patient Med Administration
        </h1>
    </div>

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
    <div class="listing">
        <h1> Patients </h1>
        <div id="wrap_list">
            <?php
                $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC); 
                if (!$conn) {
                    odbc_close($conn);
                    exit("Connection Failed: ".odbc_errormsg());
                }
                
                $sql = "SELECT ID, PatientName FROM Patient";
                $rs  = odbc_exec($conn,$sql);  
                ?>
                <table class="styled-table">
                <tr>
                <th>ID</th>
                <th>Patient Name</th>
                </tr>
                <?php
                while($row = odbc_fetch_array($rs)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID']. "</td>";
                    echo "<td>" . '<a class="linkColor" href="patient_details.php?id='.$row['ID'].'">'.$row['PatientName'].'</a>' . "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                odbc_close($conn);
            ?>
        </div>
    </div>
    <div id="Footer">
        <?php
            include('footer.php');
        ?>
    </div>
</body>
</html>