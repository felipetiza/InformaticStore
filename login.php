<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
</head>
<body>

    <?php
        if (isset($_POST["user"])) {

            $connection = new mysqli("localhost", "tf", "123456", "tf");

            if ($connection->connect_errno) {
                printf("Connection failed: %s\n", $connection->connect_error);
                exit();
            }

            //Password coded with md5 at the database. Look for better options
            $consulta="select * from usuario where
            username='".$_POST["user"]."' and password=md5('".$_POST["password"]."');";

            //Test if the query was correct
            //SQL Injection Possible
            //Check http://php.net/manual/es/mysqli.prepare.php for more security
            if ($result = $connection->query($consulta)) {

                //No rows returned
                if ($result->num_rows === 0) {
                    echo "LOGIN INVALIDO";
                } else {
                    //VALID LOGIN. SETTING SESSION VARS
                    $_SESSION["user"]     = $_POST["user"];
                    $_SESSION["language"] = "es";

                    header("Location: index.php");
                }

            }else
                echo "Wrong Query";
        }
    ?>

    <form action="login.php" method="post">
        <p><input name="user" required></p>
        <p><input name="password" type="password" required></p>
        <p><input type="submit" value="Log In"></p>
    </form>

</body>
</html>
