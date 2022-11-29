<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication</title>
    <link href="lists.css" rel="stylesheet" type="text/css">
</head>


<body>
    <div id="wrap_list">
        <h1> Medication list</h1>
        <?php
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            
            $sql = "SELECT ID, MedName FROM Medication";
            $rs  = odbc_exec($conn,$sql);  

            echo "<table border-collapse: collapse>
            <tr>
            <th>ID</th>
            <th>Medication Name</th>
            </tr>";
            while($row = odbc_fetch_array($rs)) {
                echo "<tr>";
                echo "<td>" . $row['ID']. "</td>";
                echo "<td>" . '<a href="med_details.php?id='.$row['ID'].'">'.$row['MedName'].'</a>' . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            odbc_close($conn);
        ?>
    </div>
</body>
</html>