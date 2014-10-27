<h2><?= $header ?></h2>
<p>Rekordów spełniających kryteria: <?= $count ?></p>
<?php if($error_message): ?>
    <div class="error"><?= $error_message ?></div>
<?php endif ?>
<table class="records-list">
    <tr>
        <th>ID</th>
        <th>Login</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Poziom dostępu</th>
    </tr>
<?php if($users): ?>
    <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['login'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['surname'] ?></td>
            <td>
            <?php if($user['access_level'] < 4 || $user['id'] == $logged_user_id): ?>
                <form action="/user/change-access-lvl" method="POST" class="access-level-form">
                    <select name="access_level">
                    <?php for($i = 0; $i <= 4; ++$i): ?>
                        <option value="<?= $i ?>"<?= $i == $user['access_level'] ? ' selected' : '' ?>><?= $i ?></option>
                    <?php endfor ?>
                    </select>
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <?= $csrf_field() ?>
                    <input type="submit" value="Zmień">
                </form>
            <?php else: ?>
                <?= $user['access_level'] ?>
            <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
<?php else: ?>
    <tr><td colspan="5">Brak rekordów do pokazania.</td></tr>
<?php endif ?>
</table>
<?php if($users): ?>
    <?= $pagination ?>
<?php endif ?>