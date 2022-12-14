<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients Details</title>
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
        <h1> Patients Details </h1>
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            // Establish odbc connection            
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            // Fetch and display detailed patient information in a table
            $sql = "SELECT * FROM Patient where ID = $id";
            $rs  = odbc_exec($conn,$sql);  
            echo odbc_errormsg($conn);
            echo "<table class='details-styled-table'>
            <colgroup>
                <col span='1' style='width: 7%;'>
                <col span='1' style='width: 20%;'>
                <col span='1' style='width: 13%;'>
                <col span='1' style='width: 20%;'>
                <col span='1' style='width: 20%;'>
                <col span='1' style='width: 20%;'>
            </colgroup>
            <tr>
            <th>ID</th>
            <th>Picture</th>
            <th>Patient Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>DOB</th>
            <th>Room</th>
            <th>Edit</th>
            </tr>";
            while($row = odbc_fetch_array($rs)) {
                echo "<tr>";
                echo "<td>" . $row['ID']. "</td>";
                echo "<td><img src=" .$row['Picture']. " alt=\"Patient Image\" width=\"150\" height=\"200\"></td>";
                echo "<td>" . $row['PatientName']. "</td>";
                echo "<td>" . $row['Age']. "</td>";
                echo "<td>" . $row['Gender']. "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['DOB'])). "</td>";
                echo "<td>" . $row['RoomNumber']. "</td>";
                echo '<td><form id="editInfo" action="edit_info.php" method="post">
                    <input type="hidden" name="patientID" value='. $row['ID'] . '>
                    <input type="submit" name="editInfo" value="Edit">
                </form></td>';
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