<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/admin_customer.css">
	<link rel="stylesheet" href="css/resources.css">
	<script src="js/management.js"></script>
	<script src="js/author.js"></script>
	<script>
		document.addEventListener("load", function(){
			loadModalWindow(false);
			// loadModalWindow(true);
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

	    // If user clicked on unlogin button
		if(isset($_POST["unlogin"])){
			session_destroy();
			header('Location: login.php');
		}

		$listProductCategory = getAllProductCategory($connection);

		// User data from logged customer
		$userData = getUserData($connection, $_SESSION['iduser']);
		$username = $userData['username'];

		refreshUsers($connection);

		if(isset($_POST["tableDelete"])){
			deleteUser($connection, $_POST["userID"]);
			refreshUsers($connection);
		}
		if(isset($_POST["tableEdit"])){
			showEditScreen();

			$userData = getUserData($connection, $_POST["userID"]);
			$userSelectedID       = $userData['id'];
			$userSelectedName     = $userData['name'];
			$userSelectedSurname  = $userData['surname'];
			$userSelectedEmail    = $userData['email'];
			$userSelectedAddress  = $userData['address'];
			$userSelectedPhone    = $userData['phone'];
			$userSelectedType     = $userData['type'];
			$userSelectedUsername = $userData['username'];
			$userSelectedPassword = $userData['password'];
		}
		if(isset($_POST["edit"])){
			// Delete
			// Insert
			refreshUsers($connection);
		}
		// showEditScreen();
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
			<div id="title"><h1>Customer</h1></div>
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
		        	<a href="admin_customer.php">Customer</a>
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
			<table>
				<tr>
			  		<th>ID</th>
			  		<th>Name</th>
			  		<th>Surname</th>
			  		<th>Email</th>
			  		<th>Address</th>
			  		<th>Phone</th>
			  		<th>Type</th>
			  		<th>Username</th>
			  		<th>Password</th>
			  		<th></th>
			  		<th></th>
				</tr>
				<?php
					for($i=0;$i<count($userID);$i++){
		    			echo "<tr>";
		    			echo "<td>$userID[$i]</td>";
		    			echo "<td>$userName[$i]</td>";
		    			echo "<td>$userSurname[$i]</td>";
		    			echo "<td>$userEmail[$i]</td>";
		    			echo "<td>$userAddress[$i]</td>";
		    			echo "<td>$userPhone[$i]</td>";
		    			echo "<td>$userType[$i]</td>";
		    			echo "<td>$userUsername[$i]</td>";
		    			echo "<td>$userPassword[$i]</td>";
		    			echo "<td>
			    				  <form method='post'>
			    					  <input type='submit' name='tableEdit' class='myBtn' value='✎'>
			    					  <input type='text' name='userID' value='$userID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "<td>
			    			  	  <form method='post'>
			    					  <input type='submit' name='tableDelete' class='myBtn' value='&times;'>
			    					  <input type='text' name='userID' value='$userID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "</tr>";
		    		}
			    ?>
			</table>
		</div>

		<!-- Modal Window -->
		<div id="myModal" class="modal">
			<div class="modal-content">
				<label class="close">&times;</label>
		        <h1>Edit User</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>ID</span>
			            	<input type="text" name="name" maxlength="25" value="<?php echo $userSelectedID; ?>" required>
			        	</div>
						<div>
			            	<span>Name</span>
			            	<input type="text" name="name" maxlength="25" value="<?php echo $userSelectedName; ?>" required>
			        	</div>
			        	<div>
			            	<span>Surname</span>
			            	<input type="text" name="surname" maxlength="50" value="<?php echo $userSelectedSurname; ?>" required>
			        	</div>
			        	<div>
			            	<span>Email</span>
			            	<input type="email" name="email" maxlength="45" value="<?php echo $userSelectedEmail; ?>" required>
			        	</div>
			        	<div>
			            	<span>Address</span>
			            	<input type="text" name="address" maxlength="100" value="<?php echo $userSelectedAddress; ?>" required>
			        	</div>
			        	<div>
			            	<span>Phone</span>
			            	<input type="tel" name="phone" pattern="[0-9]{9}" value="<?php echo $userSelectedPhone; ?>" required>
			        	</div>
			        	<div>
			            	<span>Type</span>
			            	<input type="tel" name="phone" pattern="[0-9]{9}" value="<?php echo $userSelectedType; ?>" required>
			        	</div>
			        	<div>
			            	<span>Username</span>
			            	<input type="text" name="user" maxlength="40" value="<?php echo $userSelectedUsername; ?>" required>
			        	</div>
			        	<div>
			            	<span>Password</span>
			            	<input type="text" name="pass" maxlength="20" value="<?php echo $userSelectedPassword; ?>" required>
			        	</div>
			            <div>
			                <input type="submit" name="edit" class="standardButton" value="Edit">
			            </div>
					</form>
		        </div>

		  	</div>
		</div>

	</div>
</body>
</html>

