<!DOCTYPE html>
<html>
<head>
    <title>Install</title>
    <meta charset="utf-8">
    <link rel="icon" href="resources/img/favicon.ico">
    <link rel="stylesheet" href="css/install.css">
    <link rel="stylesheet" href="css/resources.css">
    <script src="js/management.js"></script>
    <script src="js/author.js"></script>
</head>
<body>

    <?php
        include_once "management.php";

        session_start();

        if(file_exists("database.php"))
        	header('Location: index.php');

        if (isset($_POST["buttonInstall"])) {
        	/*
	        	The user must create manually the database and the user + password

                - In localhost you can create everything programmatically
			    - In the hostings not because they already give you the database and the user created
        	*/
			$dbName     = trim($_POST['databaseName']);
			$dbUser     = trim($_POST['databaseUser']);
			$dbPass     = trim($_POST['databasePassword']);
			$dbHost     = trim($_POST['databaseHost']);
			$dbLoadData = (isset($_POST['databaseInitialData'])) ? "true" : "false";

            $connect = databaseConnection($dbHost, $dbUser, $dbPass, $dbName);
            if($connect == "true"){
				insertCustomerTable($connection);
				insertProductTable($connection);
				insertOrderTable($connection);
				insertCartTable($connection);
				insertContainTable($connection);

            	if($dbLoadData == "true"){
					insertValuesCustomer($connection);
					insertValuesProduct($connection);
            	}

            	// Create a file with the connection variables of the database
            	$dataFile = "<?php\n\tconst DB_NAME = '$dbName';\n\tconst DB_USER = '$dbUser';\n\tconst DB_PASS = '$dbPass';\n\tconst DB_HOST = '$dbHost';\n?>";

            	$file = fopen("database.php", "w");
        		fwrite($file, $dataFile);
        		Fclose($file);

		    	showToast("Installation completed correctly");
            }
        }
        if (isset($_POST["buttonContinue"])) {
       	    if(file_exists("database.php"))
				header('Location: index.php');
        	else
		    	showToast("You need to complete the installation");
        }
    ?>

    <div id="wrapper">
    	<div id="box-logo"><img id="logo" src="resources/img/logo.png"></div>
    	<hr>
		<p>
			Welcome to InformaticStore. You are about to create your own online store that you can easily manage every day. The installation is quick and easy.
		</p>
		<p>You only must enter the connection details to your database.</p>
        <div id="down">
	        <form method="post">
	            <div>
	                <span>Database name</span>
	                <input name="databaseName" type="text" maxlength="25" value="informaticstore" required>
	            </div>
	            <div>
	                <span>User</span>
	                <input name="databaseUser" type="text" maxlength="50" value="informatic" required>
	            </div>
	            <div>
	                <span>Password</span>
	                <input name="databasePassword" type="text" maxlength="45" value="store" required>
	            </div>
	            <div>
	                <span>Database host</span>
	                <input name="databaseHost" type="text" maxlength="45" value="localhost" required>
	            </div>
	            <br/>

	            <label><input name="databaseInitialData" type="checkbox" checked>Load initial data</label>
	            <br/>
	            <br/>
	            <br/>
	            <div id="buttons">
	                <input id="btnInstall" type="submit" name="buttonInstall" class="standardButton" value="Start installation">
	                <input id="btnContinue" type="submit" name="buttonContinue" class="standardButton" value="Continue">
	            </div>
	        </form>
	    </div>
    </div>
</body>
</html>