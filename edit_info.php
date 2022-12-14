<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Information</title>
    <link href="CSS/lists.css" rel="stylesheet" type="text/css">
    <link href="CSS/main.css" rel="stylesheet" type="text/css">
</head>


<body>
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
    <div class="listing" id="wrap_list">
        <h1>Edit Patient Information </h1>
        <?php
            // define variables to empty values
            $nameErr = $dobErr = $genderErr = $roomErr = "";
            $patientID = $patientName = $gender = $dob = $roomNumber = $age = "";
            if($_SERVER["REQUEST_METHOD"] == 'POST' && (isset($_POST['editInfo']) || isset($_POST['submit']))) {
                // Storing data entered into variables
                $patientID = $_POST['patientID'];
            } else {
                exit("Request method error");
            }
            $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            // Fetch and store patient info
            $sql = "SELECT * FROM Patient where ID = $patientID";
            $rs  = odbc_exec($conn,$sql);  
            while ($row = odbc_fetch_array($rs)) {
                $patientName = $row['PatientName'];
                $gender = $row['Gender'];
                $dob = date('d/m/Y', strtotime($row['DOB']));
                $roomNumber = $row['RoomNumber'];
            }
            if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
                if (strcmp(trim($patientName),trim($_POST["name"])) != 0) {
                    // Validate Name
                    if (!preg_match("/^[a-zA-Z ]*$/", $_POST["name"])) {  
                        $nameErr = "Only alphabets and white space are allowed";  
                    } else {
                        // Update database
                        $patientName = $_POST["name"];
                        $sql = "UPDATE Patient SET PatientName = '$patientName'
                            WHERE ID = $patientID";
                        $rs  = odbc_exec($conn,$sql);
                    }
                } 
                // Validate Gender 
                if (strcmp(trim($gender),trim($_POST["gender"])) != 0) {
                    if (strcmp('F',trim($_POST["gender"])) == 0 || strcmp('M',trim($_POST["gender"])) == 0) {  
                        // Update database
                        $gender = $_POST["gender"];
                        $sql = "UPDATE Patient SET Gender = '$gender'
                            WHERE ID = $patientID";
                        $rs  = odbc_exec($conn,$sql);  
                    } else {
                        $genderErr = "Gender must be input in format 'F' or 'M'.";  
                    }
                } 
                // Validate DOB
                if (strcmp(trim($dob),trim(trim($_POST["dob"]))) != 0) {
                    if (!preg_match('~^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$~', $_POST["dob"], $parts)) {  
                        $dobErr = "The date of birth is not a valid date in the format DD/MM/YYYY";  
                    } else if (!checkdate($parts[2],$parts[1],$parts[3])){
                        $dobErr = 'The date of birth is invalid. Please check that the month is between 1 and 12, and the day is valid for that month.';
                    } else if ($parts[3] > 2022) {
                        $dobErr = 'The date of birth is invalid. Please check that the year is not greater than this year';
                    } else if ($parts[3] < 1900) {
                        $dobErr = 'The date of birth is invalid.';
                    } else {
                        // Update database with correct dob format
                        $dob = $_POST["dob"];
                        $today = DateTime::createFromFormat('d/m/Y', date("d/m/Y"));
                        $dobObj = DateTime::createFromFormat('d/m/Y', $dob);
                        $diff = date_diff($dobObj, $today);
                        $age = $diff->format("%Y");
                        $sql = "UPDATE Patient SET Age = $age, DOB = #$dob# 
                            WHERE ID = $patientID";
                        $rs  = odbc_exec($conn,$sql);  
                    }
                }
                // Validate room bumber
                if (strcmp(trim($roomNumber),trim($_POST["roomNumber"])) != 0) {
                    if (!ctype_digit($_POST["roomNumber"])) {  
                        $roomErr = "Only numbers are allowed";  
                    } else {
                        // Update room number
                        $roomNumber = $_POST["roomNumber"];
                        $sql = "UPDATE Patient SET RoomNumber = '$roomNumber'
                            WHERE ID = $patientID";
                        $rs  = odbc_exec($conn,$sql);  
                    }
                }
            }
        ?>
        <!-- Patient info edit form with prefilled value -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="container">
                <label><b>Patient Name</b></label>
                <input type="text" value="<?php echo $patientName; ?>" name="name" id="name"/>
                <span class="error">* <?php echo $nameErr; ?> </span>  
                <br><br>
                <label><b>Gender</b></label>
                <input type="text" value="<?php echo $gender; ?>" name="gender" id="gender"/>
                <span class="error">* <?php echo $genderErr; ?> </span>  
                <br><br>
                <label><b>DOB</b></label>
                <input type="text" value="<?php echo $dob; ?>" name="dob" id="dob"/>
                <span class="error">* <?php echo $dobErr; ?> </span>  
                <br><br>
                <label><b>Room Number</b></label>
                <input type="number" value="<?php echo $roomNumber; ?>" name="roomNumber" id="roomNumber"/>
                <span class="error">* <?php echo $roomErr; ?> </span>  
                <br><br>
                <input type="hidden" name="patientID" value="<?php echo $patientID; ?>">
                <button type="submit" name="submit" id="submit">Edit</button>
            </div>
        </form>
        <?php
        ?>
    </div>
    <div id="Footer">
        <?php
            include('footer.php');
        ?>
    </div>
</body>
</html>