<?php 
if(session_status()!=2){ session_start(); }

require 'validator.php';

// Ensure a user arrived with fully filled variables.
// Description can be empty, so let's not check it.
if (!empty($_POST['title']) && !empty($_POST['time']) && !empty($_POST['status']) && isset($_POST['description']) && !empty($_POST['original'])){
        
    // If the new title is NOT the same as the old title, then the user
    // is essentially deleting the old task, and replacing it with a new one.
    // This is dangerous because if they are creating a new task, it MUST
    // have a unique title, or task data will overlap and not work as intended.
    if ($_POST['original'] != $_POST['title']){
        
        // Open the tasks file for the initial serach.
        if (($task_file_handle_for_search = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'r+')) !== false) {
            
            // This is used to keep track of finding the title in the tasks.txt file.
            $new_title_already_in_tasks = false;
            
            // Searching for the new title in the current tasks.txt
            // If it is found at all, editting should halt, as this will 
            // cause a duplicate title entry.
            while (($line = fgetcsv($task_file_handle_for_search)) !== false) {
                
                if (isset($line[0]) && $line[0] == $_POST['title']){
                        
                    $new_title_already_in_tasks = true;
                    break;
                    
                }
                    
            }//End While Loop
            
            // Done with the tasks.txt file for now. Closing it.
            fclose($task_file_handle_for_search);                
            
            // Handle what to do when the title the user is trying to 
            // create is already in their task list.
            if ($new_title_already_in_tasks){
                
                // Clear any variables the user may have submitted.
                unset($_POST, $_GET);
                
                // Send the user back to the home page, with error code: DuplicateTaskTitle.
                exit(header('Location: home.php?error=DuplicateTaskTitle'));
                
            }//End $new_title_already_in_tasks === true
            
        }//End file open
        
    // This is for when the new title is the same as the original title,
    // meaning that the user is just updating some other aspect of the task.
    }
    
    // Open tasks file
    if (($task_file_handle = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'r+')) !== false) {
        
        // Open temp file
        if (($temp_file_handle = fopen('storage/'.$_SESSION['Username'].'/taskstemp.txt', 'a+')) !== false) {
            
            // Read each line of the file as a csv. 
            // We output every other line as a csv into taskstemp.txt.
            // When we find the username and original line's title, 
            // we then output the new line to the taskstemp.txt instead of
            // the original line.
            while (($line = fgetcsv($task_file_handle)) !== false) {
                
                // first column will be a string containing the task's title
                // second column will be a string containing the task's description
                // third column will be a string containing the task's deadline in a datetime format
                // fourth column will be a string containing either checked or unchecked
                // fifth column will be a string containing the username (In our case, their email)
                if (isset($line[0]) && $line[0] == $_POST['original']){
                    
                    
                        
                    if ($line[1] == $_POST['description'] && $line[2] == $_POST['time'] && $line[3] == $_POST['status'] && $line[0] == $_POST['title']){
                        
                        // Clear any variables the user may have submitted.
                        unset($_POST, $_GET);
                        
                        // Send the user back to their home.php page with DuplicateLine error
                        exit(header('Location: home.php?error=DuplicateLine'));
                        
                    }
                    
                    // If the current task's title is the same as the original title.
                    // Then create the $replacement_line and store it in the temp file INSTEAD
                    // of the current line.
                    $replacement_line = array($_POST['title'], $_POST['description'], $_POST['time'], $_POST['status']);
                    
                    // Attempt to append the data to the task_temp.txt file.
                    // This will test for a failure. This is the opposite of most other checks.
                    $result = fputcsv($temp_file_handle, $replacement_line);
                    if ($result === false){
                        
                        // Could not append the data to the task temp file.
                        // Close both files. We're going to stop processing the files.
                        fclose($temp_file_handle);
                        fclose($task_file_handle);
                        
                        // Clear the contents of the taskstemp.txt file
                        file_put_contents('storage/'.$_SESSION['Username'].'/taskstemp.txt', "");
                        
                        // Clear any variables the user may have submitted
                        unset($_POST, $_GET);
                        
                        // Send the user back to their home.php page.
                        exit(header('Location: home.php?error=CouldNotWriteTaskFile'));
                        
                    }//End (fputcsv($temp_file_handle, $replacement_line)) === false
                    
                // The current line does not match, so just copy this line over to the task_temp.txt
                } else {
                    
                    // This will only catch if the script fails to append the line to the temp file.
                    $result = fputcsv($temp_file_handle, $line);
                    if($result === false) {
                        
                        // Could not append the data to the task temp file.
                        // Close both files. We're going to stop processing the files.
                        fclose($temp_file_handle);
                        fclose($task_file_handle);
                        
                        // Clear the contents of the taskstemp.txt file
                        file_put_contents('storage/'.$_SESSION['Username'].'/taskstemp.txt', "");
                        
                        // Clear any variables the user may have submitted
                        unset($_POST, $_GET);
                        
                        // Send the user back to their home.php page.
                        exit(header('Location: home.php?error=CouldNotWriteTaskFile'));
                        
                    }//End (fputcsv($temp_file_handle, $line)) === false
                    
                }//End where either the username or the title does not match the current task
                
            }//End While Loop
            
            // We have copied all lines over to the taskstemp.txt file.
            // Now let's replace the content in the tasks.txt file.
            // Close the files, it will be quicker to just use file_put_contents()
            fclose($temp_file_handle);
            fclose($task_file_handle);
            
            // Replace the existing data.
            copy('storage/'.$_SESSION['Username'].'/taskstemp.txt', 'storage/'.$_SESSION['Username'].'/tasks.txt');
            
            // Erase temp file.
            file_put_contents('storage/'.$_SESSION['Username'].'/taskstemp.txt', "");
            
            // Clear any variables the user may have submitted.
            unset($_POST, $_GET);
            
            // Send the user back to their home.php page.
            exit(header('Location: home.php?success=EdittedALine'));
            
        }//No Temp File Handle in Edit Section.
        
    }//TaskFileHandle did not open in Edit Section.
    
}
?>