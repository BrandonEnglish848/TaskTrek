<?php if (session_status() != 2) {
    session_start();
}

// Handle error codes
require "load_error_and_success_codes.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="styles.css">
		<title>Login | TaskTrek </title>
	</head>
	<body>
	    <?php
     if (isset($error_message)) {
         echo $error_message;
     }
     if (isset($success_message)) {
         echo $success_message;
     }
     ?>
		<div class="background-image">
            <ul class="nav-bar">
                <li><a href="https://enrichhosting.us/"><img src="../favicon-removebg.png" style="max-height: 2.5em;"></a></li>
            </ul>
            <div class="form-container" id="thin">
                <fieldset>
                    <!-- Form title -->
                    <legend>
                        <h2>Login</h2>
                    </legend>
                    <!-- Start of login form -->
                    <form action="login_process.php" method="post">
                        <!-- Email address field -->
                        <label for="email">Email address:</label>
                        <input type="email" id="email" name="email" required>
                        <!-- Password field -->
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <!-- Form submit button -->
                        <div class="login">
                            <input type="submit" value="Login" style="min-width: 80%;">
                        </div>
                    </form>
                    <form action="register.php" method="get">
                        <div class="register">
                            <input type="submit" value="Register Instead"  style="min-width: 80%;">
                        </div>
                    </form>
    			</fieldset>
    			<!-- End of login form -->
    		</div>
    	</div>
	</body>
</html>
