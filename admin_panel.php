<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/admin_panel.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
   	<script type="text/javascript" src="js/chart_google.js"></script>
   	<script src="js/jspdf.js"></script>
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
			header('Location: index.php');
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['userID']);
		$username = $userData['username'];

		// Product data from all product
		$productsData = [[]];

        if(isset($_GET['categ']))
            $productsData = getProductCategory($connection, $_GET['categ']);
        else
            $productsData = getAllProduct($connection);

		$productID    = $productsData['id'];
		$productName  = $productsData['name'];
		$productPrice = $productsData['price'];
		$productImage = $productsData['urlImage'];

		// Actions of shopping cart
		refreshCart($connection);

		if(isset($_POST["delete"])){
			deleteProductFromCart($connection, $_POST["cartProductID"]);
			refreshCart($connection);
		}
		if(isset($_POST["clear"])){
			clearCart($connection);
			refreshCart($connection);
		}
		if(isset($_POST["buy"]) || isset($_POST["buyDirectly"])){
			if(isset($_POST["buy"])){
				$orderData = [];
				$orderData['orderID']        = "NULL";
				$orderData['customerID']     = $_SESSION['userID'];
				$orderData['date']           = "";						// Within the function, the current date is saved
				$orderData['amountProducts'] = $cartProductsNumber;
				$orderData['price']          = $cartTotalPrice;
				$orderID_lastInserted = insertOrder($connection, $orderData);

				// Get relationship of product/amount from cart
				$cartProductIDAndAmount = getCartProductAndAmount($connection, $_SESSION['userID']);
				$cartProductID     = $cartProductIDAndAmount['idProduct'];
				$cartProductAmount = $cartProductIDAndAmount['amount'];

				insertContain($connection, $orderID_lastInserted, $cartProductID, $cartProductAmount);
			}
			// else if(isset($_POST["buyDirectly"])){	// Products within cart + current product
			// 	$money = $cartTotalPrice + ($productPrice * $_POST["amountToAdd"]);
			// 	makePurchase($connection, $_SESSION['userID'], $cartProductsNumber + 1, $money);
			// }
			clearCart($connection);
			refreshCart($connection);
            showToast("Purchase made with success");
		}
		if(isset($_POST["theme"])){
			if($_POST['changeTheme'] == "Fresh")			$_SESSION["userTheme"] = "Fresh";
			else if($_POST['changeTheme'] == "Dark")		$_SESSION["userTheme"] = "Dark";
			else if($_POST['changeTheme'] == "Colorful")	$_SESSION["userTheme"] = "Colorful";
		}
		changeTheme();
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
	        	<h1>Control Panel</h1>
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
		        	<a href="admin.php">Home</a> >
		        	<a href="admin_panel.php">Control Panel</a>
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
        <br>
        <!-- Level 3 -->
		<div id="content">
			<div id="piechart"></div>
		</div>
	</div>

	<!--
	    =============
    	Chart
    	=============
    	It's placed here for be ran the last of page. The chart needs vars that load before.
    -->
    <?php
		$usersData    = getAllUser($connection);
		$productsData = getAllProduct($connection);
		$ordersData   = getAllOrders($connection);

		$totalUsers    = count($usersData['id']);
		$totalProducts = count($productsData['id']);
		$totalOrders   = count($ordersData['orderID']);
    ?>
	<script>
		// Vars of order
		var users    = <?php echo json_encode($totalUsers);    ?>;
		var products = <?php echo json_encode($totalProducts); ?>;
		var orders   = <?php echo json_encode($totalOrders);   ?>;

		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			if(users == 0 && products == 0 && orders == 0){
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['No data', 1]
					]);
			}else{
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['Amount of users',    users],
					['Amount of products', products],
					['Amount of orders',   orders]
					]);
			}

			var options = {
				// title: 'Amounts of user'
			};

			var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			chart.draw(data, options);
		}
	</script>

</body>
</html>