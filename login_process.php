<?php 
if(session_status()!=2){ session_start(); }

if (isset($_POST['email']) && isset($_POST['password'])){
    
    if (!empty($_POST['email']) && !empty($_POST['password'])){
        
        // Open the users file
        if (($file_handle = fopen('storage/users.txt', 'r')) !== false) {
            
            // This will change if the user's email matches what we have stored,
            // and if the password they enterred matches the hashed password we have stored.
            $user_exists = false;
            
            // Read each line of the file as a csv
            // When it reads an empty line, it sets $line to false, and the while 
            // loop ends with the $user_exists still set to false.
            while (($line = fgetcsv($file_handle, 1000, ",")) !== false) {
                
                // $line is split into an array with 4 parts:
                // line[0] -> nom
                // line[1] -> prenom
                // line[2] -> email
                // line[3] -> password hash
                
                // Check if the email / username exists in the file
                if ($line[2] == $_POST['email']){
                    
                    // The email using the current line matches the one the user entered.
                    // Now we check that the password the user entered will match
                    // our hashed password in the current line.
                    if (password_verify($_POST['password'], $line[3])) {
                        
                        // Update this so the rest of the code knows we verified the user.
                        $user_exists = true;
                        
                        // Establish Session variables using the verified information.
                        $_SESSION['Nom'] = $line[0];
                        $_SESSION['Prenom'] = $line[1];
                        $_SESSION['Username'] = $line[2];
                        $_SESSION['UserValidated'] = true;
                        
                        // Stop the while loop, the code found the user, so no need to keep looking.
                        break;
                        
                    } else {
                        
                        // The script found the email that the user enterred,
                        // but the password did not match our hashed value.
                        // Send the user back to the home page, but with the Password error code.
                        exit(header('Location: index.php?error=Password'));
                        
                    }
                    
                }
                
            }
            
            // Close the file handle
            fclose($file_handle);
            
            if ($user_exists){
                
                // Send the user to their home page.
                header('Location: home.php');
                
            } else {
                
                // Send the user back to the home page, but with the Password error code.
                exit(header('Location: index.php?error=Password'));
                
            }
            
        } else {
            
            // Send the user back to the home page, but with the NoUserFile error code.
            exit(header('Location: index.php?error=NoUserFile'));
            
        }
        
    } else {
        
        // Send the user back to the home page, but with the MissingUsernameOrPassword error code.
        exit(header('Location: index.php?error=MissingUsernameOrPassword'));
        
    }
    
} else {
    
    // Send the user back to the home page, they must have manually entered this url.
    exit(header('Location: index.php'));
    
}
?>