<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet</title>
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
    <div class="listing">
        <h1> Diet list </h1>
        <div id="wrap_list">
            <?php
                $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
                if (!$conn) {
                    odbc_close($conn);
                    exit("Connection Failed: ".odbc_errormsg());
                }
                // Fetch and display all Diet regime in a table
                $sql = "SELECT ID, DietName FROM Diet ORDER BY ID";
                $rs  = odbc_exec($conn,$sql);  
                ?>
                <table class="styled-table">
                <tr>
                <th>ID</th>
                <th>Diet</th>
                </tr>
                <?php
                while($row = odbc_fetch_array($rs)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID']. "</td>";
                    echo "<td>" . '<a class="linkColor" href="details_diet.php?id='.$row['ID'].'">'.$row['DietName'].'</a>' . "</td>";
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