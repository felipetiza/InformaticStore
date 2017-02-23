<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
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
        checkAccesOptionOnLogin();

        if (isset($_POST["buttonLogIn"])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];

            $login = "SELECT idcustomer, type
                      FROM customer
                      WHERE username = ? AND
                            password = ?;
                     ";

            if ($query = $connection->prepare($login)) {

                $query->bind_param("ss", $user, $pass);
                $query->execute();
                $query->bind_result($userID, $userType);
                $query->fetch();

                if(isset($userID)){
                    $_SESSION["userID"]   = $userID;
                    $_SESSION["userType"] = $userType;

                    if($userType == "Admin")
                        header('Location: admin.php');
                    else if($userType == "User")
                        header('Location: menu.php');
                }else
                    showToast("Invalid Login");
                $query->close();
            }
        }

        if (isset($_POST["buttonSignUp"])) {
            $name    = $_POST['name'];
            $surname = $_POST['surname'];
            $email   = $_POST['email'];
            $address = $_POST['address'];
            $phone   = $_POST['phone'];
            $type    = "User";
            $user    = $_POST['user'];
            $pass    = sha1($_POST['pass']);

            $insert = "INSERT INTO customer
                       VALUES(NULL, '$name', '$surname', '$email', '$address', '$phone', '$type', '$user', '$pass');
                      ";
            if ($result = $connection->query($insert)) {
                sleep(5);
                header('Location: '.MAIN_PAGE);
            }else
                echo "Wrong Query";
        }

        // showEditScreen();
    ?>

    <img id="logo" src="resources/img/logo.png">
    <div id="wrapper">
        <form method="post">
            <div>
                <img src="resources/img/user.png">
                <input name="user" type="text" required>
            </div>
            <div>
                <img src="resources/img/pass.png">
                <input name="pass" type="password" required>
            </div>
            <div>
                <input type="submit" name="buttonLogIn" class="standardButton"  value="Log In">
            </div>
            <div class='myBtn'>Don't have an account? <a class='myBtn'>Sign Up</a></div>
        </form>
    </div>

    <!-- Modal Window -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <label class="close">&times;</label>
            <h1>Sign Up</h1>
            <hr>
            <br>
            <div id="down">
                <form method="post">
                    <div>
                        <span>Name</span>
                        <input name="name" type="text" maxlength="25" required>
                    </div>
                    <div>
                        <span>Surname</span>
                        <input name="surname" type="text" maxlength="50" required>
                    </div>
                    <div>
                        <span>Email</span>
                        <input name="email" type="email" maxlength="45" required>
                    </div>
                    <div>
                        <span>Address</span>
                        <input name="address" type="text" maxlength="100" required>
                    </div>
                    <div>
                        <span>Phone</span>
                        <input name="phone" type="tel" pattern="[0-9]{9}" required>
                    </div>
                    <div>
                        <span>Username</span>
                        <input name="user" type="text" maxlength="40" required>
                    </div>
                    <div>
                        <span>Password</span>
                        <input name="pass" type="password" maxlength="20" required>
                    </div>
                    <div>
                        <input type="submit" name="buttonSignUp" class="standardButton" value="Sign up">
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>























