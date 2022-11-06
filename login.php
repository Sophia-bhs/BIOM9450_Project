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
        
    if (isset($_POST['name'])) {
        $name = $_POST['name'];
        // Check if practitioner login info correct
        // $sql = "";
        // $query = mysqli_query($conn, $sql);
        // if ($query) {
        //     echo 'Entry Successful';
        // } else {
        //     echo 'Error Occurred';
        // }
    }

    odbc_close($conn);

    // if valid login, lead to main page
    header("Location: main.php");
?>
