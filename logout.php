<?php if(session_status()!=2){ session_start(); }

unset($_SESSION, $_POST, $_GET);

exit(header('Location: index.php'));

?>