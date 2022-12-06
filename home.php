<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="CSS/home.css" rel="stylesheet" type="text/css">
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
    <?php
        // define variables to empty values and defalt values
        $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
        if (!$conn) {
            odbc_close($conn);
            exit("Connection Failed: ".odbc_errormsg());
        }
        $patientName = $medStatus = $dietStatus = $medAdminID = $dietAdminID = "";
        $patientID = 1;
        $chosenRound = 1;
        $chosenDate = date('Y-m-d');
        $pracID = $_SESSION['PracID'];
        $pracName = $_SESSION['PracName'];
        $admTime = date('H:i:s');
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
                    $sql = "SELECT ID, PatientName FROM Patient ORDER BY PatientName";
                    $rs  = odbc_exec($conn, $sql);
                    // $sql = "SELECT PatientName FROM Patient INNER JOIN PracPatient ON Patient.ID = PracPatient.PatientID WHERE PractitionerID = $pracID ORDER BY PatientName";
                    // $caredPatient  = odbc_exec($conn, $sql);
                    $firstPatient = true;
                    while ($row = odbc_fetch_array($rs)) {
                        $formValue = "";
                        $pID = $row['ID'];
                        $sql = "SELECT * FROM PracPatient WHERE PractitionerID = $pracID AND PatientID = $pID";
                        $caredPatient  = odbc_exec($conn, $sql);
                        if (odbc_fetch_row($caredPatient)) {
                            $formValue = $row['PatientName']."*";
                        } else {
                            $formValue = $row['PatientName'];
                        }

                        if (isset($patientName) && $patientName == $row['PatientName']) {
                            echo "<option value='".$row['PatientName']."' selected>".$formValue."</option>";
                        } else if (!isset($patientName) && $firstPatient == true) {
                            echo "<option value='".$row['PatientName']."' selected>".$formValue."</option>";
                            $firstPatient = false;
                        } else {
                            echo "<option value='".$row['PatientName']."'>".$formValue."</option>";
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

        <div id="extra">
            <h2><strong>Patient Info</strong></h2>
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
                        echo "<img src=" .$row['Picture']. " alt=\"Patient Image\" width=\"150\" height=\"200\">";
                        echo "<li>" . $row['PatientName']. "</li>";
                        echo "<li>ID: " . $row['ID']. "</li>";
                        echo "<li>Age: " . $row['Age']. "</li>";
                        echo "<li>Gender: " . $row['Gender']. "</li>";
                        echo "<li>DOB: " . date('Y-m-d', strtotime($row['DOB'])). "</li>";
                        echo "<li>Room Number: " . $row['RoomNumber']. "</li>";
                    }
                ?>
                <li>
                    <form id="editInfo" action="edit_info.php" method="post">
                        <input type="hidden" name="patientID" value="<?php echo $patientID; ?>">
                        <input type="submit" name="editInfo" value="Edit">
                    </form>
                </li>
            </ul>
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
                    SET PractitionerID = $pracID, Status = '$medStatus', MedTime = #$admTime#
                    WHERE ID = $medAdminID;";
                    $rs  = odbc_exec($conn, $sql);
                    echo odbc_errormsg($conn);
                } else if(isset($_POST['dietEdit'])) {
                    if (!$conn) {
                        odbc_close($conn);
                        exit("Connection Failed: ".odbc_errormsg());
                    }
                    // Update database with status
                    $sql = "UPDATE DietAdministration
                    SET PractitionerID = $pracID, Status = '$dietStatus', DietTime = #$admTime#
                    WHERE ID = $dietAdminID;";
                    $rs  = odbc_exec($conn, $sql);
                    echo odbc_errormsg($conn);
                }

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
                echo "<table class='styled-table'>
                <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Practitioner</th>
                <th>Name</th>
                <th>Dosage</th>
                <th>Route</th>
                <th>Round</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                </tr>";
                while($row = odbc_fetch_array($rs)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $patientName . "</td>";
                    if (isset($row['PractitionerID'])) {
                        $pID = $row['PractitionerID'];
                        $sql = "SELECT Name FROM Practitioner WHERE ID = $pID";
                        $pracNameQ  = odbc_exec($conn, $sql);
                        while($rowMed = odbc_fetch_array($pracNameQ)) {
                            echo "<td>" . $rowMed['Name'] . "</td>";
                        }
                    } else {
                        echo "<td>-</td>";
                    }
                    $medID = $row['MedID'];
                    $sql = "SELECT MedName, Dosage, Route FROM Medication WHERE ID = $medID";
                    $medNameQ  = odbc_exec($conn, $sql);
                    while($rowMed = odbc_fetch_array($medNameQ)) {
                        echo "<td>" . $rowMed['MedName'] . "</td>";
                        echo "<td>" . $rowMed['Dosage'] . "</td>";
                        echo "<td>" . $rowMed['Route'] . "</td>";
                    }
                    echo "<td>" . $row['Round'] . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['MedDate'])) . "</td>";
                    if (isset($row['MedTime'])) {
                        $time = explode(' ', $row['MedTime']);
                        echo "<td>" . $time[1] . "</td>";
                    } else {
                        echo "<td>-</td>";
                    }
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
                echo "<table class='styled-table'>
                <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Practitioner</th>
                <th>Name</th>
                <th>Amount/Day</th>
                <th>Round</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                </tr>";
                while($row = odbc_fetch_array($rs)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $patientName . "</td>";
                    if (isset($row['PractitionerID'])) {
                        $pID = $row['PractitionerID'];
                        $sql = "SELECT Name FROM Practitioner WHERE ID = $pID";
                        $pracNameQ  = odbc_exec($conn, $sql);
                        while($rowMed = odbc_fetch_array($pracNameQ)) {
                            echo "<td>" . $rowMed['Name'] . "</td>";
                        }
                    } else {
                        echo "<td>-</td>";
                    }
                    $dietID = $row['DietID'];
                    $sql = "SELECT DietName, [Amount/Day] FROM Diet WHERE ID = $dietID";
                    $dietNameQ  = odbc_exec($conn, $sql);
                    while($rowDiet = odbc_fetch_array($dietNameQ)) {
                        echo "<td>" . $rowDiet['DietName'] . "</td>";
                        echo "<td>" . $rowDiet['Amount/Day'] . "</td>";
                    }
                    // echo "<td>" . $row['DietID'] . "</td>";
                    echo "<td>" . $row['Round'] . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['DietDate'])) . "</td>";
                    if (isset($row['DietTime'])) {
                        $time = explode(' ', $row['DietTime']);
                        echo "<td>" . $time[1] . "</td>";
                    } else {
                        echo "<td>-</td>";
                    }
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