<?php

    // Checking if we are into the OpenShift App
    if(isset($_ENV['OPENSHIFT_APP_NAME'])){
        $user     = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
        $host     = $_ENV['OPENSHIFT_MYSQL_DB_HOST'];
        $password = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
        $database = "informaticstore";
    }else{
        $host     = "localhost";
        $user     = "informatic";
        $password = "store";
        $database = "informaticstore";
    }

    $connection = new mysqli($host, $user, $password, $database);
    $connection->set_charset("utf8");

    if ($connection->connect_errno) {
        printf("Connection failed: %s\n", $connection->connect_error);
        exit();
    }

?>