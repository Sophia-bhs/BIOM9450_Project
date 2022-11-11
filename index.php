<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Major Project Login Webpage</title>
        <link href="login.css" rel="stylesheet" type="text/css">
	</head>

    <center>
        <body bgcolor="#E7FBFC">
            <h1>
                Practitioner Login
            </h1>
            <form action="login.php" method="POST">
                <div class="container">
                  <label for="email"><b>email</b></label>
                  <input type="text" placeholder="Enter Email" name="email" required>
              
                  <label for="password"><b>Password</b></label>
                  <input type="password" placeholder="Enter Password" name="password" required>
                      
                  <button type="submit" name="submit" value="Submit">Login</button>
                </div>
              </form>
        </body>
    </center>
</html>