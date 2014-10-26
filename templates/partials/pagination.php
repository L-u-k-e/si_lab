<ul class="menu pagination">
<?php if($current_page > 1): ?>
    <li><a href="<?= str_replace('{page}', $current_page - 1, $link) ?>">Prev.</a></li>
<?php else: ?>
    <li>Prev.</li>
<?php endif ?>
<?php for($i = 1; $i <= $pages_num; ++$i): ?>
    <li<?= $i == $current_page ? ' class="active"' : '' ?>>
    <?php if($i != $current_page): ?>
        <a href="<?= str_replace('{page}', $i, $link) ?>">
            <?= $i ?>
        </a>
    <?php else: ?>
        <?= $i ?>
    <?php endif ?>
    </li>
<?php endfor ?>
<?php if($current_page < $pages_num): ?>
    <li><a href="<?= str_replace('{page}', $current_page + 1, $link) ?>">Next</a></li>
<?php else: ?>
    <li>Next</li>
<?php endif ?>
</ul>