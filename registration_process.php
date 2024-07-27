<?php 
// Vérifie si la session n'est pas déjà démarrée
if(session_status()!=2){ 
    session_start(); 
}

// Vérifie si les champs 'nom', 'prenom', 'email' et 'password' sont définis dans $_POST
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['password'])){
    
    // Vérifie si les champs 'nom', 'prenom', 'email' et 'password' ne sont pas vides
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['password'])){
        
        // Ouvre le fichier 'users.txt' pour vérifier si l'e-mail existe déjà
        if (($file_handle = fopen('storage/users.txt', 'r')) !== false) {
            
            // Variable pour indiquer si l'utilisateur existe déjà
            $user_exists = false;
            
            // Parcourt chaque ligne du fichier 'users.txt' pour vérifier si l'e-mail existe déjà
            while (($line = fgetcsv($file_handle, 1000, ",")) !== false) {
                
                // Vérifie si la troisième colonne contient un e-mail
                if (isset($line[2])){
                    
                    // Vérifie si l'e-mail dans 'users.txt' correspond à celui soumis dans le formulaire
                    if($line[2] == $_POST['email']){
                        
                        // L'utilisateur existe déjà, donc on met la variable à true et on sort de la boucle
                        $user_exists = true;
                        break;
                        
                    }
                    
                }
                
            }
            
            // Ferme le gestionnaire de fichier
            fclose($file_handle);
            
        } else {
            
            // Redirige vers la page d'accueil avec le code d'erreur 'NoUserFile'
            exit(header('Location: index.php?error=NoUserFile'));
        
        }
    
        // Si l'utilisateur existe déjà, redirige vers la page d'accueil avec le code d'erreur 'DuplicateEmail'
        if ($user_exists) {
            
            // Efface les variables POST, GET et SESSION
            unset($_POST, $_GET, $_SESSION);
            
            // Redirige vers la page d'accueil avec le code d'erreur 'DuplicateEmail'
            exit(header('Location: index.php?error=DuplicateEmail'));
            
        } else {
            
            // Si l'e-mail n'existe pas déjà, crée un hachage sécurisé du mot de passe
            $options = ['cost' => 10];
            $hash = password_hash($_POST['password'],PASSWORD_BCRYPT, $options);
            
            // Crée un tableau avec les données de l'utilisateur
            $user_data = array($_POST['nom'], $_POST['prenom'], $_POST['email'], $hash);
            
            // Ouvre à nouveau le fichier 'users.txt' pour ajouter les nouvelles données
            if (($file_handle = fopen('storage/users.txt', 'a+')) !== false) {
                
                // Tente d'écrire les données dans le fichier 'users.txt'
                if (fputcsv($file_handle, $user_data) !== false){
            
                    fclose($file_handle);
                    
                    // Crée un dossier pour l'utilisateur
                    mkdir('storage/'.$_POST['email'].'/');
                    
                    // Crée un fichier temporaire pour les tâches de l'utilisateur
                    $temp = fopen('storage/'.$_POST['email'].'/temptasks.txt', 'a+');
                    fclose($temp);
                    
                    // Ouvre le fichier des tâches de l'utilisateur
                    if (($tasks_file_handle = fopen('storage/'.$_POST['email'].'/tasks.txt', 'a+')) !== false) {
                        
                        // Ajoute une première tâche par défaut pour l'utilisateur
                        $first_task = array(
                            "Create Your First Task",
                            "Use the homepage controls to setup your first task. Click the button to the right to complete this task!",
                            "2030-04-24T00:00",
                            "Incomplete");
                            
                        // Tente d'écrire la première tâche dans le fichier des tâches
                        if (fputcsv($tasks_file_handle, $first_task) !== false){
                            
                            // Efface les variables POST, GET et SESSION
                            unset($_POST, $_GET, $_SESSION);
                            
                            // Redirige vers la page d'accueil avec le code de succès 'true'
                            exit(header('Location: index.php?success=true'));
                            
                        }
                        
                    } else {
                        
                        // Efface les variables POST, GET et SESSION
                        unset($_POST, $_GET, $_SESSION);
                    
                        // Redirige vers la page d'accueil avec le code d'erreur 'CouldNotLoadTasks'
                        exit(header('Location: index.php?error=CouldNotLoadTasks'));
                        
                    }
                
                } else {
                    
                    // Efface les variables POST, GET et SESSION
                    unset($_POST, $_GET, $_SESSION);
                    
                    // Redirige vers la page d'accueil avec le code d'erreur 'CouldNotWriteToUserFile'
                    exit(header('Location: index.php?error=CouldNotWriteToUserFile'));
                    
                }
                
            } else {
            
                // Efface les variables POST, GET et SESSION
                unset($_POST, $_GET, $_SESSION);
                
                // Redirige vers la page d'accueil avec le code d'erreur 'NoUserFile'
                exit(header('Location: index.php?error=NoUserFile'));
                
            }
        
        }
        
    // Si des champs sont laissés vides, redirige vers la page d'accueil avec le code d'erreur 'FieldsNotSet'
    } else {
        
        // Efface les variables POST, GET et SESSION
        unset($_POST, $_GET, $_SESSION);
        
        // Redirige vers la page d'accueil avec le code d'erreur 'FieldsNotSet'
        exit(header('Location: index.php?error=FieldsNotSet'));

    }
    
// Si tous les champs requis ne sont pas soumis via POST, redirige vers la page d'accueil avec le code d'erreur 'FieldsNotSet'
} else {
    
    // Efface les variables POST, GET et SESSION
    unset($_POST, $_GET, $_SESSION);
    
    // Redirige vers la page d'accueil avec le code d'erreur 'FieldsNotSet'
    exit(header('Location: index.php?error=FieldsNotSet'));
    
}

?>
