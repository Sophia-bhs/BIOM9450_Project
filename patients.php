<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patients</title>
</head>

<h1> Patients </h1>
<body>
    <p>Here are all our patients: </p>
    <?php
        // // Display all patients in the database
        // $conn = odbc_connect('z5262083','' ,'' ,SQL_CUR_USE_ODBC);
        // if (!$conn) {
        //     odbc_close($conn);
        //     exit("Connection Failed: ".odbc_errormsg());
        // }
        // $sql = "SELECT * FROM Patients";
		// $rs  = odbc_exec($conn,$sql);  

        // Display in table and make table content clickable
        // or
        // Make the patient list a form - hit submit at the end

		// echo "<table border-collapse: collapse>
		// <tr>
		// <th>Name</th>
		// <th>Email</th>
		// </tr>";
		// while($row = odbc_fetch_array($rs)) {
		// 	echo "<tr>";
		// 	echo "<td>" . $row['FirstName'] . " " . $row['LastName'] . "</td>";
		// 	echo "<td>" . $row['Email'] . "</td>";
		// 	echo "</tr>";
		// }
		// echo "</table>";
        // odbc_close($conn);

        // Lead to patient page
        // header("Location: main.php");
    ?>

</body>
</html>