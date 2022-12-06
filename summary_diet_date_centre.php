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
    function ODBC_Results_Data_Diet($res, $sTable, $sRow){
        $cFields = odbc_num_fields($res);
        $strTable = "<table class='styled-table' $sTable ><tr>"; 
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
                // define variables to empty values and defalt values
                $patientName = "ALL";
                $inputDate = $today = date("Y/m/d");
                $date = date_create($today);
                $patientNameDiet = "ALL";
                $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC); 
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
                    $patientName = $_SESSION["patientNameDiet"];  
                }
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
                            <?php 
                                $patientNames = "SELECT PatientName FROM Patient ORDER BY PatientName";
                                $patientNamesResult  = odbc_exec($conn, $patientNames);
                                $firstPatient = true;
                                while ($row = odbc_fetch_array($patientNamesResult)) {
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
                    </div>
                    <div class="grid-item">
                        <label for="pracName">Select Practitioner:</label>
                    </div>
                    <div class="grid-item">
                        <select name="pracName" id="pracName">
                            <option value='ALL' selected> ALL </option>
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
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'N/A',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'N/A',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'N/A',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'N/A',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'N/A',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'N/A',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'N/A',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        WHERE P.PatientName = '$patientNameDiet' AND P1.Name='$pracName' AND P2.Name='$pracName' AND P3.Name='$pracName' AND P4.Name='$pracName'
                        AND P5.Name='$pracName' AND P6.Name='$pracName' AND P7.Name='$pracName'
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected all patients and practitioners
                    elseif($patientNameDiet=="ALL" AND $pracName=="ALL") {
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'N/A',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'N/A',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'N/A',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'N/A',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'N/A',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'N/A',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'N/A',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected all patients and specific practitioner
                    elseif($patientNameDiet=="ALL" AND $pracName!="ALL") {
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'N/A',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'N/A',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'N/A',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'N/A',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'N/A',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'N/A',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'N/A',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        WHERE P1.Name='$pracName' AND P2.Name='$pracName' AND P3.Name='$pracName' AND P4.Name='$pracName'
                        AND P5.Name='$pracName' AND P6.Name='$pracName' AND P7.Name='$pracName'
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // When selected specific patient and ally
                    elseif($patientNameDiet!="ALL" AND $pracName=="ALL") {
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'N/A',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'N/A',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'N/A',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'N/A',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'N/A',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'N/A',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'N/A',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        WHERE P.PatientName = '$patientNameDiet'
                        ORDER BY P.PatientName, D.DietName;";
                        $summaryRs = odbc_exec($conn, $summaryQuery);
                        echo ODBC_Results_Data_Diet($summaryRs, null, null);
                    }
                    // Default to all patients with logged in practitioner selected
                    else{
                        $summaryQuery="SELECT P.PatientName AS [Patient], D.DietName AS Diet, 
                        D.[Amount/Day] AS [Amount/Day], DA.Round AS Round, 
                        IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                        IIf([Day1.Status] Is Null,'N/A',[Day1.Status]) AS [$date1Name Status], 
                        IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                        IIf([Day2.Status] Is Null,'N/A',[Day2.Status]) AS [$date2Name Status], 
                        IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                        IIf([Day3.Status] Is Null,'N/A',[Day3.Status]) AS [$date3Name Status], 
                        IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                        IIf([Day4.Status] Is Null,'N/A',[Day4.Status]) AS [$date4Name Status], 
                        IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                        IIf([Day5.Status] Is Null,'N/A',[Day5.Status]) AS [$date5Name Status], 
                        IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                        IIf([Day6.Status] Is Null,'N/A',[Day6.Status]) AS [$date6Name Status], 
                        IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                        IIf([Day7.Status] Is Null,'N/A',[Day7.Status]) AS [$date7Name Status]
                        FROM ((((((((((((((((SELECT DISTINCT PatientID, DietID, Round FROM DietAdministration) AS DA 
                        INNER JOIN Patient AS P ON DA.PatientID = P.ID) 
                        INNER JOIN Diet AS D ON DA.DietID = D.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date1Str#) AS Day1 ON (DA.DietID = Day1.DietID) AND (DA.PatientID = Day1.PatientID)) 
                        LEFT JOIN Practitioner AS P1 ON Day1.PractitionerID = P1.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date2Str#) AS Day2 ON DA.DietID = Day2.DietID AND (DA.PatientID = Day2.PatientID)) 
                        LEFT JOIN Practitioner AS P2 ON Day2.PractitionerID = P2.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date3Str#) AS Day3 ON DA.DietID = Day3.DietID AND (DA.PatientID = Day3.PatientID)) 
                        LEFT JOIN Practitioner AS P3 ON Day3.PractitionerID = P3.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date4Str#) AS Day4 ON DA.DietID = Day4.DietID AND (DA.PatientID = Day4.PatientID)) 
                        LEFT JOIN Practitioner AS P4 ON Day4.PractitionerID = P4.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date5Str#) AS Day5 ON DA.DietID = Day5.DietID AND (DA.PatientID = Day5.PatientID)) 
                        LEFT JOIN Practitioner AS P5 ON Day5.PractitionerID = P5.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date6Str#) AS Day6 ON DA.DietID = Day6.DietID AND (DA.PatientID = Day6.PatientID)) 
                        LEFT JOIN Practitioner AS P6 ON Day6.PractitionerID = P6.ID) 
                        LEFT JOIN (SELECT * FROM DietAdministration WHERE DietDate=#$date7Str#) AS Day7 ON DA.DietID = Day7.DietID AND (DA.PatientID = Day7.PatientID)) 
                        LEFT JOIN Practitioner AS P7 ON Day7.PractitionerID = P7.ID
                        WHERE P.PatientName = '$patientNameDiet' AND P1.Name='$pracName' AND P2.Name='$pracName' AND P3.Name='$pracName' AND P4.Name='$pracName'
                        AND P5.Name='$pracName' AND P6.Name='$pracName' AND P7.Name='$pracName'
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