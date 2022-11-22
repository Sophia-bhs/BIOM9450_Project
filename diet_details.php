<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Details</title>
    <!-- <link href="lists.css" rel="stylesheet" type="text/css"> -->
</head>

<h1> Diet Details</h1>
<body>
<div id="wrap_list">
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            echo odbc_errormsg($conn);
            $sql = "SELECT * FROM Diet where ID = $id";
            $rs  = odbc_exec($conn,$sql);  
            echo odbc_errormsg($conn);
            echo "<table border-collapse: collapse>
            <tr>
            <th>ID</th>
            <th>Diet Name</th>
            <th>Amount/Day</th>
            <th>Round1</th>
            <th>Round2</th>
            <th>Round3</th>
            </tr>";
            while($row = odbc_fetch_array($rs)) {
                echo "<tr>";
                echo "<td>" . $row['ID']. "</td>";
                echo "<td>" . $row['DietName']. "</td>";
                echo "<td>" . $row['Amount/Day']. "</td>";
                echo "<td>" . $row['Round1']. "</td>";
                echo "<td>" . $row['Round2']. "</td>";
                echo "<td>" . $row['Round3']. "</td>";
                echo "</tr>";
            }
            echo "</table>";

            odbc_close($conn);
        ?>
    </div>

</body>
</html>