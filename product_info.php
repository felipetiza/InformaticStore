<!DOCTYPE html>
<html>
<head>
	<title>Product Info</title>
	<link rel="stylesheet" href="css/product_info.css">
</head>
<body>

 	<?php
		include_once "db_connection.php";

	    session_start();

		if(isset($_POST["felipe"])){
			echo $_POST["felipe"];
		}else
			echo "Puta Mierda";


	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		// If user is logged
		if(isset($_SESSION["iduser"])){
			// Get username from database
			$username = "";
            $getusername = "SELECT name
                            FROM customer
                            WHERE idcustomer = {$_SESSION['iduser']};
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
		<h1>Product Info</h1>
        <hr>
        <form method="post" id="unlogin">
			<input type="submit" name="unlogin" value="Logout">
        </form>
        <?php echo "<p>Welcome, $username</p>" ?>
        <br>
		<div id="content">
			<div id="row1">
				<div id="image">
					<img src="resources/img/product/mouse.jpg">
				</div>
				<div id="info">
					<h1>Mouse</h1>
					<h2>12.50€</h2>
					<h3>14 units</h3>
				</div>
			</div>
			<div id="row2">
				<p>
				Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
				</p>
			</div>
		</div>
	</div>
</body>
</html>