<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset = "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Date</title>
    </head>
    <body>
        <form id="chooseDate" action="selected_date.php" method="post">
            <input type="date" name="selectedDate" min='2000-01-01' max='2025-12-31' onblur='chooseDate.submit()'>
            <noscript>
                <input type="submit" value="Submit">
            </noscript>
        </form>
    </body>
</html>
