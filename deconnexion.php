<?php
session_start();
// On détruit toutes les variables de session
session_unset();
// On détruit la session elle-même
session_destroy();

// Redirection instantanée vers la page d'accueil
header("Location: accueil.php");
exit();
?>