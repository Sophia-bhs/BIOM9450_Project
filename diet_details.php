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
    <?php
        session_start();
        if($_SESSION['status']!="Active") {
            header("location:index.php");
        }
        $PracID = $_SESSION['PracID'];
        $PracName = $_SESSION['PracName'];
    ?>
    <div class="PatientDiet" id="header">
        <h1> Diet Details </h1>
    </div>
    <div id="Naviagation">
        <?php
            include('nav_bar.php');
        ?>
    </div>
    <div id="wrap_list">
        <?php
            // ID of Patient selected
            $id = (int) $_GET['id'];
            
            $conn = odbc_connect('z5256089','' ,'' ,SQL_CUR_USE_ODBC); 
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
    <div id="Footer">
        <?php
            include('footer.php');
        ?>
    </div>
</body>
</html>