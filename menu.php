<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<link rel="stylesheet" href="css/menu.css">
</head>
<body>

 	<?php
		include_once "db_connection.php";

	    session_start();
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		if(isset($_SESSION["iduser"])){
			$username = "";

			// Get username from database
            $getusername = "SELECT name
                         FROM customer
                         WHERE idcustomer = {$_SESSION['iduser']}
                        ";

            if ($result = $connection->query($getusername)) {
                if ($result->num_rows > 0)
                    $username = $result->fetch_object()->name;
                else
                    echo "Impossible to get the username";
            }else
                echo "Wrong Query";
		}else
			header('Location: login.php');
	?>

	<div id="wrapper">
		<h1>Menu</h1>
        <hr>
        <form method="post" id="unlogin">
			<input type="submit" name="unlogin" value="Logout">
        </form>
        <?php echo "<p>Welcome, $username</p>" ?>
        <br>
		<div id="content">

		</div>
	</div>







</body>
</html>