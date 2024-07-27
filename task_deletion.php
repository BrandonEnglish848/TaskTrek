<?php 
if(session_status()!=2){ session_start(); }

require 'validator.php';

if (isset($_POST['title']) && !empty($_POST['title'])){
    
    // Open the tasks.txt file.
    if (($tasks_file_handle = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'a+')) !== false) {
        
        // Open the temp_tasks.txt file.
        if (($temp_file_handle = fopen('storage/'.$_SESSION['Username'].'/taskstemp.txt', 'w')) !== false){
            
            // This will be used to show whether or not the script has found a 
            // task with the same title as the new title.
            $found_task = false;
            
            while (($line = fgetcsv($tasks_file_handle)) !== false) {
                
                // If the current task's title matches the new title
                if (isset($line[0]) && $line[0] == $_POST['title']){

                        // Set $found_task as true. Do NOT append it to the temporary file.
                        $found_task = true;
                        
                } else {
                    
                    // The title does not match, so append the current line to the temporary file.
                    fputcsv($temp_file_handle, $line);
                    
                }
                
            }//End While Loop.
            
            // Done processing tasks.
            // Close the files.
            fclose($temp_file_handle);
            fclose($tasks_file_handle);
            
            // Check to make sure the task was found and was not output to the temporary file.
            if ($found_task) {
                    
                // Replace the existing data.
                copy('storage/'.$_SESSION['Username'].'/taskstemp.txt', 'storage/'.$_SESSION['Username'].'/tasks.txt');
                
                // Erase temp file.
                file_put_contents('storage/'.$_SESSION['Username'].'/taskstemp.txt', "");
                
                // Clear any variables the user may have submitted.
                unset($_POST, $_GET);
                
                // Send the user back to their home.php page.
                exit(header('Location: home.php?success=DeletedALine'));
            
            // The task was not found. Report the error.
            } else {
                
                // Erase temp file.
                file_put_contents('storage/'.$_SESSION['Username'].'/taskstemp.txt', "");
                
                // Clear any variables the user may have submitted.
                unset($_POST, $_GET);
                
                // Send the user back to the home page, with error code: CouldNotDeleteNoTitleFound.
                exit(header('Location: home.php?error=CouldNotDeleteNoTitleFound'));
                
            }
            
        } else {
                
            // Clear any variables the user may have submitted.
            unset($_POST, $_GET);
            
            // Send the user back to the home page, with error code: CouldNotLoadTasksTemp.
            exit(header('Location: home.php?error=CouldNotLoadTasksTemp'));
            
        }
        
    }
    
}
?>