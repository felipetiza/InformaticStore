<?php

	$host     = 'localhost';
	$database = 'informaticstore';
	$user     = 'informatic';
	$pass     = 'store';

    $connection = new mysqli($host, $user, $pass, $database);
    $connection->set_charset("utf8");

    if ($connection->connect_errno) {
        printf("Connection failed: %s\n", $connection->connect_error);
        exit();
    }

?>