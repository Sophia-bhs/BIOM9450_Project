<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient Information</title>
    <link href="lists.css" rel="stylesheet" type="text/css">
</head>


<body>
<div id="wrap_list">
        <h1> Edit Patient Information </h1>
        <?php
            $patientID = $patientName = $gender = $dob = $roomNumber = "";
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
            echo odbc_errormsg($conn);
            $sql = "SELECT * FROM Patient where ID = $patientID";
            $rs  = odbc_exec($conn,$sql);  
            echo odbc_errormsg($conn);
            while($row = odbc_fetch_array($rs)) {
                $patientName = $row['PatientName'];
                $gender = $row['Gender'];
                $dob = date('Y-m-d', strtotime($row['DOB']));
                $roomNumber = $row['RoomNumber'];
            }
            if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
                if (strcmp(trim($patientName),trim($_POST["name"])) != 0) {
                    echo "name diff";
                    // Validate
                } else if (strcmp(trim($gender),trim($_POST["gender"])) != 0) {
                    echo " gender diff";
                } else if (strcmp(trim($dob),trim($_POST["dob"])) != 0) {
                    echo "dob diff";
                    echo $dob;
                    echo $_POST["dob"];
                } else if (strcmp(trim($roomNumber),trim($_POST["roomNumber"])) != 0) {
                    echo "number diff";
                }
            }
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="container">
                <label><b>Patient Name</b></label>
                <input type="text" value="<?php echo $patientName; ?>" name="name" id="name"/>
                <label><b>Gender</b></label>
                <input type="text" value="<?php echo $gender; ?>" name="gender" id="gender"/>
                <label><b>DOB</b></label>
                <input type="text" value="<?php echo $dob; ?>" name="dob" id="dob"/>
                <label><b>Room Number</b></label>
                <input type="number" value="<?php echo $roomNumber; ?>" name="roomNumber" id="roomNumber"/>
                <input type="hidden" name="patientID" value="<?php echo $patientID; ?>">
                <button type="submit" name="submit" id="submit">Edit</button>
            </div>
        </form>
    </div>
    
</body>
</html>