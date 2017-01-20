<style>
    span {
        width: 100px;
        display: inline-block;
    }
</style>

<?php if (!isset($_POST["name"])) : ?>
    <form method="post">
        <fieldset>
            <legend>Personal Info</legend>
            <span>Name:</span><input type="text" name="name" required><br>
            <span>Password:</span><input type="text" name="pass" required><br>
            <p><input type="submit" value="Send"></p>
        </fieldset>
    </form>
<?php else:
        $nombre = $_POST["name"];
        $clave = $_POST["pass"];

        if($nombre == "admin" && $clave == "1234"){
            echo "Usuario correcto";
        }else{
            echo "Usuario incorrecto";
        }
    endif
?>







