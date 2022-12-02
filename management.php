<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management</title>
</head>

<h1> Test </h1>
<body>
<?php  
// define variables to empty values
$nameErr = $dobErr = $genderErr = $roomErr = "";
$name = $dob = $gender = $room ="";  

//Input fields validation  
if ($_SERVER["REQUEST_METHOD"] == "POST") {  
      
//String Validation  
    if (empty($_POST["name"])) {  
         $nameErr = "Name is required";  
    } else {  
        $name = input_data($_POST["name"]);  
            // check if name only contains letters and whitespace  
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {  
                $nameErr = "Only alphabets and white space are allowed";  
            }  
    }
    
    if (empty($_POST["dob"])) {  
        $dobErr = "Date of Birth is required";  
    } else {  
        $dob = input_data($_POST["dob"]);  
            // check if name only contains letters and whitespace  
            if (!preg_match('~^([0-9]{1,2})/([0-9]{1,2})/([0-9]{4})$~', $dob, $parts)) {  
                $dobErr = "The date of birth is not a valid date in the format DD/MM/YYYY";  
            } else if (!checkdate($parts[2],$parts[1],$parts[3])){
                $dobErr = 'The date of birth is invalid. Please check that the month is between 1 and 12, and the day is valid for that month.';
            } else if ($parts[3] > 2022) {
                $dobErr = 'The date of birth is invalid. Please check that the year is not greater than this year';
            }
        $DateArray = explode('/', $dob);
        $day = $DateArray[0];
        $month = $DateArray[1];
        $year = $DateArray[2];
        $dobEur = $day .'-' .$month .'-' .$year;
        $dobFormat = date('Y-m-d', strtotime($dobEur));



        // Calculating the age
        $todaydate = date("Y-m-d");
        $today = date_create($todaydate);
        $dobObj = date_create($dobFormat);
        $diff=date_diff($dobObj,$today);
        $age = $diff->format("%Y");
        
            
            
    }

    //Gender
    if (empty($_POST["gender"])) {  
            $genderErr = "Gender is required";  
    } else {  
            $gender = input_data($_POST["gender"]);  
    }

    // Room numver
    if (empty($_POST["room"])) {  
        $nameErr = "Room Number is required";  
   } else {  
       $room = input_data($_POST["room"]);  
           if (!ctype_digit($room)) {  
               $nameErr = "Only numbers are allowed";  
           }  
   }
}
function input_data($data) {  
    $data = trim($data);  
    $data = stripslashes($data);  
    $data = htmlspecialchars($data);  
    return $data;  
  }  
  
?>  
  
<h2>Registration Form</h2>  
<span class = "error">* required field </span>  
<br><br>  
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" >    
    Name:   
    <input type="text" name="name" placeholder="Enter Name">  
    <span class="error">* <?php echo $nameErr; ?> </span>  
    <br><br>
    Date of Birth:   
    <input type="text" name="dob" placeholder="DD/MM/YYYY">  
    <span class="error">* <?php echo $dobErr; ?> </span>  
    <br><br>    
    Gender:  
    <input type="radio" name="gender" value="male"> Male  
    <input type="radio" name="gender" value="female"> Female  
    <input type="radio" name="gender" value="other"> Other  
    <span class="error">* <?php echo $genderErr; ?> </span>  
    <br><br>
    Room Number:   
    <input type="text" name="room" placeholder="Enter Room Number">  
    <span class="error">* <?php echo $roomErr; ?> </span>  
    <br><br>
    <input type="submit" name="submit" value="Submit">   
    <br><br>                             
</form>
  
<?php  
    if(isset($_POST['submit'])) {  
    if($nameErr == "" && $genderErr == "" && $dobErr == "" && $roomErr == "") {  

        $conn = odbc_connect('z5256089','','',SQL_CUR_USE_ODBC);
        
        $sql = "SELECT * FROM Patient where PatientName = '$name' AND DOB = #$dobFormat#";
        echo odbc_errormsg($conn);
	    $rs = odbc_exec($conn,$sql);
	    if (odbc_fetch_row($rs)) {
            echo "This Patient already exists" . "<br>";
            exit();
	    }

        $sql = "SELECT * FROM Patient where RoomNumber = $room";
        echo odbc_errormsg($conn);
	    $rs = odbc_exec($conn,$sql);
	    if (odbc_fetch_row($rs)) {
            echo "This room already has a patient" . "<br>";
            exit();
	    }


        $sql = "INSERT INTO Patient (PatientName, Age, Gender, DOB, RoomNumber)
            VALUES ('$name', '$age', '$gender', '$dobFormat', '$room')";	
        $rs = odbc_exec($conn,$sql);
        echo odbc_errormsg($conn);

        echo "<h3 color = #FF0001> <b>You have sucessfully registered.</b> </h3>";  
        echo "<h2>Your Input:</h2>";  
        echo "Name: " .$name;  
        echo "<br>";  
        echo "Gender: " .$gender;
        echo "<br>"; 
        echo "Date of Birth : " .$dob;
        echo "<br>"; 
        echo "Age : " .$age;
        echo "<br>";  
        echo "Room Number : " .$room;
        echo "<br>";


    } else {  
        echo "<h3> <b>You didn't filled up the form correctly.</b> </h3>";  
    }  
    } 

?>  
  


</body>  
</html>    
