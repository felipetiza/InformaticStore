<!DOCTYPE html>
<html>
<head>
	<title>Orders</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/order.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
	<script>
		document.addEventListener("load", function(){
			loadAccordion();
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
		include_once "management.php";

	    session_start();
	    databaseConnection();
		checkAccesOption("User");

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: '.MAIN_PAGE);
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['userID']);
		$username = $userData['username'];

		refreshOrdersOfAClient($connection, $_SESSION["userID"]);

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
				insertOrder($connection, $orderData);

				$orderID_previousInserted = getLastAutoIncrementGenerated($connection, "order2", "informaticstore");

				// Get relationship of product/amount from cart
				$cartProductIDAndAmount = getCartProductAndAmount($connection, $_SESSION['userID']);
				$cartProductID     = $cartProductIDAndAmount['idProduct'];
				$cartProductAmount = $cartProductIDAndAmount['amount'];

				insertContain($connection, $orderID_previousInserted, $cartProductID, $cartProductAmount);
			}
			// else if(isset($_POST["buyDirectly"])){	// Products within cart + current product
			// 	$money = $cartTotalPrice + ($productPrice * $_POST["amountToAdd"]);
			// 	makePurchase($connection, $_SESSION['userID'], $cartProductsNumber + 1, $money);
			// }
			clearCart($connection);
			refreshCart($connection);
			refreshOrdersOfAClient($connection, $_SESSION["userID"]);
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
			<div id="title"><h1>Orders</h1></div>
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
		        	<a href="order.php">Order</a>
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

<!-- <button class="accordion">
	<div id="left">
		<p id="idorder">Nº: 28</p><p id="number">| 3 Product(s)</p>
	</div>
	<div id="right">
		<p id="date">Performed the: 2017-02-18</p>
	</div>
</button>
<div class="panel">
	<br/>
	<table>
		<tr>
	  		<th>Product</th>
	  		<th>Price</th>
	  		<th>Units</th>
	  		<th>Total</th>
		</tr>
	</table>
	<br/>
  	<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
</div>
		-->

			<?php
				for($i=0;$i<count($orderDate);$i++){					// Orders
					echo "<button class='accordion'>";
					echo "<div id='left'>";
					echo "<p id='idorder'>Nº: <strong>$orderOrderID[$i]</strong></p><p id='number'>| <strong>$orderAmountProd[$i]</strong> Product(s)</p>";
					echo "</div>";
					echo "<div id='right'>";
					echo "<p id='date'>Performed the: <strong>$orderDate[$i]</strong></p>";
					echo "</div>";
					echo "</button>";
					echo "<div class='panel'>";
					echo "<br><br>";
					echo "<table>";
					echo "<tr>";
					echo "<th>Product</th>";
					echo "<th>Price</th>";
					echo "<th>Units</th>";
					echo "<th>Total</th>";
					echo "</tr>";

					for($j=0;$j<count($productName);$j++){				// All orders
						for($k=0;$k<count($productName[$j]);$k++){		// All values
							if($j == $i){
				    			echo "<tr>";
				    			echo "<td><a href='product_info.php?id=".$orderProduct[$j][$k]."'>".$productName[$j][$k]."</a></td>";
				    			echo "<td>".$productPrice[$j][$k]."€</td>";
				    			echo "<td>".$orderAmount[$j][$k]."</td>";
				    			echo "<td>".$productPrice[$j][$k] * $orderAmount[$j][$k]."€</td>";
				    			echo "</tr>";
							}
						}
					}
					echo "</table>";
					echo "<br>";
					echo "<p>Total price: <strong>$orderPrice[$i]€</strong></p>";
					echo "</div>";
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

