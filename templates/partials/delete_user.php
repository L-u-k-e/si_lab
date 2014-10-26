<h2>Wybór użytkownika do usunięcia</h2>
<p>Rekordów spełniających kryteria: <?= $count ?></p>
<?php if($error_message): ?>
    <div class="error"><?= $error_message ?></div>
<?php endif ?>
<table class="records-list">
    <tr>
        <th>Usuń</th>
        <th>ID</th>
        <th>Login</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Poziom dostępu</th>
    </tr>
<?php if($users): ?>
    <?php foreach($users as $user): ?>
        <tr>
            <td>
            <?php if($user['access_level'] < 4 || $user['id'] == $logged_user_id): ?>
                <a href="/user/delete?id=<?= $user['id'] ?>">Usuń</a>
            <?php endif ?>
            </td>
            <td><?= $user['id'] ?></td>
            <td><?= $user['login'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['surname'] ?></td>
            <td><?= $user['access_level'] ?></td>
        </tr>
    <?php endforeach ?>
<?php else: ?>
    <tr><td colspan="5">Brak rekordów do pokazania.</td></tr>
<?php endif ?>
</table>
<?php if($users): ?>
    <?= $pagination ?>
<?php endif ?>