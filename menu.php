<!DOCTYPE html>
<html>
<head>
	<title>Menu</title>
	<link rel="stylesheet" href="css/menu.css">
	<script>
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
	</script>
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

            // Get products data
			$productID    = [];
			$productName  = [];
			$productPrice = [];
			$productImage = [];

            $getproduct = "SELECT *
                           FROM product;
                          ";

            if ($result = $connection->query($getproduct)) {
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
	    			echo "<div class='product' data-id='$productID[$i]'>";
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

