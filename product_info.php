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
	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		// If user is logged
		if(isset($_SESSION["iduser"])){

			// If hasn't arrived the product id from menu.php
			if(!isset($_POST["productID"]))
				header('Location: menu.php');

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
			$productDescrip;
			$productPrice;
			$productAmount;
			$productImage;

            $getproduct = "SELECT *
                           FROM product
                           WHERE idproduct = {$_POST['productID']};
                          ";

            if ($result = $connection->query($getproduct)) {
                if ($result->num_rows > 0){
                	$product = $result->fetch_object();

					$productName    = $product->name;
					$productDescrip = $product->description;
					$productPrice   = $product->price;
					$productAmount  = $product->amount;
					$productImage   = $product->urlimage;
                }else
                    echo "Impossible to get the product";
            }else
                echo "Wrong Query";
		}else
			header('Location: login.php');
	?>

	<div id="wrapper">
		<h1>Product Info</h1>
        <hr>
        <form method="post" id="unlogin">
			<input type="submit" name="unlogin" value="Logout">
        </form>
        <?php echo "<p>Welcome, $username</p>" ?>
        <br>
		<div id="content">
			<div id="row1">
				<div id="image">
					<img src="<?php echo $productImage; ?>">
				</div>
				<div id="info">
					<h1><?php echo $productName; ?></h1>
					<hr>
					<h2><?php echo $productPrice."€"; ?></h2>
					<h3><?php echo $productAmount; ?></h3>
				</div>
			</div>
			<div id="row2">
				<p><?php echo $productDescrip; ?></p>
			</div>
		</div>
	</div>
</body>
</html>