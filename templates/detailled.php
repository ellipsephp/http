<?php $this->layout('layout', ['title' => 'Ellipse: Uncaught exception']) ?>

<?php $this->start('style') ?>
ul { margin: 0; padding: 0; list-style: none; }
li { margin: 0.5em 0; }
.info { border: 1px solid; }
.info > p { padding: 1em; margin: 0; }
.info > a { padding: 1em; margin: 0; }
div.info { background-color: #ffffe0; border-color: #ffff80; }
li.info { background-color: #f0f8ff; border-color: #80c3ff; }
li.info > a { color: black; display: block; }
li.info > a:active { margin-top: 1px; }
pre { overflow: auto; }
hr { width: 80%; border: 1px solid #dddddd; }
<?php $this->stop() ?>

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
