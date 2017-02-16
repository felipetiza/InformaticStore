<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/resources.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/author.js"></script>
    <script>
        function loadToast() {
            var x = document.getElementById("toast")
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
    </script>
</head>
<body>

    <?php
        include_once "db_connection.php";

        // If user is logged
        session_start();
        if(isset($_SESSION["iduser"]))
            header('Location: menu.php');

        // The user try to logging
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

                if(isset($person)){
                    $_SESSION["iduser"] = $person;
                    header('Location: menu.php');
                }else{
                    echo "<div id='toast'>Invalid Login</div>";
                    echo "<script>loadToast();</script>";
                }
                $query->close();
            }
        }
    ?>

    <img id="logo" src="resources/img/logo.png">
    <div id="wrapper">
        <form method="post">
            <div>
                <img width="32" src="resources/img/user.png">
                <input name="user" type="text" required>
            </div>
            <div>
                <img width="32" src="resources/img/pass.png">
                <input name="pass" type="password" required>
            </div>
            <div>
                <input class="standardButton" type="submit" value="Log In">
            </div>
            <div>Don't have an account? <a href="sign_up.php">Sign Up</a></div>
        </form>
    </div>
</body>
</html>























