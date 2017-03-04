<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/admin_order.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
</head>
<body>

 	<?php
		include_once "management.php";

	    session_start();
	    databaseConnection();
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

		refreshOrders($connection);

		if(isset($_POST["buttonDelete"])){
			deleteOrder($connection, $_POST["orderID"]);
			refreshOrders($connection);
		}
		if(isset($_POST["openModalEdit"])){
			loadModalWindow("modalWindowEdit", "closeModalEdit");

			$orderData = getOrderData($connection, $_POST["orderID"]);
			$orderSelectedOrderID    = $orderData['orderID'];
			$orderSelectedCustomerID = $orderData['customerID'];
			$orderSelectedDate       = $orderData['date'];
			$orderSelectedAmountProd = $orderData['amountProducts'];
			$orderSelectedPrice      = $orderData['price'];

			// != 1  -> The array isn't empty. '1' because array is 2 dimensions
			// $orderProductAndAmount = getOrderProductAndAmount($connection, $_POST["orderID"]);	// Return 3 dimensions
			// $orderProduct    = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['idProduct'] : [];
			// $orderAmountProd = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['amount'] : [];


		}
		if(isset($_POST["buttonEdit"])){
			// Remove 2 tables
			deleteOrder($connection, $_POST["editOrderID"]);

			// Insert within 2 tables
			$orderData = [];
			$orderData['orderID']        = $_POST['editOrderID'];
			$orderData['customerID']     = $_POST['editCustomerID'];
			$orderData['date']           = $_POST['editDate'];
			$orderData['amountProducts'] = $_POST['editAmountProd'];
			$orderData['price']          = $_POST['editPrice'];
			insertOrder($connection, $orderData);

			$orderID_previousInserted = getLastAutoIncrementGenerated($connection, "order2", "informaticstore");

			// Pasarle la nueva relacion de productos-cantidad
			insertContain2($connection, $_SESSION['userID'], $orderID_previousInserted, $orderData['amountProducts']);

			refreshOrders($connection);
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
			<div id="title"><h1>Order</h1></div>
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
		        	<a href="admin_order.php">Order</a>
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
					<input type='submit' name='openModalAdd' class="flatButton" value='&#10133; Add Order'>
				</form>
				<label><?php echo count($orderOrderID); ?> Order(s)</label>
			</div>
			<br/>
			<table>
				<tr>
			  		<th>ID Order</th>
			  		<th>ID Customer</th>
			  		<th>Date</th>
			  		<th>Products</th>
			  		<th>Price (€)</th>
			  		<th></th>
			  		<th></th>
				</tr>
				<?php
					for($i=0;$i<count($orderOrderID);$i++){
		    			echo "<tr>";
		    			echo "<td>$orderOrderID[$i]</td>";
		    			echo "<td>$orderCustomerID[$i]</td>";
		    			echo "<td>$orderDate[$i]</td>";
		    			echo "<td>$orderAmount[$i]</td>";
		    			echo "<td>$orderPrice[$i]</td>";
		    			echo "<td>
			    				  <form method='post'>
			    					  <input type='submit' name='openModalEdit' value='✎'>
			    					  <input type='text' name='orderID' value='$orderOrderID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "<td>
			    			  	  <form method='post'>
			    					  <input type='submit' name='buttonDelete' value='&times;'>
			    					  <input type='text' name='orderID' value='$orderOrderID[$i]'>
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
				<label id="closeModalEdit" class="close">&times;</label>
		        <h1>Edit Order</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>Order ID</span>
			            	<input type="text" name="editOrderID" value="<?php echo $orderSelectedOrderID; ?>" required>
			        	</div>
						<div>
			            	<span>Customer ID</span>
			            	<input type="text" name="editCustomerID" maxlength="80" value="<?php echo $orderSelectedCustomerID; ?>" required>
			        	</div>
			        	<div>
			            	<span>Date</span>
			            	<input type="text" name="editDate" maxlength="50" value="<?php echo $orderSelectedDate; ?>" required>
			        	</div>
			        	<div>
			            	<span>Amount Products</span>
			            	<input type="text" name="editAmountProd" maxlength="2000" value="<?php echo $orderSelectedAmountProd; ?>" required>
			        	</div>
			        	<div>
			            	<span>Total Price</span>
			            	<input type="text" name="editPrice" maxlength="10" value="<?php echo $orderSelectedPrice; ?>" required>
			        	</div>
						<?php
							// for($i=0;$i<count($orderProduct);$i++){
							// 	for($j=0;$j<count($orderProduct[$i]);$j++){
							// 		echo $orderProduct[$i][$j]. " > ".$orderAmountProd[$i][$j]."<br>";
							// 	}
							// }
						?>
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



