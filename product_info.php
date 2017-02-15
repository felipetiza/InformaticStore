<!DOCTYPE html>
<html>
<head>
	<title>Product Info</title>
	<link rel="stylesheet" href="css/resources.css">
	<link rel="stylesheet" href="css/product_info.css">
	<script src="js/author.js"></script>
	<meta charset="UTF-8">
	<script>
        function loadToast(){
            var x = document.getElementById("toast")
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }

        function loadModalWindow(showWithoutClick){
        	var modal = document.getElementById('myModal');
			var btn   = document.getElementsByClassName("myBtn");
			var span  = document.getElementsByClassName("close")[0];

			if(showWithoutClick)
				btn[0].click();

			btn[0].onclick = function() { modal.style.display = "block"; }
			btn[1].onclick = function() { modal.style.display = "block"; }
			span.onclick = function() {
			    modal.style.display = "none";
			}
			window.onclick = function(event) {
			    if (event.target == modal)
			        modal.style.display = "none";
			}
        }

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

		// If hasn't arrived the product id from menu.php
		if(!isset($_GET["id"]))
			header('Location: menu.php');

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		// Modal window of cart doesn't vanish when remove a product
		if(!isset($_SESSION["modalWindow"]))
			$_SESSION["modalWindow"] = "false";

		if($_SESSION["modalWindow"] == "true"){
			echo "<script>document.addEventListener('load', function(){ loadModalWindow(true); }, true);</script>";
			$_SESSION["modalWindow"] = "false";
		}

		// Delete from Shopping_Cart
		if(isset($_POST["cartProductID"]))
			deleteProductFromCart($connection, $_POST["cartProductID"]);

		$listProductCategory = getProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['iduser']);
		$username = $userData['username'];

		// Product data from current product
		$productData = getProductData($connection, $_GET['id']);
		$productName     = $productData['name'];
		$productCategory = $productData['category'];
		$productDescrip  = $productData['description'];
		$productPrice    = $productData['price'];
		$productAmount   = $productData['amount'];
		$productImage    = $productData['urlimage'];

        // -----------------------
		// Get from Shopping_Cart
        // ----------------------

        // Get client's products from shopping cart
		$cartProductIDAndAmount = getProductIDFromCart($connection, $_SESSION['iduser']);
		$cartProductsNumber     = count($cartProductIDAndAmount);
		$cartTotalPrice         = 0;

        // Get products data of client
		$cartProductID     = [];
		$cartProductAmount = [];

		foreach($cartProductIDAndAmount as $id=>$amount){
			array_push($cartProductID, $id);
			array_push($cartProductAmount, $amount);
		}

		$cartProductData  = getProductDataFromCart($connection, $cartProductsNumber, $cartProductID);
		$cartProductName  = $cartProductData['name'];
		$cartProductPrice = $cartProductData['price'];

        // -----------------------
		// Add to Cart
        // ----------------------

        // Check wheter the product id has already been inserted
        // - Reason? Can not insert 2 times the id product (it's primary key)
        // - Solution? Increase the new quantity to the product quantity

        if(isset($_POST["addToCart"])){
        	if($_POST["amountToAdd"] > 0){
	    		$productInserted = checkProductIsInserted($connection, $_GET["id"]);

				if(!$productInserted)
					addToCart($connection, $_SESSION["iduser"], $_GET["id"], $_POST["amountToAdd"]);
				else{
					$productAmount = getAmountProductOfCart($connection, $_GET["id"]);
					riseAmountProductOfCart($connection, $productAmount, $_POST["amountToAdd"], $_SESSION["iduser"], $_GET["id"]);
				}
				// Refresh cart data 		header("Refresh:0");
				header("Refresh:0");
        	}else{
        		echo "<div id='toast'>The product is out of stock</div>";
                echo "<script>loadToast();</script>";
        	}
		}
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
			    <div class="dropdown">
			        <button class="dropbtnCategory">Category</button>
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
						<input type="submit" name="addToCart" value="Add to cart">
						<input type="submit" name="buy" value="Buy">
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
			    			// echo "<td class='delete'>&times;</td>";
			    			echo "<td>
				    				  <form method='post'>
				    					  <input class='delete' type='submit' name='delete' value='&times;'>
				    					  <input type='text' name='cartProductID' value='$cartProductID[$i]'>
				    				  </form>
			    				  </td>";
			    			echo "</tr>";
			    			$cartTotalPrice += $cartProductPrice[$i] * $cartProductAmount[$i];
			    		}
				    ?>
				</table>
				<br/>
				<br/>
				<p><?php echo "Total: ".$cartTotalPrice."€"; ?></p>

		  	</div>
		</div>




	</div>
</body>
</html>