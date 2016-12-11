<?php
/**
 * Created by IntelliJ IDEA.
 * User: peter
 * Date: 11.11.2016
 * Time: 20:35
 */
?>
<style>
    .formular {
        text-align: center;
    }
    form {
        margin-bottom: 30px;
    }
    input {
        color: #FFFFFF;
        border: none;
        background-color: rgb(67, 79, 100);
        width: 80%;
    }
</style>
<div class="formular">

    <h1>MyTrack</h1>

    <form class="login_form">
        <input type="text" name="username" placeholder="Nickname" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <br>
        <input type="submit" value="Login">
    </form>

    <form class="register_form">
        <input type="text" name="username" placeholder="Nickname" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <input type="password" name="password_validation" placeholder="Passwort erneut eingeben" required>
        <br>
        <input type="submit" value="Registrieren">
    </form>

</div>
