<!DOCTYPE html>
<html>
<head>
	<title>Sign Up</title>
	<link rel="stylesheet" href="css/sign_up.css">
</head>
<body>

    <?php
        include_once "db_connection.php";

        if (isset($_POST["user"])) {

            $login = "SELECT *
                      FROM customer
                      WHERE username = '{$_POST['user']}' AND
                            password = '{$_POST['pass']}';
                     ";

            if ($result = $connection->query($login)) {

                if ($result->num_rows > 0) {
                    header('Location: menu.php');
                } else{
                    // echo "Invalid Login";
                }
            }else
                echo "Wrong Query";
        }
    ?>

	<div id="menu">
		<div id="form">
			<div>
            	<label>Name</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Surname</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Email</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Address</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Phone</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Username</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Password</label>
            	<input name="user" type="text" required>
        	</div>
		</div>


	</div>

</body>
</html>
