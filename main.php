<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Major Project Webpage</title>
        <link href="CSS/main.css" rel="stylesheet" type="text/css">
	</head>
	<script>
		$(function() {
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			localStorage.setItem('lastTab', $(this).attr('href'));
		});
		var lastTab = localStorage.getItem('lastTab');
		
		if (lastTab) {
			$('[href="' + lastTab + '"]').tab('show');
		}
		
		});
</script>
    <body bgcolor="#E7FBFC">
		<?php
			session_start();
			if($_SESSION['status']!="Active") {
    			header("location:index.php");
			}
			$PracID = $_SESSION['PracID'];
			$PracName = $_SESSION['PracName'];
		?>

		<div class="PatientMedAd" id="header">
			<h1>
				Patient Med Administration
			</h1>
		</div>

		<div id="Footer">
			<?php
				include('footer.php');
			?>
		</div>
		
	</body>
</html>