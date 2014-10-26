<h2><?= $header ?></h2>
<form action="<?= $form_action ?>" method="POST" class="confirmation-form">
    <fieldset>
        <legend><?= $question ?></legend>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="submit" name="confirmation" value="Tak">
        <input type="submit" name="confirmation" value="Nie">
    </fieldset>
</form>