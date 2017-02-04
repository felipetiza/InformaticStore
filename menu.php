<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<link rel="stylesheet" href="css/menu.css">
</head>
<body>

 	<?php
		include_once "db_connection.php";

	    session_start();
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		if(isset($_SESSION["iduser"])){
			$username = "";

			// Get username from database
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

            // Get products data
			$productName  = [];
			$productPrice = [];
			$productImage = [];

            $getproduct = "SELECT *
                           FROM product;
                          ";

            if ($result = $connection->query($getproduct)) {
                if ($result->num_rows > 0){
                	while($product = $result->fetch_object()){
                		array_push($productName, $product->name);
                		array_push($productPrice, $product->price);
                		array_push($productImage, $product->urlimage);
                	}
                }else
                    echo "Impossible to get the products";
            }else
                echo "Wrong Query";
		}else
			header('Location: login.php');
	?>

	<div id="wrapper">
		<h1>Menu</h1>
        <hr>
        <form method="post" id="unlogin">
			<input type="submit" name="unlogin" value="Logout">
        </form>
        <?php echo "<p>Welcome, $username</p>" ?>
        <br>
		<div id="content">
			<?php
				for($i=0;$i<count($productName);$i++){
	    			echo "<div class='product'>";
	    			echo "<img src='".$productImage[$i]."'>";
	    			echo "<h3>".$productName[$i]."</h3>";
	    			echo "<p>".$productPrice[$i]."€</p>";
	    			echo "</div>";
				}
			?>
		</div>
	</div>
</body>
</html>