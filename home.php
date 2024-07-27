<?php 
if(session_status()!=2){ session_start(); } 

// Ensures whoever hits this page is logged in, and has session variables set.
require 'validator.php';

// Attempts to output $user_tasks for the home page to use.
require 'load_tasks.php';

// Handles all error and success codes sent to the home page.
require 'load_error_and_success_codes.php';

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="styles.css">
		<title>Home | TaskTrek</title>
	</head>
	<body>
        <ul class="nav-bar">
            <li><a class="active" href="home.php">Home</a></li>
            <li><a href="create_task.php">Create</a></li>
            <li><a href="search_task.php">Search</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
        </ul>
	    <div class="form-container">
            <h2>Current Tasks</h2>
            <?php
            if (isset($user_tasks) && !empty($user_tasks)){
                echo $user_tasks;
            } else {
                echo "<h2>No Tasks Set!</h2>";
            } ?>
            <form action='home.php' method='get' id='controls_form'>
                <select name=controls onchange='this.form.submit();' id=controls>
                    <option disabled selected>Choose A Sorting Method</option>
                    <option value=None>No Sorting</option>
                    <option value=Status>Sort by Status</option>
                    <option value=Time>Sort by Time</option>
                    <option value=Both>Sort by Status and Time</option>
                </select>
            </form>
        </div>
        <?php if (isset($success_message)){ echo $success_message; } ?>
        <?php if (isset($error_message)){ echo $error_message; } ?>
    </body>
</html>
<?php unset($_GET, $_POST); ?>
