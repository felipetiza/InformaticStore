<!DOCTYPE html>
<html>
<head>
	<title>Product</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/admin_product.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
</head>
<body>

 	<?php
		include_once "db_connection.php";
		include_once "management.php";

	    session_start();
	    checkAccesOption("Admin");

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: '.MAIN_PAGE);
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['userID']);
		$username = $userData['username'];

		refreshProducts($connection);

		if(isset($_POST["buttonDelete"])){
			deleteProduct($connection, $_POST["productID"]);
			refreshProducts($connection);
		}
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
			<div id="title"><h1>Product</h1></div>
			<div id="cart">
				<label><?php echo $cartProductsNumber; ?></label><img src="resources/img/cart.png">
			</div>
		</div>
        <hr>
		<!-- Level 2 -->
        <div id="infobar">
	        <div id="left">
		        <div id="path">
		        	<a href="admin.php">Home</a> >
		        	<a href="admin_product.php">Product</a>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a>Shopping Cart</a>
			        	<a href="order.php">Orders</a>
						<form method="post"><input type="submit" name="unlogin" value="Logout"></form>
			        </div>
			    </div>

	        </div>
        </div>
        <!-- Level 3 -->
        <br>
		<div id="content">
			<div id="up">
				<form method='post'>
					<input type='submit' name='openModalAdd' class="flatButton" value='&#10133; Add Product'>
				</form>
				<label><?php echo count($productID); ?> Product(s)</label>
			</div>
			<br/>
			<table>
				<tr>
			  		<th>ID</th>
			  		<th>Name</th>
			  		<th>Category</th>
			  		<th>Description</th>
			  		<th>Price</th>
			  		<th>Amount</th>
			  		<th>urlImage</th>
			  		<th></th>
			  		<th></th>
				</tr>
				<?php
					for($i=0;$i<count($productID);$i++){
		    			echo "<tr>";
		    			echo "<td>$productID[$i]</td>";
		    			echo "<td>";
		    					shortenStrings($productName[$i], 42);
		    			echo "</td>";
		    			echo "<td>$productCategory[$i]</td>";
		    			echo "<td>";
		    					shortenStrings($productDescript[$i], 20);
		    			echo "</td>";
		    			echo "<td>$productPrice[$i]</td>";
		    			echo "<td>$productAmount[$i]</td>";
		    			echo "<td>";
								shortenStrings($productImg[$i], 15);
		    			echo "</td>";
		    			echo "<td>
			    				  <form method='post'>
			    					  <input type='submit' name='openModalEdit' value='✎'>
			    					  <input type='text' name='productID' value='$productID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "<td>
			    			  	  <form method='post'>
			    					  <input type='submit' name='buttonDelete' value='&times;'>
			    					  <input type='text' name='productID' value='$productID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "</tr>";
		    		}
			    ?>
			</table>
		</div>



	</div>
</body>
</html>


