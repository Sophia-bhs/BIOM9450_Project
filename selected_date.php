<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Date</title>
    </head>
    
    <?php
        $unformatedDate = $_POST['selectedDate'];
        $exp = explode("-", $unformatedDate);
        $year = $exp[0];
        $month = $exp[1];
        $day = $exp[2];
        $date = "$day/$month/$year";
        echo $date;
    ?>
</html>