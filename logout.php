<!DOCTYPE html>
<html lang="en">
    <?php
        session_start();
        unset($_SESSION["PracID"]);
        unset($_SESSION['PracName']);
        session_destroy();
        header("Location:index.php");
    ?>
</html>