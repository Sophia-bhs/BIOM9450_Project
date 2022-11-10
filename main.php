<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body {font-family: Arial;}

    /* Style the tab */
    .tab {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
        background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }
</style>
</head>
<body>

<h1>Medication and Diet Regime Management System</h1>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Medication')" id="defaultOpen">Medication</button>
  <button class="tablinks" onclick="openTab(event, 'Diet')">Diet</button>
  <button class="tablinks" onclick="openTab(event, 'Patients')">Patients</button>
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

<script>
    function openTab(evt, cityName) {
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
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    document.getElementById("defaultOpen").click();
</script>
   
</body>
</html> 
