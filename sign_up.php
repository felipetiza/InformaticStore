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
            $name    = $_POST['name'];
            $surname = $_POST['surname'];
            $email   = $_POST['email'];
            $address = $_POST['address'];
            $phone   = $_POST['phone'];
            $type    = "User";
            $user    = $_POST['user'];
            $pass    = $_POST['pass'];

            $insert = "INSERT INTO customer
                       VALUES(NULL, '$name', '$surname', '$email', '$address', '$phone', '$type', '$user', '$pass');
                      ";
            if ($result = $connection->query($insert)) {
                sleep(5);
                header('Location: login.php');
            }else
                echo "Wrong Query";
        }
    ?>

	<div id="menu">
		<form method="post">
			<div>
            	<label>Name</label>
            	<input name="name" type="text" required>
        	</div>
        	<div>
            	<label>Surname</label>
            	<input name="surname" type="text" required>
        	</div>
        	<div>
            	<label>Email</label>
            	<input name="email" type="text" required>
        	</div>
        	<div>
            	<label>Address</label>
            	<input name="address" type="text" required>
        	</div>
        	<div>
            	<label>Phone</label>
            	<input name="phone" type="text" required>
        	</div>
        	<div>
            	<label>Username</label>
            	<input name="user" type="text" required>
        	</div>
        	<div>
            	<label>Password</label>
            	<input name="pass" type="text" required>
        	</div>
            <div>
                <input type="submit" value="Sign up">
            </div>
		</form>


	</div>

</body>
</html>
