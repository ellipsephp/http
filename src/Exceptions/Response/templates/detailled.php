<?php $this->layout('layout', ['title' => 'Ellipse: Uncaught exception']) ?>
<h1><?= $details->current()->getMessage() ?></h1>
<h2>Inner exception</h2>
<div class="info">
    <p>
        <?php $this->insert('exception', ['e' => $details->inner()]) ?>
    </p>
</div>
<h2>Exceptions flow</h2>
<ul>
    <?php foreach ($details->linearized() as $i => $e): ?>
    <li class="info">
        <a href="#e<?= $i ?>">
            <?php $this->insert('exception', ['e' => $e]) ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
<h2>Stack traces:</h2>
<?php foreach ($details->linearized() as $i => $e): ?>
<div id="e<?= $i ?>">
    <?php $this->insert('stacktrace', ['e' => $e]) ?>
</div>
<hr>
<?php endforeach; ?>
