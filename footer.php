<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
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
    <div id="footer">
        <div class="PracName">
            <?php
                echo "Practitioner: $PracName";
            ?>
        </div>
        <div class="logout">
            <a class="logout" href="logout.php" title="Logout">Logout
        </div>
    </div>
</body>
</html>