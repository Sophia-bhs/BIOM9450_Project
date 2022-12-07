<!DOCTYPE html>
<html lang="en">
    <!-- Destroy session and return to login page -->
    <?php
        session_start();
        unset($_SESSION["PracID"]);
        unset($_SESSION['PracName']);
        session_destroy();
        header("Location:index.php");
    ?>
</html>