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
		<link rel="stylesheet" href="create.css">
		<title>Create task | TaskTrek</title>
	</head>
	<body>
        <ul class="nav-bar">
            <li><a href="home.php">Home</a></li>
            <li><a class="active" href="create_task.php">Create</a></li>
            <li><a href="search_task.php">Search</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
        </ul>
	    <div class="form-container">
	        <fieldset>
                <legend>
                    <h2>Create Task</h2>
                </legend>
                <form action="task_creation.php" method="post">
                    <label for="title">Enter a title for your task:</label>
                    <input type="text" name="title" id="title" required>
                    <label for="description">Enter a description for your task:</label>
                    <input type="text" name="description" id="description"></input>
                    <label for="time">Choose a deadline for your task:</label>
                    <input type="datetime-local" id="time" name="time" value="2024-06-04T00:00" min="2024-06-04T00:00" max="2040-00-00T00:00" required/>
                    <label for="status">Status:</label>
                    <select name='status' id='status' required>
                        <option value="" selected disabled></option>
                        <option value='Complete'>Complete</option>
                        <option value='Incomplete' selected>Incomplete</option>
                        <option value='In Progress'>In Progress</option>
                    </select>
                    <input type="submit" value="Create New Task">
                </form>
            </fieldset>
        </div>
    </body>
</html>