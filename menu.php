<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<link rel="stylesheet" href="css/resources.css">
	<link rel="stylesheet" href="css/menu.css">
	<script src="js/author.js"></script>
<!-- 	<script>
		// Get productID of the product clicked and send it to product_info.php by POST
		window.onload = function(){
			var listProduct = document.querySelectorAll(".product");

			for(i in listProduct){
				listProduct[i].onclick = function() {
					var productID = this.dataset.id;	// Get ID value - Attribute 'data-id' of product class
					console.log(productID);

					// How to I send the productID by POST? I made a form and clicked it
					// Impossible by AJAX. Don't let me change the screen. It just return me the result
					var form = "<form action='product_info.php' method='post' id='changeScreen'><input id='press' type='submit' name='productID' value='"+productID+"'></form>";
					var content = document.getElementById("wrapper").insertAdjacentHTML("afterend", form);
					document.getElementById("changeScreen").style.display = 'none';
					document.getElementById('press').click();
				};
			}
		};
	</script> -->
</head>
<body>

 	<?php
		include_once "db_connection.php";

	    session_start();

		// If user is not logged
		if(!isset($_SESSION["iduser"]))
			header('Location: login.php');

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		// Get username from database
		$username = "";
        $getusername = "SELECT username
                        FROM customer
                        WHERE idcustomer = {$_SESSION['iduser']};
                       ";

        if ($result = $connection->query($getusername)) {
            if ($result->num_rows > 0)
                $username = $result->fetch_object()->username;
            else
                echo "Impossible to get the username";
        }else
            echo "Wrong Query";


        // -----------------------
		// Get products data
        // ----------------------

		// If I receive the category of a product I show it,
		// else I show all the store products
		$productID    = [];
		$productName  = [];
		$productPrice = [];
		$productImage = [];

        $getProducts = "";

        if(isset($_GET['categ']))
            $getProducts = "SELECT * FROM product WHERE category = '{$_GET['categ']}';";
        else
            $getProducts = "SELECT * FROM product;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object()){
            		array_push($productID, $product->idproduct);
            		array_push($productName, $product->name);
            		array_push($productPrice, $product->price);
            		array_push($productImage, $product->urlimage);
            	}
            }else
                echo "Impossible to get the products";
        }else
            echo "Wrong Query";


        // Get products category
		$listProductCategory = [];

        $getProducts = "SELECT DISTINCT category FROM product ORDER BY category ASC;";

        if ($result = $connection->query($getProducts)) {
            if ($result->num_rows > 0){
            	while($product = $result->fetch_object())
            		array_push($listProductCategory, $product->category);
            }else
                echo "Impossible to get the products category";
        }else
            echo "Wrong Query";


		// Get amount products from shopping cart
		$amount = 0;
        $getusername = "SELECT idproduct
                        FROM shopping_cart
                        WHERE idcustomer = {$_SESSION['iduser']};
                       ";

        if ($result = $connection->query($getusername)) {
            if ($result->num_rows > 0){
                	while($result->fetch_object())
                		$amount++;
            }else
                echo "Impossible to get the amount of products within shopping cart";
        }else
            echo "Wrong Query";

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
				<label><?php echo $amount; ?></label><img class="myBtn" src="resources/img/cart.png">
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
	</div>
</body>
</html>

