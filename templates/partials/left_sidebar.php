<ul id="left-menu" class="menu">
    <li><a href="/">Strona główna</a></li>
<?php if($logged_user->access_level >= 1): ?>
    <li><a href="/employee/add">Formularz</a></li>
    <li><a href="/employee/session">Zawartość sesji</a></li>
    <li><a href="/employee/all">Baza pracowników</a></li>
<?php endif ?>
<?php if($logged_user->access_level >= 2): ?>
    <li><a href="/employee/edit">Edycja pracownika</a></li>
<?php endif ?>
<?php if($logged_user->access_level >= 3): ?>
    <li><a href="/employee/delete">Usunięcie pracownika</a></li>
<?php endif ?>
<?php if($logged_user->access_level >= 1): ?>
    <li class="new-group"><a href="/user/change-data">Zmień dane</a></li>
<?php endif ?>
<?php if($logged_user->access_level >= 4): ?>
    <li><a href="/user/change-access-lvl">Zmień poziom dostępu</a></li>
    <li><a href="/user/delete">Usuń użytkownika</a></li>
<?php endif ?>
</ul>