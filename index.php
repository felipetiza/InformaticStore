<?php
    // This file is created when finish the installation
    if(file_exists("database.php")){

    	// Delete the installation file
    	if(file_exists("install.php"))
    		unlink("install.php");

        header('Location: login.php');
    }else
        header('Location: install.php');
?>