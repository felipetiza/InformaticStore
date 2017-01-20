<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <?php
        include_once "db_connection.php";

        if (isset($_POST["user"])) {

            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $login = "SELECT idcustomer
                      FROM customer
                      WHERE username = ? AND
                            password = ?;
                     ";

            if ($query = $connection->prepare($login)) {

                $query->bind_param("ss", $user, $pass);
                $query->execute();
                $query->bind_result($person);
                $query->fetch();

                if(!empty($person)){
                    header('Location: menu.php');
                }else
                    echo "Invalid Login";

                $query->close();
            }
        }
    ?>

    <img id="logo" src="resources/img/logo.png">
    <form class='login' method="post">
        <div>
            <img width="32" src="resources/img/user.png">
            <input name="user" type="text" required>
        </div>
        <div>
            <img width="32" src="resources/img/pass.png">
            <input name="pass" type="password" required>
        </div>
        <div>
            <input type="submit" value="Log In">
        </div>
        <div>Don't have an account? <a href="sign_up.php">Sign Up</a></div>
    </form>

</body>
</html>























