<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diet Summary</title>
    <link href="CSS/lists.css" rel="stylesheet" type="text/css">
    <link href="CSS/main.css" rel="stylesheet" type="text/css">
</head>

<?php
    // Organise ODBC result into table values
    function ODBC_Results_Data_Diet($res, $sTable, $sRow){
        $cFields = odbc_num_fields($res);
        $strTable = "<table class='summary-styled-table' $sTable ><tr>"; 
        for ($n=1; $n<=$cFields; $n++){
            $strTable .= "<td $sRow><b>".odbc_field_name($res, $n)."</b></td>";
        }
        $strTable .= "</tr>";
        while(odbc_fetch_row($res)){
            $strTable .= "<tr>";
            for ($n=1; $n<=$cFields; $n++){
                $cell = odbc_result($res, $n);
                if ($cell==''){
                    $strTable .= "<td $sRow>&nbsp;</td>";
                }
                else{
                    $strTable .= "<td $sRow>". $cell . "</td>";
                }
            }
            $strTable .= "</tr>";
        }
        $strTable .= "</table>";
        Return $strTable;
    }
?>

<body bgcolor="#E7FBFC">
    <?php
        session_start();
        if($_SESSION['status']!="Active") {
            header("location:index.php");
        }
        $PracID = $_SESSION['PracID'];
        $pracName = $_SESSION['PracName'];
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
        <h1>
            Diet Summary
        </h1>
        <div id="wrap_list">
            <?php  
                // define variables to empty values and default values
                $placeholder='-';
                $patientName = "ALL";
                $inputDate = $today = date("Y/m/d");
                $date = date_create($today);
                $patientNameDiet = "ALL";
                $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC); 
                if (!$conn) {
                    odbc_close($conn);
                    exit("Connection Failed: ".odbc_errormsg());
                }
                if(isset($_POST['submitDiet'])){ //check if form was submitted
                    $_SESSION['centreDate'] = $inputDate = $_POST['centreDate']; //get input text
                    $date = date_create($inputDate);
                    $_SESSION['patientNameDiet'] = $patientNameDiet = $_POST['patientNameDiet'];
                    $pracName = $_POST['pracName'];
                }
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $inputDate = $_SESSION["centreDate"];
                    $patientNameDiet = $_SESSION["patientNameDiet"];  
                }
                // Calculate dates for a week with centered date selected or by default
                $date1 = clone date_sub($date, date_interval_create_from_date_string("3 days"));
                $date2 = clone date_add($date, date_interval_create_from_date_string("1 days"));
                $date3 = clone date_add($date, date_interval_create_from_date_string("1 days"));
                $date4 = clone date_add($date, date_interval_create_from_date_string("1 days"));
                $date5 = clone date_add($date, date_interval_create_from_date_string("1 days"));
                $date6 = clone date_add($date, date_interval_create_from_date_string("1 days"));
                $date7 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            ?>
            <form id="chooseDate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="grid-container">
                    <div class="grid-item">
                        <label>Centre By:</label>
                    </div>
                    <div class="grid-item">
                        <input type="date" name="centreDate" min='2022-01-01' max='2025-12-31' value="<?php echo date('Y-m-d', strtotime($inputDate)); ?>">
                    </div>
                    <div class="grid-item">
                        <label for="patientNameDiet">Select Patient:</label>
                    </div>
                    <div class="grid-item">
                        <select name="patientNameDiet" id="patientNameDiet">
                            <option value='ALL' selected> ALL </option>
                            <!-- Choose from patient list -->
                            <?php 
                                $patientNames = "SELECT PatientName FROM Patient ORDER BY PatientName";
                                $patientNamesResult  = odbc_exec($conn, $patientNames);
                                $firstPatient = true;
                                while ($row = odbc_fetch_array($patientNamesResult)) {
                                    if (isset($patientNameDiet) && $patientNameDiet == $row['PatientName']) {
                                        echo "<option value='".$row['PatientName']."' selected>".$row['PatientName']."</option>";
                                    } else if (!isset($patientNameDiet) && $firstPatient == true) {
                                        echo "<option value='".$row['PatientName']."' selected>".$row['PatientName']."</option>";
                                        $firstPatient = false;
                                    } else {
                                        echo "<option value='".$row['PatientName']."'>".$row['PatientName']."</option>";
                                    }
                                }
                            ?>        
                        </select>
                    </div>
                    <div class="grid-item">
                        <label for="pracName">Select Practitioner:</label>
                    </div>
                    <div class="grid-item">
                        <select name="pracName" id="pracName">
                            <option value='ALL' selected> ALL </option>
                            <!-- Choose from practitioner list -->
                            <?php 
                                $pracNames = "SELECT Name FROM Practitioner ORDER BY Name";
                                $pracNamesResult  = odbc_exec($conn, $pracNames);
                                $firstPrac = true;
                                while ($row = odbc_fetch_array($pracNamesResult)) {
                                    if (isset($pracName) && $pracName == $row['Name']) {
                                        echo "<option value='".$row['Name']."' selected>".$row['Name']."</option>";
                                    } else if (!isset($pracName) && $firstPrac == true) {
                                        echo "<option value='".$row['Name']."' selected>".$row['Name']."</option>";
                                        $firstPrac = false;
                                    } else {
                                        echo "<option value='".$row['Name']."'>".$row['Name']."</option>";
                                    }
                                }
                            ?>        
                        </select>
                    </div>
                    <div class="grid-item">
                        <input type="submit" name="submitDiet" value="Search">
                    </div>
                    <div class="grid-item"></div>
                </div>
            </form>
            <div style="overflow-x:auto;">
                <?php
                    // Format date
                    $date1Name = date_format($date1,"d/m/Y");
                    $date2Name = date_format($date2,"d/m/Y");
                    $date3Name = date_format($date3,"d/m/Y");
                    $date4Name = date_format($date4,"d/m/Y");
                    $date5Name = date_format($date5,"d/m/Y");
                    $date6Name = date_format($date6,"d/m/Y");
                    $date7Name = date_format($date7,"d/m/Y");
                    $date1Str = date_format($date1,"Y-m-d");
                    $date2Str = date_format($date2,"Y-m-d");
                    $date3Str = date_format($date3,"Y-m-d");
                    $date4Str = date_format($date4,"Y-m-d");
                    $date5Str = date_format($date5,"Y-m-d");
                    $date6Str = date_format($date6,"Y-m-d");
                    $date7Str = date_format($date7,"Y-m-d");
                    // When selected specific patient and practitioner
                    if($patientNameDiet!="ALL" AND $pracName!="ALL"){
                        $pracIDQuery="SELECT ID FROM Practitioner WHERE Name='$pracName'";
                        $pracIDRs = odbc_exec($conn, $pracIDQuery);
                        $pracID = odbc_result($pracIDRs,1);
                        $summaryQuery="SELECT P.PatientName AS [Patient], M.MedName AS Medication, 
                    M.Dosage AS Dosage, M.Route AS Route, MA.Round AS Round, 
                    IIf([P1.Name] Is Null,'$placeholder',[P1.Name]) AS [$date1Name Practitioner], 
                    IIf([D1.Status] Is Null,'$placeholder',[D1.Status]) AS [$date1Name Status], 
                    IIf([P2.Name] Is Null,'$placeholder',[P2.Name]) AS [$date2Name Practitioner], 
                    IIf([D2.Status] Is Null,'$placeholder',[D2.Status]) AS [$date2Name Status], 
                    IIf([P3.Name] Is Null,'$placeholder',[P3.Name]) AS [$date3Name Practitioner], 
                    IIf([D3.Status] Is Null,'$placeholder',[D3.Status]) AS [$date3Name Status], 
                    IIf([P4.Name] Is Null,'$placeholder',[P4.Name]) AS [$date4Name Practitioner], 
                    IIf([D4.Status] Is Null,'$placeholder',[D4.Status]) AS [$date4Name Status], 
                    IIf([P5.Name] Is Null,'$placeholder',[P5.Name]) AS [$date5Name Practitioner], 
                    IIf([D5.Status] Is Null,'$placeholder',[D5.Status]) AS [$date5Name Status], 
                    IIf([P6.Name] Is Null,'$placeholder',[P6.Name]) AS [$date6Name Practitioner], 
                    IIf([D6.Status] Is Null,'$placeholder',[D6.Status]) AS [$date6Name Status], 
                    IIf([P7.Name] Is Null,'$placeholder',[P7.Name]) AS [$date7Name Practitioner], 
                    IIf([D7.Status] Is Null,'$placeholder',[D7.Status]) AS [$date7Name Status]
                    FROM ((((((((((((((((SELECT DISTINCT PatientID, MedID, Round FROM MedAdministration) AS MA 
                    INNER JOIN Patient AS P ON MA.PatientID = P.ID) 
                    INNER JOIN Medication AS M ON MA.MedID = M.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date1Str# AND PractitionerID=$pracID)
                     AS D1 ON (MA.MedID = D1.MedID) AND (MA.PatientID = D1.PatientID)) 
                    LEFT JOIN Practitioner AS P1 ON D1.PractitionerID = P1.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date2Str# AND PractitionerID=$pracID)
                     AS D2 ON MA.MedID = D2.MedID AND (MA.PatientID = D2.PatientID)) 
                    LEFT JOIN Practitioner AS P2 ON D2.PractitionerID = P2.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date3Str# AND PractitionerID=$pracID)
                     AS D3 ON MA.MedID = D3.MedID AND (MA.PatientID = D3.PatientID)) 
                    LEFT JOIN Practitioner AS P3 ON D3.PractitionerID = P3.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date4Str# AND PractitionerID=$pracID)
                     AS D4 ON MA.MedID = D4.MedID AND (MA.PatientID = D4.PatientID)) 
                    LEFT JOIN Practitioner AS P4 ON D4.PractitionerID = P4.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date5Str# AND PractitionerID=$pracID)
                     AS D5 ON MA.MedID = D5.MedID AND (MA.PatientID = D5.PatientID)) 
                    LEFT JOIN Practitioner AS P5 ON D5.PractitionerID = P5.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date6Str# AND PractitionerID=$pracID)
                     AS D6 ON MA.MedID = D6.MedID AND (MA.PatientID = D6.PatientID)) 
                    LEFT JOIN Practitioner AS P6 ON D6.PractitionerID = P6.ID) 
                    LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date7Str# AND PractitionerID=$pracID)
                     AS D7 ON MA.MedID = D7.MedID AND (MA.PatientID = D7.PatientID)) 
                    LEFT JOIN Practitioner AS P7 ON D7.PractitionerID = P7.ID
                    WHERE P.PatientName = '$patientNameDiet'
                    ORDER BY P.PatientName, M.MedName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected all patients and practitioners
                    elseif($patientNameDiet=="ALL" AND $pracName=="ALL") {
                        $pracIDQuery="SELECT ID FROM Practitioner WHERE Name='$pracName'";
                        $pracIDRs = odbc_exec($conn, $pracIDQuery);
                        $pracID = odbc_result($pracIDRs,1);
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'$placeholder',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'$placeholder',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'$placeholder',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'$placeholder',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'$placeholder',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'$placeholder',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'$placeholder',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'$placeholder',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'$placeholder',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'$placeholder',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'$placeholder',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'$placeholder',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'$placeholder',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'$placeholder',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) 
                        AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) 
                        AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) 
                        AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) 
                        AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) 
                        AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) 
                        AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) 
                        AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected all patients and specific practitioner
                    elseif($patientNameDiet=="ALL" AND $pracName!="ALL") {
                        $pracIDQuery="SELECT ID FROM Practitioner WHERE Name='$pracName'";
                        $pracIDRs = odbc_exec($conn, $pracIDQuery);
                        $pracID = odbc_result($pracIDRs,1);
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'$placeholder',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'$placeholder',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'$placeholder',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'$placeholder',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'$placeholder',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'$placeholder',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'$placeholder',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'$placeholder',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'$placeholder',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'$placeholder',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'$placeholder',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'$placeholder',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'$placeholder',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'$placeholder',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str# AND PractitionerID=$pracID) 
                        AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str# AND PractitionerID=$pracID) 
                        AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str# AND PractitionerID=$pracID) 
                        AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str# AND PractitionerID=$pracID) 
                        AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str# AND PractitionerID=$pracID) 
                        AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str# AND PractitionerID=$pracID) 
                        AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str# AND PractitionerID=$pracID) 
                        AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected specific patient and all practitioners
                    else{
                        $pracIDQuery="SELECT ID FROM Practitioner WHERE Name='$pracName'";
                        $pracIDRs = odbc_exec($conn, $pracIDQuery);
                        $pracID = odbc_result($pracIDRs,1);
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'$placeholder',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'$placeholder',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'$placeholder',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'$placeholder',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'$placeholder',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'$placeholder',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'$placeholder',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'$placeholder',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'$placeholder',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'$placeholder',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'$placeholder',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'$placeholder',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'$placeholder',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'$placeholder',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) 
                        AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) 
                        AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) 
                        AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) 
                        AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) 
                        AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) 
                        AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) 
                        AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        WHERE P.PatientName = '$patientNameDiet'
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                ?>
            </div>
        </div>
    </div>
    <div id="Footer">
        <?php
            include('footer.php');
        ?>
    </div>
</body>
</html>