<strong>
    <?= get_class($e) ?>
</strong>
<br>
<small>
    <?= $e->getMessage() ?> in <?= $e->getFile() ?>:<?= $e->getLine() ?>
</small>
