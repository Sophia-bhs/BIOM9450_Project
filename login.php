<?php
    if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
        // Storing data entered into variables
        $email = $_POST['email'];
        $password = $_POST['password'];
    } else {
        exit("Request method error");
    }
    $conn = odbc_connect('z5209691','' ,'' ,SQL_CUR_USE_ODBC);
    if (!$conn) {
        odbc_close($conn);
        exit("Connection Failed: ".odbc_errormsg());
    }
    // Check if practitioner login info correct
    // SQL command to match Email
    $sqlEmailMatch="SELECT * FROM Practitioner WHERE Email = '".$email."'";
    // SQL command to match Password
    $sqlPswMatch="SELECT * FROM Practitioner WHERE StrComp(Practitioner.Password,'".$password."',0) = 0";
    // Execute sql commands
    $execEmail=odbc_exec($conn,$sqlEmailMatch);
    $execPsw=odbc_exec($conn,$sqlPswMatch);
    // Find number of rows if there are matching emails and password
    $rsEmail=odbc_fetch_row($execEmail);
    $rsPsw=odbc_fetch_row($execPsw);
    // Check if email and password is correct
    if ($rsEmail && $rsPsw) {
        odbc_close($conn);
        // if valid login, lead to main page
        header("Location: main.php");
    }
    else {
        // Create pop up saying incorrect email and/or password
        // Then redirects back to login page
        echo "<script type='text/javascript'>
            alert('Incorrect Email and/or Password');
            window.location = 'index.php';
        </script>";
    }
?>