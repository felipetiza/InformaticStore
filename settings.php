<!DOCTYPE html>
<html>
<head>
	<title>Settings</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/settings.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
   	<script type="text/javascript" src="js/chart_google.js"></script>
   	<script src="js/jspdf.js"></script>
	<script>
		document.addEventListener("load", function(){
            // Open modal windows
            document.getElementById("openModalWindow1").onclick = function() {
                loadModalWindow('modalWindowCart', 'closeModalCart');
            };
            document.getElementById("openModalWindow2").onclick = function() {
                loadModalWindow('modalWindowCart', 'closeModalCart');
            };
            // Change Theme - Automatically
            // No press button, user clic on input radio and a hidden button
            // is automatically clicked to provoke a POST
            document.getElementById("theme1").onclick = function() {
                document.getElementById("buttonTheme").click();
            };
            document.getElementById("theme2").onclick = function() {
                document.getElementById("buttonTheme").click();
            };
            document.getElementById("theme3").onclick = function() {
                document.getElementById("buttonTheme").click();
            };
	    }, true);
	</script>
</head>
<body>

 	<?php
	    // File is created when finish the installation. Contains database connection variables
	    file_exists("database.php") ? include_once "database.php" : header('Location: index.php');
	    include_once "management.php";
	    session_start();

	    // The arguments variables are into database.php
	    databaseConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		checkAccesOption("User");

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
		if(isset($_POST["pdf"])){
			// Get username
			$userData = getUserData($connection, $_SESSION['userID']);
			$name     = $userData['name'];
			$username = $userData['username'];

			// Get total values of user
			refreshOrdersOfAClient($connection, $_SESSION["userID"]);
			$totalMoney          = 0;
			$totalNumberProducts = 0;
			$totalNumberOrders   = count($orderOrderID);

	    	for($i=0;$i<count($orderDate);$i++){
				$totalMoney          += $orderPrice[$i];
				$totalNumberProducts +=	$orderAmountProd[$i];
	    	}

			// Get other values
			$currentDate = date('Y-m-d');

			echo "<script>
					var doc = new jsPDF();
					doc.setFontSize(32);
					doc.setFontStyle('bold');
					doc.setTextColor(12, 42, 72);
					doc.text(20, 20, 'InformaticStore');

					doc.setFontSize(16);
					doc.text(20, 40, 'Activity report');
					doc.setFontSize(14);
					doc.text(80, 40, '$currentDate');

					doc.setTextColor(0, 0, 0);
					doc.setFontSize(12);
					doc.text(20, 50, 'User:');
					doc.text(20, 55, 'Orders:');
					doc.text(20, 60, 'Products purchased:');
					doc.text(20, 65, 'Spent money:');

					doc.setFontStyle('normal');
					doc.text(80, 50, '$name ($username)');
					doc.text(80, 55, '$totalNumberOrders');
					doc.text(80, 60, '$totalNumberProducts');
					doc.text(80, 65, '$totalMoney €');

					doc.save('InformaticStore-report_user.pdf');
				  </script>";
		}
		changeTheme();
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
			    <div class="dropdown">
			        <button class="flatButton dropbtnCategory">Category</button>
			        <div class="dropdown-content">
 						<?php
							for($i=0;$i<count($listProductCategory);$i++){
								echo "<a href='menu.php?categ=".strtolower($listProductCategory[$i])."' ?>";
								echo $listProductCategory[$i]."</a>";
							}
						?>
			        </div>
			    </div>
	        </div>
			<div id="title"><h1>Settings</h1></div>
			<div id="cart">
				<label><?php echo $cartProductsNumber; ?></label><img id="openModalWindow1" src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<a href="menu.php">Home</a> >
		        	<a href="settings.php">Settings</a>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a id="openModalWindow2">Shopping Cart</a>
			        	<a href="order.php">Orders</a>
			        	<a href="settings.php">Settings</a>
						<form method="post"><input type="submit" name="unlogin" value="Logout"></form>
			        </div>
			    </div>

	        </div>
        </div>
        <!-- Level 3 -->
        <br>
		<div id="content">
	        <div id="theme-box">
		        <h2>Theme</h2>
		        <hr>
		        <form method='post'>
	            	<label><input id="theme1" type="radio" name="changeTheme" value="Fresh">Fresh</label>
	            	<br/>
	            	<label><input id="theme2" type="radio" name="changeTheme" value="Dark">Dark</label>
	            	<br/>
	            	<label><input id="theme3" type="radio" name="changeTheme" value="Colorful">Colorful</label>
	            	<input id="buttonTheme" type='submit' name="theme" style="display:none;">
	            </form>
        	</div>
        	<br/>
        	<br/>
		    <div id="chart-box">
		        <h2>Control Panel</h2>
		        <hr>
	        	<form method='post'>
	        		<input id="buttonPDF" type="submit" class="flatButton" name="pdf" value="Generate report in pdf">
	        	</form>
				<div id="piechart"></div>
		    </div>
        	<br/>
		</div>

		<!-- Modal Window -->
		<div id="modalWindowCart" class="modal">
			<div class="modal-content">
				<?php if($cartProductsNumber == 0){ ?>
			    	<label id="closeModalCart" class="close">&times;</label>
					<p id="cartEmpty">The cart is empty</p>
				<?php }else{ ?>
				    <label id="closeModalCart" class="close">&times;</label>
				    <p>(<?php echo $cartProductsNumber; ?>) Products in your shopping cart</p>
						<table>
						<tr>
					  		<th>Product</th>
					  		<th>Price</th>
					  		<th>Units</th>
					  		<th>Total</th>
					  		<th></th>
						</tr>
	 				    <?php
							for($i=0;$i<$cartProductsNumber;$i++){
				    			echo "<tr>";
				    			echo "<td><a href='product_info.php?id=$cartProductID[$i]'>$cartProductName[$i]</a></td>";
				    			echo "<td>".$cartProductPrice[$i]."€</td>";
				    			echo "<td>".$cartProductAmount[$i]."</td>";
				    			echo "<td>".$cartProductPrice[$i] * $cartProductAmount[$i]."€</td>";
				    			echo "<td>
					    				  <form method='post'>
					    					  <input class='delete' type='submit' name='delete' value='&times;'>
					    					  <input type='text' name='cartProductID' value='$cartProductID[$i]'>
					    				  </form>
				    				  </td>";
				    			echo "</tr>";
				    		}
					    ?>
					</table>
					<br/>
					<br/>
					<p><?php echo "Total: ".$cartTotalPrice."€"; ?></p>
					<br/>
				    <form method='post'>
						<input class="standardButton" id="btnClear" type='submit' name='clear' value='Clear'>
						<input class="standardButton" id="btnBuy" type='submit' name='buy' value='Buy'>
					</form>
				<?php } ?>
		  	</div>
		</div>

	</div>

	<!--
	    =============
    	Chart
    	=============
    	It's placed here for be ran the last of page. The chart needs vars that load before.
    -->
    <?php
		$ordersData = getOrderDataOfAClient($connection, $_SESSION["userID"]);
		$orderPrice      = $ordersData['price'];
		$orderAmountProd = $ordersData['amount'];
		$orderOrderID    = $ordersData['orderID'];

		$totalMoney          = 0;
		$totalNumberProducts = 0;
		$totalNumberOrders   = count($orderOrderID);

    	for($i=0;$i<count($orderOrderID);$i++){
			$totalMoney          += $orderPrice[$i];
			$totalNumberProducts +=	$orderAmountProd[$i];
    	}
    ?>
	<script>
		// Vars of order
		var money    = <?php echo json_encode($totalMoney); ?>;
		var products = <?php echo json_encode($totalNumberProducts); ?>;
		var orders   = <?php echo json_encode($totalNumberOrders); ?>;

		google.charts.load('current', {'packages':['corechart']});
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {

			if(products == 0 && orders == 0){
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
					['No data', 1]
					]);
			}else{
				var data = google.visualization.arrayToDataTable([
					['Task', 'Hours per Day'],
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