<?php
    // Server side validation of Login information and error handling
    if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
        // Storing data entered into variables
        $email = $_POST['email'];
        $password = $_POST['password'];
    } else {
        exit("Request method error");
    }
    $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC);
    if (!$conn) {
        odbc_close($conn);
        exit("Connection Failed: ".odbc_errormsg());
    }
    // Check if practitioner login info correct
    // SQL command to match Email
    $sqlEmailMatch = "SELECT * FROM Practitioner WHERE Email = '".$email."'";
    // Execute sql commands
    $execEmail = odbc_exec($conn,$sqlEmailMatch);
    // Find number of rows if there are matching email
    $rsEmail = odbc_fetch_row($execEmail);
    // Check if email and password is correct
    if ($rsEmail && odbc_result($execEmail,4) == $password) {
        // Starting session to store Pracitioner Info
        session_start();
        $_SESSION['status'] = "Active";
        // Stores practitioner ID
        $_SESSION['PracID'] = odbc_result($execEmail,1);
        // Stores practitioner Name
        $_SESSION['PracName'] = odbc_result($execEmail,2);
        odbc_close($conn);
        // if valid login, lead to main page
        header("Location: home.php");
    }
    else {
        // Create pop up saying incorrect email
        // Then redirects back to login page
        echo "<script type='text/javascript'>
            alert('Incorrect Email and/or Password');
            // Redirects back to login Page
            window.location = 'index.php';
        </script>";
    }
?>