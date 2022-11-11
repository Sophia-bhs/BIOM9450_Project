<?php
    if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
        // $email = $_POST['email'];
        // $password = $_POST['password'];
        // $name = $_POST['name'];
    } else {
        exit("Request method error");
    }
    // $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC);
    // if (!$conn) {
    //     odbc_close($conn);
    //     exit("Connection Failed: ".odbc_errormsg());
    // }
    // Check if email exists
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        // echo $email;
        // Check if practitioner login info correct
        // $sql = "";
        // $query = odbc_exec($conn, $sql);
        // if ($query) {
        //     echo 'Entry Successful';
        // } else {
        //     echo 'Error Occurred';
        // }
    } 
    // Check if password correct for existing email
    // odbc_close($conn);

    // if valid login, lead to main page
    header("Location: main.php");
?>
