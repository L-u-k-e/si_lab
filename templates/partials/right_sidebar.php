<?php if($logged_user->access_level >= 1): ?>
<form action="/employee/search">
    <label>
        Wyszukaj: <input name="q">
    </label>
    <input type="submit" value="Szukaj">
</form>
<?php endif ?>

<ul id="login-menu" class="menu">
<?php if($logged_in): ?>
    <li>Zalogowany jako: <?= $logged_user->safe_get('login') ?>.</li>
    <li><a href="/user/logout">Wyloguj</a></li>
<?php else: ?>
    <li><a href="/user/login">Zaloguj</a></li>
<?php endif ?>
    <li><a href="/user/register">Rejestracja</a></li>
</ul>