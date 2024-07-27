<?php
if(session_status()!=2){ session_start(); }

require 'validator.php';

if (isset($_POST['title'])) {
    
    $search_term = $_POST['title'];
    $sort_status = false;
    $sort_time = false;
    
} elseif (isset($_POST['status'])){
    
    $status = $_POST['status'];
    $sort_status = false;
    $sort_time = false;
    
}

if (isset($_GET['controls'])){
    
    if ($_GET['controls'] == "None"){
        
        $sort_status = false;
        $sort_time = false;
    
    } elseif ($_GET['controls'] == "Status"){
        
        $sort_status = true;
        $sort_time = false;
    
    } elseif ($_GET['controls'] == "Time"){
        
        $sort_status = false;
        $sort_time = true;
        
    } elseif ($_GET['controls'] == "Both"){
        
        $sort_status = true;
        $sort_time = true;
        
    } else {
        
        $sort_status = false;
        $sort_time = false;
        
    }
    
} else {
    
    $sort_status = false;
    $sort_time = false;
    
}

// Determine if the user set any sorting preferences.
// Status will sort the rows by status, so there's a string for each status option.
// Each string will contain that status' rows, so that at the end, 
// all the strings can be combined in the correct order.
if ($sort_status){
    
    if ($sort_time){
        
        /* 
        If time and status are set, rows get added to their own array by status.
        Google showed forums where people imported pandas
        That was really complex. Instead of doing all that, forums said to store the row as the value,
        and store the date as the index. So it would be something like:
        $array_data['04/08/2024T 00:00'] = rowdata.
        Then sort the array by the keys.
        But PHP kept breaking it, so I had to convert the dates into numbers 
        so PHP would know how to compare them
        https://stackoverflow.com/questions/21796958/how-to-convert-date-into-integer-number-in-php
        Now it worked.
        */
        $completed_tasks_array = [];
        $incompleted_tasks_array = [];
        $in_progress_tasks_array = [];
        
    } else {
        
        $completed_tasks = "";
        $incompleted_tasks = "";
        $in_progress_tasks = "";
        
    }
    
} else {
    
    if ($sort_time){
        
        $user_tasks_array = [];
        
    } else {
        
        $user_tasks = "";
        
    }
    
}

