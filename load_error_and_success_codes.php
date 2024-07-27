<?php if(session_status()!=2){ session_start(); } 

if (isset($_GET['error'])){
    
    $error = $_GET['error'];
    
    $error_message = "<h3 id='error_message'>";
    
    // Occurs when the process_task.php cannot write to the tasks.txt file.
    if ($error == "CouldNotWriteTaskFile"){ 
        
        $error_message .= "Error! Could Not Append New Task To Task File.</h3>";
    
    // Occurs when the process_task.php finds a duplicate task when editting or creating tasks.
    } elseif ($error == "DuplicateTaskTitle"){ 
        
        $error_message .= "Error! Task Title Already Exists, Try A Different Title!</h3>";
        
    // Occurs when the process_task.php cannot open the tasks.txt file.
    } elseif ($error == "CouldNotOpenTaskFile"){ 
        
        $error_message .= "Error! Could Not Open Task File.</h3>";
    
    // Occurs when user is not logged in, or their $_SESSION is not properly set.
    } elseif ($error == "Username"){ 
        
        $error_message .= "Error! Please Login.</h3>";
    
    // Occurs when email / password combination is not found in text file.
    } elseif ($error == "Password"){
        
        $error_message .= "Error! Incorrect Username / Password.</h3>";
    
    // Occurs when a user submits the login form, but does not fill in a field.
    } elseif ($error == "MissingUsernameOrPassword"){
        
        $error_message .= "Error! Please Fill In All Required Fields.</h3>";
    
    // Occurs when the login script cannot find the users.txt file.
    } elseif ($error == "NoUserFile"){
        
        $error_message .= "Error! Could Not Find Users.txt File.</h3>";
        
    // Occurs when the registration script cannot append the data to the users.txt file.
    } elseif ($error == "CouldNotWriteToUserFile"){
        
        $error_message .= "Error! Could Not Append Data To Users.txt File.</h3>";
    
    // Occurs when task_process.php receives an empty $_POST['title'].
    } elseif ($error == "NoTitleSet"){
        
        $error_message .= "Error! The Task Did Not Have A Title Set, So No Action Was Taken.</h3>";
    
    // Occurs when scripts cannot open the tasks.txt file.
    } elseif ($error == "CouldNotLoadTasks"){
        
        $error_message .= "Error! Could Not Load Tasks.txt File.</h3>";
    
    // Occurs when scripts cannot open the temp.txt file.
    } elseif ($error == "CouldNotLoadTasksTemp"){
        
        $error_message .= "Error! Could Not Load temp_tasks.txt File.</h3>";
    
    // Occurs when the user submitted .
    } elseif ($error == "DuplicateLine"){
        
        $error_message .= "Error! No Changes Detected. Did Not Process Edit.</h3>";
    
    // Occurs when task_process.php cannot find the task the user is trying to delete.
    } elseif ($error == "CouldNotDeleteNoTitleFound"){
        
        $error_message .= "Error! Could Not Delete Task From Tasks.txt File.</h3>";
        
    // Catches unknowns.
    } else {
        
        $error_message .= "Error! Unknown Error.</h3>";
        
    }
    
}

// If a task completes successfully, the user will arrive at home.php with a success
// code passed into the URL with GET.
// The success code could be a range of options, but they will indicate that
// the user's intended operation was successful.
if (isset($_GET['success'])){
    
    $success = $_GET['success'];
    
    $success_message = "<h3 id='success_message'>";
    
    // Occurs when process_task.php creates and appends a new task.
    if ($success == "CreatedNewTask"){
        
        $success_message .= "Success! Created And Appended New Task!</h3>";
    
    // Occurs when process_task.php edits a task.
    } elseif ($success == "EdittedALine"){
        
        $success_message .= "Success! Updated A Task!</h3>";
        
    // Occurs when process_task.php deletes a task.
    } elseif ($success == "DeletedALine"){
        
        $success_message .= "Success! Deleted A Task!</h3>";
        
    }
}
?>