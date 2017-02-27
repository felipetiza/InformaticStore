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
		if(isset($_POST["openModalEdit"])){
			loadModalWindow("modalWindowEdit");

			$productData = getProductData($connection, $_POST["productID"]);
			$productSelectedID    = $productData['id'];
			$prodSelectedName     = $productData['name'];
			$prodSelectedCategory = $productData['category'];
			$prodSelectedDescript = $productData['description'];
			$prodSelectedPrice    = $productData['price'];
			$prodSelectedAmount   = $productData['amount'];
			$prodSelectedImg      = $productData['urlImage'];
		}
		if(isset($_POST["buttonEdit"])){
			deleteProduct($connection, $_POST["editID"]);

			$productData = [];
			$productData['id']          = $_POST['editID'];
			$productData['name']        = $_POST['editName'];
			$productData['category']    = $_POST['editCategory'];
			$productData['description'] = $_POST['editDescript'];
			$productData['price']       = $_POST['editPrice'];
			$productData['amount']      = $_POST['editAmount'];
			$productData['urlImage']    = $_POST['editImg'];
			insertProduct($connection, $productData);

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

		<!-- Modal Window -->
		<div id="modalWindowEdit" class="modal">
			<div class="modal-content">
				<label class="close">&times;</label>
		        <h1>Edit User</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>ID</span>
			            	<input type="text" name="editID" value="<?php echo $productSelectedID; ?>" required>
			        	</div>
						<div>
			            	<span>Name</span>
			            	<input type="text" name="editName" maxlength="80" value="<?php echo $prodSelectedName; ?>" required>
			        	</div>
			        	<div>
			            	<span>Category</span>
			            	<input type="text" name="editCategory" maxlength="50" value="<?php echo $prodSelectedCategory; ?>" required>
			        	</div>
			        	<div>
			            	<span>Description</span>
			            	<input type="text" name="editDescript" maxlength="2000" value="<?php echo $prodSelectedDescript; ?>" required>
			        	</div>
			        	<div>
			            	<span>Price</span>
			            	<input type="text" name="editPrice" maxlength="10" value="<?php echo $prodSelectedPrice; ?>" required>
			        	</div>
			        	<div>
			            	<span>Amount</span>
			            	<input type="text" name="editAmount" value="<?php echo $prodSelectedAmount; ?>" required>
			        	</div>
			        	<div>
			            	<span>urlImage</span>
			            	<input type="text" name="editImg" maxlength="200" value="<?php echo $prodSelectedImg; ?>" required>
			        	</div>
			            <div>
			                <input type="submit" name="buttonEdit" class="standardButton" value="Edit">
			            </div>
					</form>
		        </div>
		  	</div>
		</div>

	</div>
</body>
</html>


