<h2><?= $header ?></h2>
<p>Rekordów spełniających kryteria: <?= $count ?></p>
<?php if($error_message): ?>
    <div class="error"><?= $error_message ?></div>
<?php endif ?>
<table class="records-list">
    <tr>
    <?php if($additional_option): ?>
        <th><?= $option_name ?></th>
    <?php endif ?>
        <th>ID</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Płeć</th>
        <th>Nazwisko pan.</th>
        <th>E-mail</th>
        <th>Kod pocztowy</th>
    </tr>
<?php if($employees): ?>
    <?php foreach($employees as $employee): ?>
        <tr>
        <?php if($additional_option): ?>
            <td><a href="<?= str_replace('{id}', $employee['id'], $option_link) ?>"><?= $option_name ?></a></td>
        <?php endif ?>
            <td><?= $employee['id'] ?></td>
            <td><?= $employee['name'] ?></td>
            <td><?= $employee['surname'] ?></td>
            <td><?= $employee['gender'] == 'male' ? 'mężczyzna' : 'kobieta' ?></td>
            <td><?= $employee['family_name'] ?></td>
            <td><?= $employee['email'] ?></td>
            <td><?= $employee['postal_code'] ?></td>
        </tr>
    <?php endforeach ?>
<?php else: ?>
    <tr><td colspan="<?= $additional_option ? 8 : 7 ?>">Brak rekordów do pokazania.</td></tr>
<?php endif ?>
</table>
<?php if($employees): ?>
    <?= $pagination ?>
<?php endif ?>