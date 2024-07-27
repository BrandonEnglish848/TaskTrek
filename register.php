<?php if(session_status()!=2){ session_start(); } ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/x-icon" href="images/favicon.ico">
		<link rel="stylesheet" href="index.css">
		<title>Register | TaskTrek</title>
	</head>
	<body>
		<div class="background-image">
    		<div class="form-container">
    			<fieldset>
    				<!-- Form title -->
    				<legend>
    					<h2>Register</h2>
    				</legend>
    				<!-- Start of registration form -->
    				<form action="registration_process.php" method="post">
    					<!-- Name field -->
    					<label for="nom">Name:</label>
    					<input type="text" id="nom" name="nom" required>
    					<!-- Surname field -->
    					<label for="prenom">Surname:</label>
    					<input type="text" id="prenom" name="prenom" required>
    					<!-- Email address field -->
    					<label for="email">Email address:</label>
    					<input type="email" id="email" name="email" required>
    					<!-- Password field -->
    					<label for="password">Password:</label>
    					<input type="password" id="password" name="password" required>
    					<!-- Form submit button -->
    					<input type="submit" value="Register">
    				</form>
    				<!-- End of registration form -->
    				<form action="index.php" method="get"><input type="submit" value="Login Instead"></form>
    			</fieldset>
    		</div>
		</div>
	</body>
</html>
