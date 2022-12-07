<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication Details</title>
    <link href="CSS/lists.css" rel="stylesheet" type="text/css">
    <link href="CSS/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E7FBFC">
    <div class="PatientMedAd" id="header">
        <h1>
            Patient Med Administration
        </h1>
    </div>
    <div id="Naviagation">
        <?php
            include('nav_bar.php');
        ?>
    </div>
    <div id="wrap_list" class="listing">
        <h1> Medication Details </h1>
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            // Establish odbc connection            
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            // Fetch and display detailed medication information in a table
            $sql = "SELECT * FROM Medication where ID = $id";
            $rs  = odbc_exec($conn,$sql);  
            echo odbc_errormsg($conn);
            echo "<table class='details-styled-table'>
            <colgroup>
                <col span='1' style='width: 5%;'>
                <col span='1' style='width: 16%;'>
                <col span='1' style='width: 10%;'>
                <col span='1' style='width: 16%;'>
                <col span='1' style='width: 16%;'>
                <col span='1' style='width: 16%;'>
                <col span='1' style='width: 16%;'>
            </colgroup>
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
                // Display true/false value in readable text
                if ($row['Morning'] == 1) {
                    echo "<td>Recommended</td>";
                } else {
                    echo "<td>Not Recommended</td>";
                }
                if ($row['Afternoon'] == 1) {
                    echo "<td>Recommended</td>";
                } else {
                    echo "<td>Not Recommended</td>";
                }
                if ($row['Evening'] == 1) {
                    echo "<td>Recommended</td>";
                } else {
                    echo "<td>Not Recommended</td>";
                }
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