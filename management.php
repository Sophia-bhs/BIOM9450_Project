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
$nameErr = $dobErr = $genderErr = "";
$name = $dob = $gender = "";  

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
            if (!preg_match('~^([0-9]{2})/([0-9]{2})/([0-9]{4})$~', $dob, $parts)) {  
                $dobErr = "The date of birth is not a valid date in the format DD/MM/YYYY";  
            } else if (!checkdate($parts[2],$parts[1],$parts[3])){
                $dobErr = 'The date of birth is invalid. Please check that the month is between 1 and 12, and the day is valid for that month.';
            }
    }

    //Empty Field Validation  
    if (empty($_POST["gender"])) {  
            $genderErr = "Gender is required";  
    } else {  
            $gender = input_data($_POST["gender"]);  
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
    <input type="text" name="name">  
    <span class="error">* <?php echo $nameErr; ?> </span>  
    <br><br>
    Date of Birth:   
    <input type="text" name="dob">  
    <span class="error">* <?php echo $dobErr; ?> </span>  
    <br><br>    
    Gender:  
    <input type="radio" name="gender" value="male"> Male  
    <input type="radio" name="gender" value="female"> Female  
    <input type="radio" name="gender" value="other"> Other  
    <span class="error">* <?php echo $genderErr; ?> </span>  
    <br><br>
    <input type="submit" name="submit" value="Submit">   
    <br><br>                             
</form>
  
<?php  
    if(isset($_POST['submit'])) {  
    if($nameErr == "" && $genderErr == "" && $dobErr == "") {  
        echo "<h3 color = #FF0001> <b>You have sucessfully registered.</b> </h3>";  
        echo "<h2>Your Input:</h2>";  
        echo "Name: " .$name;  
        echo "<br>";  
        echo "Gender: " .$gender;
        echo "<br>"; 
        echo "Date of Birth : " .$dob;
         
    // $sql = "INSERT INTO Patient (PatientName, Gender, DOB)
	//	VALUES ('$name', '$gender', '$dob')";	
    //    $rs = odbc_exec($conn,$sql);
    //    echo odbc_errormsg($conn);
    } else {  
        echo "<h3> <b>You didn't filled up the form correctly.</b> </h3>";  
    }  
    } 

?>  
  


</body>  
</html>    
