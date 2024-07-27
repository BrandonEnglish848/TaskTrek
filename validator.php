<?php if(session_status()!=2){ session_start(); }

if (!isset($_SESSION['UserValidated'])){
    
    exit(header('Location: index.php?error=Username'));
    
}

if (!isset($_SESSION['Username'])){
    
    exit(header('Location: index.php?error=Username'));
    
}

if (!isset($_SESSION['Nom']) && !isset($_SESSION['Prenom'])){
    
    exit(header('Location: index.php?error=Username'));
    
}

?>