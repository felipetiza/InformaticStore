<!DOCTYPE html>
<html>
<head>
	<title>Product Info</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/product_info.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
	<script>
		document.addEventListener("load", function(){
			loadModalWindow(false);
			// loadModalWindow(true);
		}, true);
    </script>
</head>
<body>

 	<?php
		include_once "db_connection.php";
		include_once "management.php";

	    session_start();
		checkAccesOption("User");

		// If hasn't arrived the product id from menu.php
		if(!isset($_GET["id"]))
			header('Location: menu.php');

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: '.MAIN_PAGE);
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['userID']);
		$username = $userData['username'];

		// Product data from current product
		$productData = getProductData($connection, $_GET['id']);
		$productName     = $productData['name'];
		$productCategory = $productData['category'];
		$productDescrip  = $productData['description'];
		$productPrice    = $productData['price'];
		$productAmount   = $productData['amount'];
		$productImage    = $productData['urlimage'];

		// Actions of shopping cart
		refreshCart($connection);

        if(isset($_POST["add"])){
        	if($productAmount > 0){
	    		$productInserted = checkProductIsInserted($connection, $_GET["id"]);

				if(!$productInserted)
					addToCart($connection, $_SESSION["userID"], $_GET["id"], $_POST["amountToAdd"]);
				else{
					$productAmount = getAmountProductOfCart($connection, $_GET["id"]);
					riseAmountProductOfCart($connection, $productAmount, $_POST["amountToAdd"], $_SESSION["userID"], $_GET["id"]);
				}
				refreshCart($connection);
        	}else
                showToast("The product is out of stock");
		}
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
				makePurchase($connection, $_SESSION['userID'], $cartProductsNumber, $cartTotalPrice);
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
				<label><?php echo $cartProductsNumber; ?></label><img class="myBtn" src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<a href="menu.php">Home</a> >
		        	<a href="menu.php?categ=<?php echo strtolower($productCategory); ?>">
		        		<?php echo $productCategory ?>
		        	</a>
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
			<div id="row1">
				<div id="image">
					<img src="<?php echo $productImage; ?>">
				</div>
				<div id="info">
					<h1><?php echo $productName; ?></h1>
					<hr>
					<h2>
						<!-- MySQL automatically inserts 2 decimal zeros even if you do not specify it -->
						<?php
							if($position = strpos($productPrice, ".00")){
								$integer = substr($productPrice, 0, $position);
								echo "<label id='integer'>".$integer."€</label>";
							}else if($position = strpos($productPrice, ".")){
								$integer = substr($productPrice, 0, $position);
								$decimal = substr($productPrice, $position + 1, $position + 2);
								echo "<label id='integer'>".$integer.",</label>
								      <label id='decimal'>".$decimal."€</label>";
							}
						?>
					</h2>
					<h3>Availability:
						<?php
							if($productAmount > 0)
								echo "<label id='green'>In Stock!</label>";
							else
								echo "<label id='red'>Sold out</label>";
						?>
					</h3>
			        <form method="post" id="buttons">
						<h3>Quantity:
							<?php $min = ($productAmount > 0) ? 1 : 0 ?>
							<input type="number" name="amountToAdd" min="<?php echo $min; ?>" max="<?php echo $productAmount; ?>" value="<?php echo $min; ?>" >
						</h3>
						<input type="submit" name="add" value="Add to cart">
						<!-- <input type="submit" name="buyDirectly" value="Buy"> -->
			        </form>
				</div>
			</div>
			<div id="row2">
				<p><?php echo nl2br($productDescrip); ?></p>
			</div>
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