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

		refreshUser($connection);

		if(isset($_POST["edit"])){
			echo $_POST["userID"];
			showEditScreen();

		}
		if(isset($_POST["delete"])){
			deleteUser($connection, $_POST["userID"]);
			refreshUser($connection);
		}
		error_log("Hola Mundo");
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
			    					  <input type='submit' name='edit' class='myBtn' value='✎'>
			    					  <input type='text' name='userID' value='$userID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "<td>
			    			  	  <form method='post'>
			    					  <input type='submit' name='delete' class='myBtn' value='&times;'>
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
				<span class="close">&times;</span>
				<p>Hola Mundo</p>
		  	</div>
		</div>

	</div>
</body>
</html>

