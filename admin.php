<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
</head>
<body>

 	<?php
        // File is created when finish the installation. Contains database connection variables
        file_exists("database.php") ? include_once "database.php" : header('Location: index.php');
        include_once "management.php";
        session_start();

        // The arguments variables are into database.php
        databaseConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		checkAccesOption("Admin");

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header("Location: index.php");
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['userID']);
		$username = $userData['username'];
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
	        	<h1>Informatic Store</h1>
	        </div>
			<div id="title"></div>
			<div id="cart">
				<label>Administration</label>
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<label>Manage your online store easily</label>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a href="admin_customer.php">Customer</a>
			        	<a href="admin_product.php">Products</a>
			        	<a href="admin_order.php">Order</a>
			        	<a href="admin_panel.php">Control Panel</a>
						<form method="post"><input type="submit" name="unlogin" value="Logout"></form>
			        </div>
			    </div>

	        </div>
        </div>
        <!-- Level 3 -->
        <br>
		<div id="content">
			<div id="img">
				<a href="admin_customer.php"><div><img src="resources/img/admin_customer.png"></div></a>
				<a href="admin_product.php"><div><img src="resources/img/admin_product.png"></div></a>
				<a href="admin_order.php"><div><img src="resources/img/admin_order.png"></div></a>
			</div>
			<div id="text">
				<div><p>Customer</p></div>
				<div><p>Product</p></div>
				<div><p>Order</p></div>
			</div>
		</div>
	</div>
</body>
</html>

