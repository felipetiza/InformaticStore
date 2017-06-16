<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="utf-8">
    <link rel="icon" href="resources/img/favicon.ico">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/resources.css">
    <script src="js/management.js"></script>
    <script src="js/author.js"></script>
    <script>
        document.addEventListener("load", function(){
            // Open sign_up screen
            document.getElementById("openModalWindow").onclick = function() {
                loadModalWindow('modalWindowSignUp', 'closeModalSignUp');
            };
        }, true);
    </script>
</head>
<body>

    <?php
        // File is created when finish the installation. Contains database connection variables
        file_exists("database.php") ? include_once "database.php" : header('Location: index.php');
        include_once "management.php";
        session_start();

        // These variables is into database.php
        databaseConnection(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        checkAccesOptionOnLogin();

        if (isset($_POST["buttonLogIn"])) {
            $user = trim($_POST['user']);
            $pass = sha1(trim($_POST['pass']));

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
                    $_SESSION["userID"]    = $userID;
                    $_SESSION["userType"]  = $userType;
                    $_SESSION["userTheme"] = "Fresh";

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
            $userData = [];
            $userData['id']      = 'NULL';
            $userData['name']    = trim($_POST['name']);
            $userData['surname'] = trim($_POST['surname']);
            $userData['email']   = trim($_POST['email']);
            $userData['address'] = trim($_POST['address']);
            $userData['phone']   = trim($_POST['phone']);
            $userData['type']    = "User";
            $userData['user']    = trim($_POST['user']);
            $userData['pass']    = trim($_POST['pass']);

            insertUser($connection, $userData);
            showToast("Account created with success");
        }
    ?>

    <img id="logo" src="resources/img/logo.png">
    <div id="wrapper">
        <form method="post">
            <div class="row">
                <div class="left"><img src="resources/img/user.png"></div>
                <div class="right"><input name="user" type="text" required></div>
            </div>
            <div class="row">
                <div class="left"><img src="resources/img/pass.png"></div>
                <div class="right"><input name="pass" type="password" required></div>
            </div>
            <div class="row">
                <input type="submit" name="buttonLogIn" class="standardButton"  value="Log In">
            </div>
            <div class="row">
                <label>Don't have an account? <a id="openModalWindow">Sign Up</a></label>
            </div>
        </form>
    </div>

    <!-- Modal Window -->
    <div id="modalWindowSignUp" class="modal">
        <div class="modal-content">
            <label id="closeModalSignUp" class="close">&times;</label>
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























