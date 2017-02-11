<!DOCTYPE html>
<html>
<head>
	<title>Product Info</title>
	<link rel="stylesheet" href="css/product_info.css">
	<script src="js/author.js"></script>
	<meta charset="UTF-8">
</head>
<body>

 	<?php
		include_once "db_connection.php";

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

        // Get product data
		$productName;
		$productCategory;
		$productDescrip;
		$productPrice;
		$productAmount;
		$productImage;

        $getproduct = "SELECT *
                       FROM product
                       WHERE idproduct = {$_GET['id']};
                      ";

        if ($result = $connection->query($getproduct)) {
            if ($result->num_rows > 0){
            	$product = $result->fetch_object();

				$productName     = $product->name;
				$productCategory = $product->category;
				$productDescrip  = $product->description;
				$productPrice    = $product->price;
				$productAmount   = $product->amount;
				$productImage    = $product->urlimage;
            }else
                echo "Impossible to get the product";
        }else
            echo "Wrong Query";



        // ***********************
		// Add to Cart
        // ***********************

        // Check wheter the product id has already been inserted
        // - Reason? Can not insert 2 times the id product (it's primary key)
        // - Solution? Sum the new quantity to the product quantity
        if(isset($_POST["addToCart"]) && $_POST["quantity"] > 0){
			$productInserted = false;

            $getproduct = "SELECT idproduct
            			   FROM shopping_cart
            			   WHERE idproduct = {$_GET["id"]};
                          ";

            if ($result = $connection->query($getproduct))
				$productInserted = ($result->num_rows > 0) ? true : false;
            else
                echo "Wrong Query";

			if(!$productInserted){
	            $getproduct = "INSERT INTO shopping_cart
	            			   VALUES({$_SESSION["iduser"]}, {$_GET["id"]}, {$_POST["quantity"]});
	                          ";

	            if ($result = $connection->query($getproduct)){
	                if (!$result)
	                    echo "Impossible insert the product within shopping cart";
	            }else
	                echo "Wrong Query";
			}else{
				$amount = 0;
				// 1. Select the amount of that product
	            $getproduct = "SELECT amount
	            			   FROM shopping_cart
	            			   WHERE idproduct = {$_GET["id"]};
	                          ";

	            if ($result = $connection->query($getproduct)){
	                if ($result->num_rows > 0)
		            	$amount = $result->fetch_object()->amount;
	            }else
	                echo "Wrong Query";

				// 2. Sum the new quantity
	            $amount += $_POST["quantity"];

	            $getproduct = "UPDATE shopping_cart
	            			   SET amount = $amount
	                           WHERE idproduct = {$_GET["id"]};
	                          ";

	            if ($result = $connection->query($getproduct)){
	                if (!$result)
	                    echo "Impossible update the new quantity of a product";
	            }else
	                echo "Wrong Query";
			}
		}

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
		<div id="basic">
	        <div id="user"><?php echo "<p>Welcome, $username</p>" ?></div>
			<div id="title"><h1>Product Info</h1></div>
			<div id="cart"><label><?php echo $amount; ?></label><img src="resources/img/cart.png"></div>
		</div>
        <hr>
        <div id="infobar">
	        <div id="path">
	        	<a href="menu.php">Home</a> >
	        	<a href="menu.php?categ=<?php echo strtolower($productCategory); ?>"><?php echo $productCategory ?></a>
	        </div>
	        <form method="post" id="unlogin">
				<input type="submit" name="unlogin" value="Logout">
	        </form>
        </div>
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
							<input type="number" name="quantity" min="<?php echo $min; ?>" max="<?php echo $productAmount; ?>" value="<?php echo $min; ?>" >
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
	</div>
</body>
</html>