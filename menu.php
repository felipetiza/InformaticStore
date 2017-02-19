<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/resources.css">
	<link rel="stylesheet" href="css/menu.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
	<script>
		// // Get productID of the product clicked and send it to product_info.php by POST
		// window.onload = function(){
		// 	var listProduct = document.querySelectorAll(".product");

		// 	for(i in listProduct){
		// 		listProduct[i].onclick = function() {
		// 			var productID = this.dataset.id;	// Get ID value - Attribute 'data-id' of product class
		// 			console.log(productID);

		// 			// How to I send the productID by POST? I made a form and clicked it
		// 			// Impossible by AJAX. Don't let me change the screen. It just return me the result
		// 			var form = "<form action='product_info.php' method='post' id='changeScreen'><input id='press' type='submit' name='productID' value='"+productID+"'></form>";
		// 			var content = document.getElementById("wrapper").insertAdjacentHTML("afterend", form);
		// 			document.getElementById("changeScreen").style.display = 'none';
		// 			document.getElementById('press').click();
		// 		};
		// 	}
		// };
		document.addEventListener("load", function(){
			loadModalWindow(false);
		}, true);
	</script>
</head>
<body>

 	<?php
		include_once "db_connection.php";
		include_once "management.php";

	    session_start();

		// If user is not logged
		if(!isset($_SESSION["iduser"]))
			header('Location: login.php');

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['iduser']);
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
		$productImage = $productsData['urlimage'];

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
			if(isset($_POST["buy"]))
				makePurchase($connection, $_SESSION['iduser'], $cartProductsNumber, $cartTotalPrice);
			// else if(isset($_POST["buyDirectly"])){	// Products within cart + current product
			// 	$money = $cartTotalPrice + ($productPrice * $_POST["amountToAdd"]);
			// 	makePurchase($connection, $_SESSION['iduser'], $cartProductsNumber + 1, $money);
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
				<label><?php echo $cartProductsNumber; ?></label><img class="myBtn" src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<label>Your computer store and online technology.</label>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a class="myBtn">Shopping Cart</a>
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
		<div id="myModal" class="modal">
			<div class="modal-content">
				<p id="cartEmpty">The cart is empty</p>
				<div id="inner">
				    <span class="close">&times;</span>
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

