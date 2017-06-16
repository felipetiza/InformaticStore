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
<!-- 	<script>
		// When you click on the button + , a new field will be added to place the product data
		window.onload = function(){
			var cont = 1;	// The first product field (value 0) already is wrote
			var buttonAddOrder = document.getElementById('addProduct');
			console.log(buttonAddOrder);

			buttonAddOrder.addEventListener("click", function(ev){
				console.log("Ouhh yeahh!");
				var newInput = "<div class='products'><input type='text' class='inputProd' name='addProducID_"+cont+"' required><input type='number' class='inputAmount' name='addAmount_"+cont+"' min='1' value='1' required></div>";

				document.getElementById('putProducts').insertAdjacentHTML("beforeend", newInput);
				cont++;
				// console.log(newInput);
			},false);
		};
	</script> -->
</head>
<body>

 	<?php
        // File is created when finish the installation. Contains database connection variables
        file_exists("database.php") ? include_once "database.php" : header('Location: index.php');
        include_once "management.php";
        session_start();

        // The arguments variables are into database.php
        databaseConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		checkAccesOption("Admin");

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: index.php');
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
			$orderProductAndAmount = getOrderProductAndAmount($connection, $_POST["orderID"]);	// Return 2 dimensions
			$orderProduct    = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['idProduct'] : [];
			$orderAmountProd = (count($orderProductAndAmount) != 1) ? $orderProductAndAmount['amount'] : [];
		}
		if(isset($_POST["buttonEdit"])){

			// Get the product count of a order
			$orderProdAndAmount = getOrderProductAndAmount($connection, $_POST["editOrderID"]);	// Return 2 dimensions
			$orderProduct = (count($orderProdAndAmount) != 1) ? $orderProdAndAmount['idProduct'] : [];

			// Get relationship of product/amount from modalWindowEdit
			$orderProductID     = [];
			$orderProductAmount = [];
			for($i=0;$i<count($orderProduct);$i++){
				array_push($orderProductID, $_POST['editProducID_'.$i]);
				array_push($orderProductAmount, $_POST['editAmount_'.$i]);
			}

			// Remove 2 tables (Second table with 'ON DELETE CASCADE')
			deleteOrder($connection, $_POST["editOrderID"]);

			// Insert within 2 tables
			$orderData = [];
			$orderData['orderID']        = trim($_POST['editOrderID']);
			$orderData['customerID']     = trim($_POST['editCustomerID']);
			$orderData['date']           = trim($_POST['editDate']);
			$orderData['amountProducts'] = trim($_POST['editAmountProd']);
			$orderData['price']          = trim($_POST['editPrice']);
			insertOrder($connection, $orderData);
			insertContain($connection, $orderData['orderID'], $orderProductID, $orderProductAmount);

			refreshOrders($connection);
		}
		if(isset($_POST["openModalAdd"])){
			echo "
				<script>
					window.onload = function(){

						loadModalWindow('modalWindowAdd', 'closeModalAdd');

						var cont = 1;
						var buttonAddOrder = document.getElementById('addProduct');

						buttonAddOrder.addEventListener('click', function(ev){
							var newInput = '<div class=products><input type=text class=inputProd name=addProducID_'+cont+' required><input type=number class=inputAmount name=addAmount_'+cont+' min=1 value=1 required></div>';

							document.getElementById('putProducts').insertAdjacentHTML('beforeend', newInput);
							cont++;
						},false);
					};
				</script>
			";
		}
		if(isset($_POST["buttonAdd"])){
			// Get relationship of product/amount from modalWindowAdd
			// ------------------------------------------------------
			// Default 5.
			// 2 are of 1 product (input text and number)
			// 3 are of the fields (customerID, price, buttonAdd)
			$numberTotal = count($_POST);
			$numberProductToInsert = ($numberTotal - 3) / 2;

			$orderData = [];
			$orderData['orderID']        = "NULL";
			$orderData['customerID']     = $_POST['addCustomerID'];
			$orderData['date']           = "";
			$orderData['amountProducts'] = $numberProductToInsert;
			$orderData['price']          = $_POST['addPrice'];
			insertOrder($connection, $orderData);

			$orderID_previousInserted = getLastAutoIncrementGenerated($connection, "order2", "informaticstore");

			$orderProductID     = [];
			$orderProductAmount = [];
			for($i=0;$i<$numberProductToInsert;$i++){
				array_push($orderProductID, $_POST['addProducID_'.$i]);
				array_push($orderProductAmount, $_POST['addAmount_'.$i]);
			}

			insertContain($connection, $orderID_previousInserted, $orderProductID, $orderProductAmount);

			refreshOrders($connection);
		}
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
	        	<h1>Order</h1>
	        </div>
			<div id="title"></div>
			<div id="cart">
				<label>Administration</label>
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
			        	<a href="admin_customer.php">Customer</a>
			        	<a href="admin_product.php">Products</a>
			        	<a href="admin_order.php">Order</a>
			        	<a href="admin_panel.php">Control Panel</a>
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
			            	<input type="text" name="editDate" maxlength="10" value="<?php echo $orderSelectedDate; ?>" required>
			        	</div>
			        	<div>
			            	<span>Amount</span>
			            	<input type="text" class="inputDisabled" name="editAmountProd" maxlength="6" value="<?php echo $orderSelectedAmountProd; ?>" tabindex="-1">
			        	</div>
			        	<div>
			            	<span>Price</span>
			            	<input type="text" name="editPrice" maxlength="9" value="<?php echo $orderSelectedPrice; ?>" required>
			        	</div>
			        	<br/>
			        	<div>
							<span>Product(s)</span>
							<span>Product ID</span>
							<span>Amount</span>
			        	</div>
						<?php
							for($i=0;$i<count($orderProduct);$i++){
								echo "<div class='products'>";
								echo "<input type='text' class='inputProd' name='editProducID_$i' value=".$orderProduct[$i]." required>";
								echo "<input type='number' class='inputAmount' name='editAmount_$i' value=".$orderAmountProd[$i].">";
								echo "</div>";
							}
						?>
			            <div>
			                <input type="submit" name="buttonEdit" class="standardButton" value="Edit">
			            </div>
					</form>
		        </div>
		  	</div>
		</div>

		<div id="modalWindowAdd" class="modal">
			<div class="modal-content">
				<label id="closeModalAdd" class="close">&times;</label>
		        <h1>Add Order</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>Customer ID</span>
			            	<input type="text" name="addCustomerID" maxlength="80" required>
			        	</div>
			        	<div>
			            	<span>Price</span>
			            	<input type="text" name="addPrice" maxlength="9" required>
			        	</div>
			        	<br/>
			            <div>
							<span>Product(s)</span>
							<span>Product ID</span>
							<span>Amount</span>
							<span id="addProduct">&#10133;</span>
			        	</div>
						<div id="putProducts">
							<div class='products'><input type='text' class='inputProd' name='addProducID_0' required><input type='number' class='inputAmount' name='addAmount_0' min="1" value="1" required></div>
<!-- 							<div class='products'>
								<input type='text' class='inputProd' name='editProducID_$i' required>
								<input type='number' class='inputAmount' name='editAmount_$i' min="1" value="1" required>
							</div> -->
						</div>
			            <div>
			                <input type="submit" name="buttonAdd" class="standardButton" value="Add">
			            </div>
					</form>
		        </div>
			</div>
		</div>

	</div>
</body>
</html>



