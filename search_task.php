<?php 
if(session_status()!=2){ session_start(); } 

// Ensures whoever hits this page is logged in, and has session variables set.
require 'validator.php';

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="styles.css">
		<title>Search task | TaskTrek</title>
	</head>
	<body>
        <ul class="nav-bar">
            <li><a href="home.php">Home</a></li>
            <li><a href="create_task.php">Create</a></li>
            <li><a class="active" href="search_task.php">Search</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
        </ul>
	    <div class="form-container" id="thin">
	        <fieldset>
	            <legend>
                    <h2>Search Task</h2>
                </legend>
                <form action="home.php" method="post">
                    <label for="title">Task title:</label>
                    <input type="text" name="title" id="title" required>
                    <input type="submit" value="Search For Task Title">
                </form>
                <br>
                <hr>
                <br>
                <form action="home.php" method="post">
                    <label for="status">Task status:</label>
                    <select name='status' id='status' onchange='this.form.submit();' required>
                        <option value="" selected disabled></option>
                        <option value='Complete'>Complete</option>
                        <option value='Incomplete'>Incomplete</option>
                        <option value='In Progress'>In Progress</option>
                    </select>
                    <input type="submit" value="Search For Task Status">
                </form>
            </fieldset>
        </div>
    </body>
</html>