<h2><?= $header ?></h2>
<?php if($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif ?>
<?php if($errors): ?>
    <ul class="errors list">
    <?php foreach($errors as $error): ?>
        <li><?= $error ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif ?>
<form action="<?= $form_action ?>" method="POST" class="content-form">
    <label><span>Imię: </span><input name="name" value="<?= $name ?>"></label>
    <label><span>Nazwisko: </span><input name="surname" value="<?= $surname ?>"></label>
    <label><span>Login: </span><input name="login" value="<?= $login ?>"></label>
    <label><span>Hasło: </span><input type="password" name="password" value="<?= $password ?>"></label>
    <label><span>Powtórz hasło: </span><input type="password" name="repeated_password" value="<?= $repeated_password ?>"></label>
    <?php if($editing): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="submit" value="Zapisz zmiany" class="submit-edit">
        <a href="/" class="button-like link-decline">Odrzuć zmiany</a>
    <?php else: ?>
        <input type="submit" value="Rejestruj" class="submit-one">
    <?php endif ?>
</form>