// Open the tasks file.
if (($file_handle = fopen('storage/'.$_SESSION['Username'].'/tasks.txt', 'r')) !== false) {
    
    // Read each line of the file as a csv
    // When it reads an empty line, it sets $line to false, and the while 
    // loop ends.
    while (($line = fgetcsv($file_handle, 1000, ",")) !== false) {
        
        // first column will be a text input containing the task's title
        // second column will be a text input containing the task's description
        // third column will be a date-time containing the task's deadline in a datetime format
        // fourth column will be a select with options for incomplete, in progress, and complete
        // fifth column will offer to update the task
        // sixth column will offer to delete the task
            
        // If the user is searching for a specific title and if the current title contains their search term.
        // Or if there is no search term, continue.
        if (
            (!empty($search_term) && strpos($line[0], $search_term) !== false) || 
            (empty($search_term) && empty($status)) || 
            ((!empty($status) && $line[3] == $status))
        ){
            
            // Setup Column One
            $column_one = "<td><input type='hidden' name='original' value='".$line[0]."' form='".$line[0]."'><input name='title' type='text' value='".$line[0]."' form='".$line[0]."' required></td>";
            
            // Setup Column Two
            $column_two = "<td><input name='description' type='text' value='".$line[1]."' form='".$line[0]."'></td>";
            
            // Setup Column Three
            $column_three = "<td><input name='time' type='datetime-local' value='".$line[2]."' form='".$line[0]."' required></td>";
            
            // Setup Column Four
            $column_four = "<td><select name='status' id='status' form='".$line[0]."' required'>";
            
            if ($line[3] == "Complete"){
                
                $column_four .= "<option value='Complete' selected>Complete</option><option value='Incomplete'>Incomplete</option><option value='In Progress'>In Progress</option>";
                
            } elseif ($line[3] == "Incomplete") {
                
                $column_four .= "<option value='Complete'>Complete</option><option value='Incomplete' selected>Incomplete</option><option value='In Progress'>In Progress</option></select></td>";
                
            } else {
                
                $column_four .= "<option value='Complete'>Complete</option><option value='Incomplete'>Incomplete</option><option value='In Progress' selected>In Progress</option></select></td>";
                
            }
            
            $column_four .= "</select></td>";
            
            // Setup Column Five
            $column_five = "<td><form id='".$line[0]."' action='task_edit.php' method='post'><input type='submit'  style='background-color: transparent; border: none;' value='Submit Changes' form='".$line[0]."' ></form></td>";
            
            // Setup Column Six
            $column_six = "<td><form action='task_deletion.php' method='post'><input type='hidden' name='title' value='".$line[0]."'><input type='submit' style='background-color: transparent; border: none;' value='Delete This Task'></form></td>";
            
            // This is the string that will be appended to the output
            // Customizing the background-color based on the current status
            if ($line[3] == "Complete"){
                
                $table_row = "<tr class='complete_row'>".$column_one.$column_two.$column_three.$column_four.$column_five.$column_six."</tr>";
            
                
            } elseif ($line[3] == "Incomplete") {
                
                $table_row = "<tr class='incomplete_row'>".$column_one.$column_two.$column_three.$column_four.$column_five.$column_six."</tr>";
                
            } else {
                
                $table_row = "<tr class='in_progress_row'>".$column_one.$column_two.$column_three.$column_four.$column_five.$column_six."</tr>";
                
            }
            
            // The variable that will be output is $user_tasks, so it is really important
            // that the rows are appended to that variable.
            // Otherwise there'd be a lot of PHP code in the middle of the html.
            // And that is very confusing.
            if ($sort_status){
                
                // Compare $line[3] to the possible status options and add the 
                // row string to the appropriate status.
                if ($line[3] == "Complete"){
                    
                    if ($sort_time){
                        
                        $time = strtotime($line[2]);
                        
                        while (true){
                            
                            // If that key does not already exist, store the time in the array with that key.
                            if (empty($completed_tasks_array[$time])){
                                
                                $completed_tasks_array[$time] = $table_row;
                                break;
                            
                            // If that key already exists, increase time by one second and try again.
                            } else {
                                
                                $time++;
                                
                            }
                            
                        }
                        
                    } else {
                        
                        $completed_tasks .= $table_row;
                        
                    }
                    
                } elseif ($line[3] == "Incomplete") {
                    
                    if ($sort_time){
                        
                        $time = strtotime($line[2]);
                        
                        while (true){
                            
                            // If that key does not already exist, store the time in the array with that key.
                            if (empty($incompleted_tasks_array[$time])){
                                
                                $incompleted_tasks_array[$time] = $table_row;
                                break;
                            
                            // If that key already exists, increase time by one second and try again.
                            } else {
                                
                                $time++;
                                
                            }
                            
                        }
                        
                    } else {
                    
                        $incompleted_tasks .= $table_row;
                        
                    }
                    
                } else {
                    
                    if ($sort_time){
                        
                        $time = strtotime($line[2]);
                        
                        while (true){
                            
                            // If that key does not already exist, store the time in the array with that key.
                            if (empty($in_progress_tasks_array[$time])){
                                
                                $in_progress_tasks_array[$time] = $table_row;
                                break;
                                
                            
                            // If that key already exists, increase time by one second and try again.
                            } else {
                                
                                $time++;
                                
                            }
                            
                        }
                    
                    } else {
                    
                        $in_progress_tasks .= $table_row;
                        
                    }
                    
                }

            // If the user did not select a sorting method, just append the current
            // string to the output directly.
            } else {
                
                if ($sort_time){
                    
                    $time = strtotime($line[2]);
                    
                    while (true){
                            
                        // If that key already exists, increase time by one second and try again.
                        if (empty($user_tasks_array[$time])){
                            
                            $user_tasks_array[$time] = $table_row;
                            break;
                            
                        
                        // If that key does not already exist, store the time in the array with that key.
                        } else {
                            
                            $time++;
                            
                        }
                        
                    }
                    
                } else {
                
                    $user_tasks .= $table_row;
                    
                }
            }
        }
    }
    // Close the file handle
    fclose($file_handle);
    
    // All of the user's tasks have been processed into rows, and those rows are stored
    // in their corresponding status strings.
    if ($sort_status){
        
        if ($sort_time){
            // Sort each array by the keys (The timestamp of the deadline)
            ksort($incompleted_tasks_array);
            ksort($in_progress_tasks_array);
            ksort($completed_tasks_array);
            $user_tasks = implode($incompleted_tasks_array).implode($in_progress_tasks_array).implode($completed_tasks_array);
            
        } else {
            
            // The order of tasks will be:
            // Incomplete
            // In Progress
            // Complete
            $user_tasks = $incompleted_tasks.$in_progress_tasks.$completed_tasks;
            
        }
        
    } else {
    
        if ($sort_time){
            // Sort the array by the keys (The timestamp of the deadline)
            ksort($user_tasks_array);
            $user_tasks = implode($user_tasks_array);
            
        }
    }
}// Done with the tasks.txt file.

// Now finish creating the table output
$user_tasks = "<table id='table_contents'><tr><th>Title</th><th>Description</th><th>Deadline</th><th>Status</th><th>Update Task</th><th>Delete Task</th></tr>".$user_tasks."</table>"
?>
