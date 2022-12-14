<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Details</title>
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
        <h1> Diet Details </h1>
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            // Establish odbc connection            
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            // Fetch and display detailed diet information in a table
            $sql = "SELECT * FROM Diet where ID = $id";
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
                // Display true/false value in readable text
                if ($row['Round1'] == 1) {
                    echo "<td>Recommended</td>";
                } else {
                    echo "<td>Not Recommended</td>";
                }
                if ($row['Round2'] == 1) {
                    echo "<td>Recommended</td>";
                } else {
                    echo "<td>Not Recommended</td>";
                }
                if ($row['Round3'] == 1) {
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