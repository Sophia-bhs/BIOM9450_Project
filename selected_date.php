<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Date</title>
    </head>
    
    <?php
        $unformatedDate = $_POST['selectedDate'];
        $year = explode("-", $unformatedDate)[0];
        $month = explode("-", $unformatedDate)[1];
        $day = explode("-", $unformatedDate)[2];
        $date = "$day/$month/$year";
        echo $date;
    ?>
</html>