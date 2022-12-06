<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <link href="CSS/lists.css" rel="stylesheet" type="text/css">
    <link href="CSS/main.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#E7FBFC">
    <?php
        session_start();
        if($_SESSION['status']!="Active") {
            header("location:index.php");
        }
        $PracName = $_SESSION['PracName'];
    ?>
    <!-- Display Practitioner name and logout option -->
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