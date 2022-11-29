<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Major Project Webpage</title>
        <link href="main.css" rel="stylesheet" type="text/css">
	</head>

    <body bgcolor="#E7FBFC">
		<?php
			session_start();
			if($_SESSION['status']!="Active") {
    			header("location:index.php");
			}
			$PracID = $_SESSION['PracID'];
			$PracName = $_SESSION['PracName'];
		?>
		<div id="header">
			<h1>
				Patient Med Administration
			</h1>
		</div>

		<div id="navigation">
			<!-- <p><strong>Navigation Menu</strong></p> -->
			<button class="tablinks" onclick="openTab(event, 'Home')" id="defaultOpen">Home</button>
			<button class="tablinks" onclick="openTab(event, 'Medication')">Medication</button>
			<button class="tablinks" onclick="openTab(event, 'Diet')">Diet</button>
			<button class="tablinks" onclick="openTab(event, 'Patients')">Patients</button>

			<!-- <ul>
				<li>Home</li>
				<li>Client List</li>
				<li>Org Med Reports</li>
			</ul> -->
		</div>
		
		<div id="Home" class="tabcontent">
			<?php
				include('home.php');
			?>
		</div>
		
		<div id="Medication" class="tabcontent">
			<?php
				include('medication.php');
			?>
		</div>
		
		<div id="Diet" class="tabcontent">
			<?php
				include('diet.php');
			?>
		</div>
		
		<div id="Patients" class="tabcontent">
			<?php
				include('patients.php');
			?>
		</div>

		<div id="footer">
			<p>
			<?php
			echo "Practitioner: $PracName";
			?>
			Click here to <a href="logout.php" tite="Logout">Logout.
			</p>
		</div>

		<script>
			function openTab(evt, tabName) {
				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName("tabcontent");
				// hide tabcontent divs
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				// inactivate all tablinks
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				// activate current tab link
				document.getElementById(tabName).style.display = "block";
				evt.currentTarget.className += " active";
			}
			document.getElementById("defaultOpen").click();
		</script>
	</body>
</html>
