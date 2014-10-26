<h2><?= $header ?></h2>
<?php if($errors): ?>
    <ul class="errors list">
    <?php foreach($errors as $error): ?>
        <li><?= $error ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif ?>
<form class="content-form" action="<?= $form_action ?>" method="POST">
    <label><span>Imię: </span><input name="name" value="<?= $name ?>"></label>
    <label><span>Nazwisko: </span><input name="surname" value="<?= $surname ?>"></label>
    <fieldset>
        <legend>Płeć: </legend>
        <div class="radios">
            <label><input type="radio" name="gender" value="male"<?= $gender == 'male' ? ' checked' : '' ?>> mężczyzna</label>
            <label><input type="radio" name="gender" value="female"<?= $gender == 'female' ? ' checked' : '' ?>> kobieta</label>
        </div>
    </fieldset>
    <label><span>Nazwisko panieńskie: </span><input name="family_name" value="<?= $family_name ?>"></label>
    <label><span>E-mail: </span><input name="email" value="<?= $email ?>"></label>
    <label><span>Kod pocztowy: </span><input name="postal_code" value="<?= $postal_code ?>"></label>
    <?php if($editing): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="submit" value="Potwierdź zmiany" class="submit-edit">
        <a href="/employee/edit" class="button-like link-decline">Odrzuć zmiany</a>
    <?php else: ?>
        <input type="submit" value="Dodaj" class="submit-add">
    <?php endif ?>
</form>