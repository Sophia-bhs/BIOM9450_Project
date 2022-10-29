<?php
    if($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['submit'])) {
        $conn = mysqli_connect('localhost', 'root', '', 'medications_and_diet') or die("Connection Failed:".mysqli_connect_error());
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $sql = "INSERT INTO patient (FName) VALUES ('$name')";
            $query = mysqli_query($conn, $sql);
            if ($query) {
                echo 'Entry Successful';
            } else {
                echo 'Error Occurred';
            }
        }
    }
?>
