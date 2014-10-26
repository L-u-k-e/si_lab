<h2>Logowanie</h2>
<?php if($errors): ?>
    <ul class="errors list">
    <?php foreach($errors as $error): ?>
        <li><?= $error ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif ?>
<form action="/user/login" method="POST" class="content-form">
    <label><span>Login: </span><input name="login"></label>
    <label><span>Hasło: </span><input type="password" name="password"></label>
    <input type="submit" value="Zaloguj" class="submit-one">
</form>