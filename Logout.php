<?php
session_start();    
session_unset();    
session_destroy(); 
header("Location: index.html"); // Redirect to the login page or home page
exit();
?>
