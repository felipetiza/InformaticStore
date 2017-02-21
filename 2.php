<style>
    span {
        width: 100px;
        display: inline-block;
    }
</style>

<?php if (!isset($_POST["mail"])) : ?>
<form method="post">
    <fieldset>
        <legend>Personal Info</legend>
        <span>Name:</span><input type="text" name="name" required><br>
        <span>Last Name:</span><input type="text" name="lastname" required><br>
        <span>Email:</span><input type="email" name="mail" required><br>
        <span>Date if Birth:</span><input type="date" name="date"><br>
        <span>Sex: </span><input type="radio" name="sex" value="H">Male<input type="radio" name="sex" value="M">Female<br>
        <h3>Subjects</h3>
        <input type="checkbox" name="subjects[]" value="LM">MarkUp Languages<br>
        <input type="checkbox" name="subjects[]" value="GBD">Database Management<br>
        <input type="checkbox" name="subjects[]" value="PAR">Computer Networks<br>
        <input type="checkbox" name="subjects[]" value="IAW">Web Apps<br>
        <p><input type="submit" value="Send"></p>
    </fieldset>
</form>
<?php else:
        echo "<h3>Showing data coming from the form</h3>";
        var_dump($_POST);
      endif
?>



