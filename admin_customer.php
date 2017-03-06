<!DOCTYPE html>
<html>
<head>
	<title>Customer</title>
	<meta charset="utf-8">
	<link rel="icon" href="resources/img/favicon.ico">
	<link rel="stylesheet" href="css/admin_customer.css">
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

		refreshUsers($connection);

		if(isset($_POST["buttonDelete"])){
			deleteUser($connection, $_POST["userID"]);
			refreshUsers($connection);
		}
		if(isset($_POST["openModalEdit"])){
			loadModalWindow("modalWindowEdit", "closeModalEdit");

			$userData = getUserData($connection, $_POST["userID"]);
			$userSelectedID      = $userData['id'];
			$userSelectedName    = $userData['name'];
			$userSelectedSurname = $userData['surname'];
			$userSelectedEmail   = $userData['email'];
			$userSelectedAddress = $userData['address'];
			$userSelectedPhone   = $userData['phone'];
			$userSelectedType    = $userData['type'];
			$userSelectedUser    = $userData['username'];
			$userSelectedPass    = "";	// No show pass because sha1 can't be decoded
		}
		if(isset($_POST["buttonEdit"])){
			$userData = [];
			$userData['id']      = $_POST['editID'];
			$userData['name']    = $_POST['editName'];
			$userData['surname'] = $_POST['editSurname'];
			$userData['email']   = $_POST['editEmail'];
			$userData['address'] = $_POST['editAddress'];
			$userData['phone']   = $_POST['editPhone'];
			$userData['type']    = $_POST['editType'];
			$userData['user']    = $_POST['editUser'];

			// Store the original pass. In edit screen, If password isn't put, it saved
			// the previous, else it stored the new one
			$userDataRecover = getUserData($connection, $_POST["editID"]);
			$passwordSaved   = $userDataRecover['pass'];

			$userData['pass'] = (strlen($_POST['editPass']) == 0) ? $passwordSaved : $_POST['editPass'];

			deleteUser($connection, $_POST['editID']);
			insertUser($connection, $userData);

			refreshUsers($connection);
		}
		if(isset($_POST["openModalAdd"])){
			loadModalWindow("modalWindowAdd", "closeModalAdd");
		}
		if(isset($_POST["buttonAdd"])){
			$userData = [];
			$userData['id']      = 'NULL';
			$userData['name']    = $_POST['addName'];
			$userData['surname'] = $_POST['addSurname'];
			$userData['email']   = $_POST['addEmail'];
			$userData['address'] = $_POST['addAddress'];
			$userData['phone']   = $_POST['addPhone'];
			$userData['type']    = $_POST['addType'];
			$userData['user']    = $_POST['addUser'];
			$userData['pass']    = $_POST['addPass'];
			insertUser($connection, $userData);

			refreshUsers($connection);
		}
	?>

	<div id="wrapper">
		<!-- Level 1 -->
		<div id="basic">
	        <div id="user">
	        	<h1>Customer</h1>
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
		        	<a href="admin_customer.php">Customer</a>
		        </div>
	        </div>
	        <div id="right">

	        	<div id="btnUser" class="dropdown">
			        <button class="dropbtnUser"><?php echo $username; ?></button>
			        <div class="dropdown-content-user">
			        	<a href="admin_customer.php">Customer</a>
			        	<a href="admin_product.php">Products</a>
			        	<a href="admin_order.php">Order</a>
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
					<input type='submit' name='openModalAdd' class="flatButton" value='&#10133; Add User'>
				</form>
				<label><?php echo count($userID); ?> Customer(s)</label>
			</div>
			<br/>
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
		    			// echo "<td>$userPassword[$i]</td>";
		    			echo "<td>";
		    				shortenStrings($userPassword[$i], 15);
		    			echo "</td>";
		    			echo "<td>
			    				  <form method='post'>
			    					  <input type='submit' name='openModalEdit' value='✎'>
			    					  <input type='text' name='userID' value='$userID[$i]'>
			    				  </form>
		    				  </td>";
		    			echo "<td>
			    			  	  <form method='post'>
			    					  <input type='submit' name='buttonDelete' value='&times;'>
			    					  <input type='text' name='userID' value='$userID[$i]'>
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
		        <h1>Edit User</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>ID</span>
			            	<input type="text" name="editID" value="<?php echo $userSelectedID; ?>" required>
			        	</div>
						<div>
			            	<span>Name</span>
			            	<input type="text" name="editName" maxlength="25" value="<?php echo $userSelectedName; ?>" required>
			        	</div>
			        	<div>
			            	<span>Surname</span>
			            	<input type="text" name="editSurname" maxlength="50" value="<?php echo $userSelectedSurname; ?>" required>
			        	</div>
			        	<div>
			            	<span>Email</span>
			            	<input type="email" name="editEmail" maxlength="45" value="<?php echo $userSelectedEmail; ?>" required>
			        	</div>
			        	<div>
			            	<span>Address</span>
			            	<input type="text" name="editAddress" maxlength="100" value="<?php echo $userSelectedAddress; ?>" required>
			        	</div>
			        	<div>
			            	<span>Phone</span>
			            	<input type="tel" name="editPhone" pattern="[0-9]{9}" value="<?php echo $userSelectedPhone; ?>" required>
			        	</div>
