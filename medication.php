<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medication</title>
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
        <h1>Medication list</h1>
    
        <div id="wrap_list">
            <?php
                $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC); 
                if (!$conn) {
                    odbc_close($conn);
                    exit("Connection Failed: ".odbc_errormsg());
                }
                
                $sql = "SELECT ID, MedName FROM Medication ORDER BY ID";
                $rs  = odbc_exec($conn,$sql);  
            ?>
            <table class="list-styled-table">
                <colgroup>
                    <col span='1' style='width: 30%;'>
                    <col span='1' style='width: 70%;'>
                </colgroup>
                <tr>
                <th>ID</th>
                <th>Medication Name</th>
                </tr>
                <?php
                    while($row = odbc_fetch_array($rs)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID']. "</td>";
                        echo "<td>" . '<a class="linkColor" href="details_med.php?id='.$row['ID'].'">'.$row['MedName'].'</a>' . "</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <?php
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