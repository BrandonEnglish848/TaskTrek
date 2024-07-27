<?php 
if(session_status()!=2){ session_start(); }

require 'validator.php';

// Ensure a user arrived with fully filled variables.
// Description can be empty, so let's not check it.
if (!empty($_POST['title']) && !empty($_POST['time']) && !empty($_POST['status']) && isset($_POST['description'])){

    // Open the tasks file.
    if (($tasks_file_handle = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'a+')) !== false) {
        
        // This will be used to show whether or not the script has found a 
        // task with the same title as the new title.
        $found_task = false;
        
        $line_count = 0;
        $last_line = [];
        
        while (($line = fgetcsv($tasks_file_handle)) !== false) {
            
            $line_count++;
            
            $last_line = $line;
            
            // If the current task's title matches the new title
            // Set $found_task as true and end loop.
            if (isset($line[0]) && $line[0] == $_POST['title']){
                
                    $found_task = true;
                    break;

            }
            
        }//End While Loop.
        
        if ($found_task === false){
            
            // If there is only one line in the tasks file, and that task is still
            // titled "Create your first task". Delete the contents of the tasks file.
            if ($line_count == 1 && isset($last_line[0]) && $last_line[0] == "Create Your First Task"){
                
                // Close the tasks file.
                fclose($tasks_file_handle);
                
                // Reopen the file for writing, but use the 'w' to clear the contents first
                $tasks_file_handle = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'w');
            
            }
            
            // first column will be a string containing the task's title
            // second column will be a string containing the task's description
            // third column will be a string containing the task's deadline in a datetime format
            // fourth column will be a string containing either complete, incomplete, or in progress.
            $new_task = array(
                $_POST['title'],
                $_POST['description'],
                $_POST['time'],
                $_POST['status']);
                
            // Attempt to append the data to the task file.
            if (fputcsv($tasks_file_handle, $new_task) !== false){
                
                fclose($tasks_file_handle);
                
                // Clear any variables the user may have submitted.
                unset($_POST, $_GET);
                
                // Send the user back to the home page, with success code: CreatedNewTask.
                exit(header('Location: home.php?success=CreatedNewTask'));
            
            // Failed to append data to the task file. Report this error.
            } else {
                
                // Clear any variables the user may have submitted.
                unset($_POST, $_GET);
                
                // Send the user back to the home page, with error code: CouldNotWriteTaskFile.
                exit(header('Location: home.php?error=CouldNotWriteTaskFile'));
            }
            
        // The script has found a task with that title. Report this error.
        } else {
            
            // Clear any variables the user may have submitted.
            unset($_POST, $_GET);
            
            // Send the user back to the home page, with error code: DuplicateTaskTitle.
            exit(header('Location: home.php?error=DuplicateTaskTitle'));
        }
        
    // Failed to open the task file. Report this error
    } else {
        
        // Clear any variables the user may have submitted.
        unset($_POST, $_GET);
        
        // Send the user back to the home page, with error code: CouldNotOpenTaskFile.
        exit(header('Location: home.php?error=CouldNotOpenTaskFile'));
        
    }
}
?>