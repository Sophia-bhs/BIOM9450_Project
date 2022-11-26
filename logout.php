<!DOCTYPE html>
<html lang="en">
    <?php
        session_start();
        unset($_SESSION["id"]);
        unset($_SESSION["name"]);
        header("Location:index.php");
    ?>
</html>