<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/menu.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
	<script>
		document.addEventListener("load", function(){
            // Open shopping cart screen
            document.getElementById("openModalWindow1").onclick = function() {
                loadModalWindow('modalWindowCart', 'closeModalCart');
            };
            document.getElementById("openModalWindow2").onclick = function() {
                loadModalWindow('modalWindowCart', 'closeModalCart');
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
			showCart();
			refreshCart($connection);
		}
		if(isset($_POST["clear"])){
			clearCart($connection);
			showCart();
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
		toggleDesignCart();
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
			<div id="title"><h1>Product Info</h1></div>
			<div id="cart">
				<label><?php echo $cartProductsNumber; ?></label><img id="openModalWindow1" src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		            <?php
		            	if(!isset($_GET['categ']))
		        			echo "<label>Your computer store and online technology.</label>";
		            	else{
		            		$categ = $_GET['categ'];
			        		echo "<a href='menu.php'>Home</a> > ";
			        		echo "<a href='menu.php?categ=$categ'>".$categ."</a>";
		            	}
		            ?>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a id="openModalWindow2">Shopping Cart</a>
			        	<a href="order.php">Orders</a>
						<form method="post"><input type="submit" name="unlogin" value="Logout"></form>
			        </div>
			    </div>

	        </div>
        </div>
        <!-- Level 3 -->
        <br>
		<div id="content">
			<?php
				for($i=0;$i<count($productName);$i++){
	    			echo "<a href='product_info.php?id=".$productID[$i]."'>";
	    			echo "<div class='product' data-id='$productID[$i]'>";
	    			echo "<img src='".$productImage[$i]."'>";
	    			echo "<h3>".$productName[$i]."</h3>";
	    			echo "<p>".$productPrice[$i]."€</p>";
	    			echo "</div>";
	    			echo "</a>";
				}
			?>
		</div>

		<!-- Modal Window -->
		<div id="modalWindowCart" class="modal">
			<div class="modal-content">
				<p id="cartEmpty">The cart is empty</p>
				<div id="inner">
				    <span id="closeModalCart" class="close">&times;</span>
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
				</div>
		  	</div>
		</div>

	</div>
</body>
</html>

