<?php

    // Checking if we are into the OpenShift App
    if(isset($_ENV['OPENSHIFT_APP_NAME'])){
        $user     = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];    // Openshift db name OPENSHIFT_MYSQL_DB_USERNAME
        $host     = $_ENV['OPENSHIFT_MYSQL_DB_HOST'];        // Openshift db host OPENSHIFT_MYSQL_DB_HOST
        $password = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];    // Openshift db password OPENSHIFT_MYSQL_DB_PASSWORD
        $database = "informaticstore";                       // Openshift db name
    }else{
        $host     = "localhost";
        $user     = "informatic";
        $password = "store";
        $database = "informaticstore";
    }

	// $host     = 'localhost';
	// $database = 'informaticstore';
	// $user     = 'informatic';
	// $pass     = 'store';

    $connection = new mysqli($host, $user, $password, $database);
    $connection->set_charset("utf8");

    if ($connection->connect_errno) {
        printf("Connection failed: %s\n", $connection->connect_error);
        exit();
    }

?>