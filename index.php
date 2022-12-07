<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioner Login</title>
    <link href="CSS/login.css" rel="stylesheet" type="text/css">
</head>
<center>
    <h1> Medication and Diet Regime Management System </h1>
    <body bgcolor="#E7FBFC">
        <h2>
            Login
        </h2>
        <!-- Login form -->
        <form action="login.php" method="POST">
            <div class="container">
                <label><b>Email</b></label>
                <input type="text" placeholder="Enter Email" name="email" id="email" required/>
                <label><b>Password</b></label>
                <input type="password" placeholder="Enter Password" name="password" id="password" required/>

                <button type="submit" name="submit" id="submit">Login</button>
            </div>
        </form>
    </body>
</center>
</html>