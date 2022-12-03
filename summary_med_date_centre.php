<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="lists.css" rel="stylesheet" type="text/css">
</head>

<?php
    function ODBC_Results_Data($res, $sTable, $sRow){
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

<body>
    <div class="listing">
        <h1>
            Medication Summary
        </h1>
        <div id="wrap_list">
        <?php  
            // define variables to empty values and defalt values
            $today = date("Y/m/d");
            $date = date_create($today);
            $patientName = "ALL";
            $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC); 
            if (!$conn) {
                odbc_close($conn);
                exit("Connection Failed: ".odbc_errormsg());
            }
            if(isset($_POST['submitMedSum'])){ //check if form was submitted
                $inputDate = $_POST['centreDate']; //get input text
                $date = date_create($inputDate);
                $patientName = $_POST['patientName'];
            }
            $date1 = clone date_sub($date, date_interval_create_from_date_string("3 days"));
            $date2 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            $date3 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            $date4 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            $date5 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            $date6 = clone date_add($date, date_interval_create_from_date_string("1 days"));
            $date7 = clone date_add($date, date_interval_create_from_date_string("1 days"));
        ?>
        <form id="chooseDate" method="post">
            <div class="grid-container">
                <div class="grid-item">
                    <label>Centre By:</label>
                </div>
                <div class="grid-item">
                    <input type="date" name="centreDate" min='2022-01-01' max='2025-12-31'>
                </div>
                <div class="grid-item">
                    <label for="patientName">Select Patient:</label>
                </div>
                <div class="grid-item">
                    <select name="patientName" id="patientName">
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
                    <input type="submit" name="submitMedSum" value="Search">
                </div>
                <div class="grid-item"></div>
            </div>
        </form>
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
            if($patientName!="ALL"){
                $summaryQuery="SELECT P.PatientName AS [Patient], M.MedName AS Medication, 
                M.Dosage AS Dosage, M.Route AS Route, MA.Round AS Round, 
                IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                IIf([D1.Status] Is Null,'N/A',[D1.Status]) AS [$date1Name Status], 
                IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                IIf([D2.Status] Is Null,'N/A',[D2.Status]) AS [$date2Name Status], 
                IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                IIf([D3.Status] Is Null,'N/A',[D3.Status]) AS [$date3Name Status], 
                IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                IIf([D4.Status] Is Null,'N/A',[D4.Status]) AS [$date4Name Status], 
                IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                IIf([D5.Status] Is Null,'N/A',[D5.Status]) AS [$date5Name Status], 
                IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                IIf([D6.Status] Is Null,'N/A',[D6.Status]) AS [$date6Name Status], 
                IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                IIf([D7.Status] Is Null,'N/A',[D7.Status]) AS [$date7Name Status]
                FROM ((((((((((((((((SELECT DISTINCT PatientID, MedID, Round FROM MedAdministration) AS MA 
                INNER JOIN Patient AS P ON MA.PatientID = P.ID) 
                INNER JOIN Medication AS M ON MA.MedID = M.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date1Str#) AS D1 ON (MA.MedID = D1.MedID) AND (MA.PatientID = D1.PatientID)) 
                LEFT JOIN Practitioner AS P1 ON D1.PractitionerID = P1.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date2Str#) AS D2 ON MA.MedID = D2.MedID AND (MA.PatientID = D2.PatientID)) 
                LEFT JOIN Practitioner AS P2 ON D2.PractitionerID = P2.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date3Str#) AS D3 ON MA.MedID = D3.MedID AND (MA.PatientID = D3.PatientID)) 
                LEFT JOIN Practitioner AS P3 ON D3.PractitionerID = P3.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date4Str#) AS D4 ON MA.MedID = D4.MedID AND (MA.PatientID = D4.PatientID)) 
                LEFT JOIN Practitioner AS P4 ON D4.PractitionerID = P4.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date5Str#) AS D5 ON MA.MedID = D5.MedID AND (MA.PatientID = D5.PatientID)) 
                LEFT JOIN Practitioner AS P5 ON D5.PractitionerID = P5.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date6Str#) AS D6 ON MA.MedID = D6.MedID AND (MA.PatientID = D6.PatientID)) 
                LEFT JOIN Practitioner AS P6 ON D6.PractitionerID = P6.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date7Str#) AS D7 ON MA.MedID = D7.MedID AND (MA.PatientID = D7.PatientID)) 
                LEFT JOIN Practitioner AS P7 ON D7.PractitionerID = P7.ID
                WHERE P.PatientName = '$patientName'
                ORDER BY P.PatientName, M.MedName;";
                $summaryRs = odbc_exec($conn, $summaryQuery);
                echo ODBC_Results_Data($summaryRs, null, null);
            }
            else{
                $summaryQuery="SELECT P.PatientName AS [Patient], M.MedName AS Medication, 
                M.Dosage AS Dosage, M.Route AS Route, MA.Round AS Round, 
                IIf([P1.Name] Is Null,'N/A',[P1.Name]) AS [$date1Name Practitioner], 
                IIf([D1.Status] Is Null,'N/A',[D1.Status]) AS [$date1Name Status], 
                IIf([P2.Name] Is Null,'N/A',[P2.Name]) AS [$date2Name Practitioner], 
                IIf([D2.Status] Is Null,'N/A',[D2.Status]) AS [$date2Name Status], 
                IIf([P3.Name] Is Null,'N/A',[P3.Name]) AS [$date3Name Practitioner], 
                IIf([D3.Status] Is Null,'N/A',[D3.Status]) AS [$date3Name Status], 
                IIf([P4.Name] Is Null,'N/A',[P4.Name]) AS [$date4Name Practitioner], 
                IIf([D4.Status] Is Null,'N/A',[D4.Status]) AS [$date4Name Status], 
                IIf([P5.Name] Is Null,'N/A',[P5.Name]) AS [$date5Name Practitioner], 
                IIf([D5.Status] Is Null,'N/A',[D5.Status]) AS [$date5Name Status], 
                IIf([P6.Name] Is Null,'N/A',[P6.Name]) AS [$date6Name Practitioner], 
                IIf([D6.Status] Is Null,'N/A',[D6.Status]) AS [$date6Name Status], 
                IIf([P7.Name] Is Null,'N/A',[P7.Name]) AS [$date7Name Practitioner], 
                IIf([D7.Status] Is Null,'N/A',[D7.Status]) AS [$date7Name Status]
                FROM ((((((((((((((((SELECT DISTINCT PatientID, MedID, Round FROM MedAdministration) AS MA 
                INNER JOIN Patient AS P ON MA.PatientID = P.ID) 
                INNER JOIN Medication AS M ON MA.MedID = M.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date1Str#) AS D1 ON (MA.MedID = D1.MedID) AND (MA.PatientID = D1.PatientID)) 
                LEFT JOIN Practitioner AS P1 ON D1.PractitionerID = P1.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date2Str#) AS D2 ON MA.MedID = D2.MedID AND (MA.PatientID = D2.PatientID)) 
                LEFT JOIN Practitioner AS P2 ON D2.PractitionerID = P2.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date3Str#) AS D3 ON MA.MedID = D3.MedID AND (MA.PatientID = D3.PatientID)) 
                LEFT JOIN Practitioner AS P3 ON D3.PractitionerID = P3.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date4Str#) AS D4 ON MA.MedID = D4.MedID AND (MA.PatientID = D4.PatientID)) 
                LEFT JOIN Practitioner AS P4 ON D4.PractitionerID = P4.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date5Str#) AS D5 ON MA.MedID = D5.MedID AND (MA.PatientID = D5.PatientID)) 
                LEFT JOIN Practitioner AS P5 ON D5.PractitionerID = P5.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date6Str#) AS D6 ON MA.MedID = D6.MedID AND (MA.PatientID = D6.PatientID)) 
                LEFT JOIN Practitioner AS P6 ON D6.PractitionerID = P6.ID) 
                LEFT JOIN (SELECT * FROM MedAdministration WHERE MedDate=#$date7Str#) AS D7 ON MA.MedID = D7.MedID AND (MA.PatientID = D7.PatientID))
                LEFT JOIN Practitioner AS P7 ON D7.PractitionerID = P7.ID
                ORDER BY P.PatientName, M.MedName;";
                $summaryRs = odbc_exec($conn, $summaryQuery);
                echo ODBC_Results_Data($summaryRs, null, null);
            }
        ?>
        </div>
    </div>
</body>
</html>