<!-- 			        	<div>
			            	<span>Type</span>
			            	<input type="tel" name="editType" value="<?php echo $userSelectedType; ?>" required>
			        	</div> -->
			        	<div>
			            	<span>Username</span>
			            	<input type="text" name="editUser" maxlength="40" value="<?php echo $userSelectedUser; ?>" required>
			        	</div>
			        	<div>
			            	<span>Password</span>
			            	<input type="text" name="editPass" maxlength="20" value="<?php echo $userSelectedPass; ?>" placeholder="New password">
			        	</div>

 			        	<?php
				        	echo "<div id='radioBox'>";
				        	echo "<span>Type</span>";

				        	if($userSelectedType == "User"){
				            	echo "<label id='labelLeft'>User
				            			  <input id='radioLeft' type='radio' name='editType' value='User' checked>
				            		  </label>";
				            	echo "<label id='labelRight'>Admin
				            			  <input id='radioRight' type='radio' name='editType' value='Admin'>
				            		  </label>";
				        	}else if($userSelectedType == "Admin"){
				        		echo "<label id='labelLeft'>User
				            			  <input id='radioLeft' type='radio' name='editType' value='User'>
				            		  </label>";
				            	echo "<label id='labelRight'>Admin
				            			  <input id='radioRight' type='radio' name='editType' value='Admin' checked>
				            		  </label>";
				        	}
				        	echo "</div>";
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
		        <h1>Add User</h1>
		        <hr>
		        <br>
		        <div id="down">
					<form method="post">
						<div>
			            	<span>Name</span>
			            	<input type="text" name="addName" maxlength="25" required>
			        	</div>
			        	<div>
			            	<span>Surname</span>
			            	<input type="text" name="addSurname" maxlength="50" required>
			        	</div>
			        	<div>
			            	<span>Email</span>
			            	<input type="email" name="addEmail" maxlength="45" required>
			        	</div>
			        	<div>
			            	<span>Address</span>
			            	<input type="text" name="addAddress" maxlength="100" required>
			        	</div>
			        	<div>
			            	<span>Phone</span>
			            	<input type="tel" name="addPhone" pattern="[0-9]{9}" required>
			        	</div>
			        	<div>
			            	<span>Username</span>
			            	<input type="text" name="addUser" maxlength="40" required>
			        	</div>
			        	<div>
			            	<span>Password</span>
			            	<input type="text" name="addPass" maxlength="20" required>
			        	</div>
			        	<div id="radioBox">
			            	<span>Type</span>
			            	<label id="labelLeft">User
			            		<input id="radioLeft" type="radio" name="addType" value="User" checked>
			            	</label>
  							<label id="labelRight">Admin
  								<input id="radioRight" type="radio" name="addType" value="Admin">
			        		</label>
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

