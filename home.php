<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="home.css" rel="stylesheet" type="text/css">
</head>


<body>
    <?php  
        // define variables to empty values and defalt values
        $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
        if (!$conn) {
            odbc_close($conn);
            exit("Connection Failed: ".odbc_errormsg());
        }
        $patientName = $patientID = $medStatus = $dietStatus = $medAdminID = $dietAdminID = "";
        $chosenRound = 1;
        $chosenDate = date('Y-m-d');
        session_start();
        $pracID = $_SESSION['PracID'];
        $pracName = $_SESSION['PracName'];
  
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {  
            // Store selected date, patient and round number
            $_SESSION['patientName'] = $patientName = $_POST["patientName"];  
            $_SESSION['selectedDate'] = $chosenDate = $_POST["selectedDate"];
            $_SESSION['roundNumber'] = $chosenRound = $_POST["roundNumber"];
            $sql = "SELECT ID FROM Patient WHERE PatientName = '$patientName'";
            $rs  = odbc_exec($conn, $sql);
            while ($row = odbc_fetch_array($rs)) {
                $patientID = $row['ID'];
            }
            $_SESSION['patientID'] = $patientID;
        } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Read selected date, patient and round number
            $patientName = $_SESSION["patientName"];  
            $chosenDate = $_SESSION["selectedDate"];
            $chosenRound = $_SESSION["roundNumber"];
            $patientID = $_SESSION["patientID"];
            if (isset($_POST['medEdit'])) {
                $medAdminID = $_POST["medAdminID"];  
                $medStatus = $_POST["medStatus"];  
            } else if (isset($_POST['dietEdit'])) {
                $dietAdminID = $_POST["dietAdminID"];  
                $dietStatus = $_POST["dietStatus"];  
            }  
        } 
    ?>   
    <div id="wrapper">
        <div id="filter">
            <h1> Home </h1> 
            <form id="chooseDate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="date" name="selectedDate" min='2022-01-01' max='2025-12-31' value="<?php echo date('Y-m-d', strtotime($chosenDate)); ?>">
                <!-- Patient name -->
                <label for="patientName">Patient Name:</label>
                <select name="patientName" id="patientName">
                <?php 
                    $sql = "SELECT PatientName FROM Patient ORDER BY PatientName";
                    $rs  = odbc_exec($conn, $sql);
                    $firstPatient = true;
                    while ($row = odbc_fetch_array($rs)) {
                        if (isset($patientName) && $patientName == $row['PatientName']) {
                            echo "<option value='".$row['PatientName']."' selected>".$row['PatientName']."</option>";
                        } else if (!isset($patientName) && $firstPatient == true) {
                            echo "<option value='".$row['PatientName']."' selected>".$row['PatientName']."</option>";
                            $firstPatient = false;
                        } else {
                            echo "<option value='".$row['PatientName']."'>".$row['PatientName']."</option>";
                        }
                    }
                ?>        
                </select>
                
                <!-- Round number -->
                <label for="roundNumber">Round Number:</label>
                <select name="roundNumber" id="roundNumber">
                    <option value="1" <?php if ($chosenRound=="1") echo "selected";?>>1</option>
                    <option value="2" <?php if ($chosenRound=="2") echo "selected";?>>2</option>
                    <option value="3" <?php if ($chosenRound=="3") echo "selected";?>>3</option> 
                </select>
                <input type="submit" name="search" value="OK">
            </form>
        </div>

        
        <div id="content">
            <?php  
                if(isset($_POST['medEdit'])) {
                    if (!$conn) {
                        odbc_close($conn);
                        exit("Connection Failed: ".odbc_errormsg());
                    }
                    // Update database with status
                    $sql = "UPDATE MedAdministration
                    SET PractitionerID = $pracID, Status = '$medStatus'
                    WHERE ID = $medAdminID;";
                    $rs  = odbc_exec($conn, $sql);
                    echo odbc_errormsg($conn);
                } else if(isset($_POST['dietEdit'])) {
                    if (!$conn) {
                        odbc_close($conn);
                        exit("Connection Failed: ".odbc_errormsg());
                    }
                    echo $pracID;
                    echo $dietStatus;
                    echo $dietAdminID;
                    // Update database with status
                    $sql = "UPDATE DietAdministration
                    SET PractitionerID = $pracID, Status = '$dietStatus'
                    WHERE ID = $dietAdminID;";
                    $rs  = odbc_exec($conn, $sql);
                    echo odbc_errormsg($conn);
                }
                if(isset($_POST['search']) || isset($_POST['medEdit']) || isset($_POST['dietEdit'])) {  
                    if (!$conn) {
                        odbc_close($conn);
                        exit("Connection Failed: ".odbc_errormsg());
                    }
                    //Medication
                    $sql = "SELECT * FROM MedAdministration 
                        WHERE PatientID = $patientID 
                        AND Round = $chosenRound AND MedDate = #$chosenDate#";
                    $rs  = odbc_exec($conn, $sql);

                    echo "<h2>Medication Administration:</h2>"; 
                    echo "<table border-collapse: collapse>
                    <tr>
                    <th>ID</th>
                    <th>PatientID</th>
                    <th>Practitioner</th>
                    <th>MedID</th>
                    <th>Round</th>
                    <th>Date</th>
                    <th>Status</th>
                    </tr>";
                    while($row = odbc_fetch_array($rs)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['PatientID'] . "</td>";
                        if (isset($row['PractitionerID'])) {
                            echo "<td>" . $row['PractitionerID'] . "</td>";
                        } else {
                            echo "<td>-</td>";
                        }
                        echo "<td>" . $row['MedID'] . "</td>";
                        echo "<td>" . $row['Round'] . "</td>";
                        echo "<td>" . date('Y-m-d', strtotime($row['MedDate'])) . "</td>";
                        if (isset($row['Status'])) {
                            echo "<td>" . $row['Status'] . "</td>";
                        } else {
                            echo "<td>" . '<form id="chooseStatus" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                                <select name="medStatus" id="medStatus">
                                    <option value="Accepted" selected>Accepted</option>
                                    <option value="Ceased">Ceased</option>
                                    <option value="Fasting">Fasting</option> 
                                    <option value="No Stock">No Stock</option> 
                                    <option value="Rejected">Rejected</option> 
                                </select>
                                <input type="hidden" name="medAdminID" value="' . $row['ID'] . '">
                                <input type="submit" value="Edit" name="medEdit">
                            </form>' . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
               
                    //Diet
                    $sql = "SELECT * FROM DietAdministration 
                        WHERE PatientID = $patientID 
                        AND Round = $chosenRound AND DietDate = #$chosenDate#";
                    $rs  = odbc_exec($conn, $sql);
                    echo odbc_errormsg($conn);
                    echo "<h2>Diet Administration:</h2>"; 
                    echo "<table border-collapse: collapse>
                    <tr>
                    <th>ID</th>
                    <th>PatientID</th>
                    <th>Practitioner</th>
                    <th>DietID</th>
                    <th>Round</th>
                    <th>Date</th>
                    <th>Status</th>
                    </tr>";
                    while($row = odbc_fetch_array($rs)) {
                        echo "<tr>";
                        echo "<td>" . $row['ID'] . "</td>";
                        echo "<td>" . $row['PatientID'] . "</td>";
                        if (isset($row['PractitionerID'])) {
                            echo "<td>" . $row['PractitionerID'] . "</td>";
                        } else {
                            echo "<td>-</td>";
                        }
                        echo "<td>" . $row['DietID'] . "</td>";
                        echo "<td>" . $row['Round'] . "</td>";
                        echo "<td>" . date('Y-m-d', strtotime($row['DietDate'])) . "</td>";
                        if (isset($row['Status'])) {
                            echo "<td>" . $row['Status'] . "</td>";
                        } else {
                            echo "<td>" . '<form id="chooseStatus" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">
                                <select name="dietStatus" id="dietStatus">
                                    <option value="Accepted" selected>Accepted</option>
                                    <option value="Ceased">Ceased</option>
                                    <option value="Fasting">Fasting</option> 
                                    <option value="No Stock">No Stock</option> 
                                    <option value="Rejected">Rejected</option> 
                                </select>
                                <input type="hidden" name="dietAdminID" value="' . $row['ID'] . '">
                                <input type="submit" value="Edit" name="dietEdit">
                            </form>' . "</td>";
                        }
                        echo "</tr>";
                    }
                    echo "</table>";
                }  
            ?>  
        </div>
    </div>

    <div id="extra">
        <p><strong>Patient Info</strong></p>
        <h3><img src="stickman.jpg" alt="Patient Image" width="150" height="200"></h3>
        <ul>
            <?php
                if (!$conn) {
                    odbc_close($conn);
                    exit("Connection Failed: ".odbc_errormsg());
                }
                echo odbc_errormsg($conn);
                $sql = "SELECT * FROM Patient where ID = $patientID";
                $rs  = odbc_exec($conn,$sql);  
                echo odbc_errormsg($conn);

                while($row = odbc_fetch_array($rs)) {
                    echo "<li>" . $row['PatientName']. "</li>";
                    echo "<li>ID: " . $row['ID']. "</li>";
                    echo "<li>Age: " . $row['Age']. "</li>";
                    echo "<li>Gender: " . $row['Gender']. "</li>";
                    echo "<li>DOB: " . date('Y-m-d', strtotime($row['DOB'])). "</li>";
                    echo "<li>RoomNumber: " . $row['RoomNumber']. "</li>";
                }
            ?>
        </ul>
    </div>
</body>
</html>