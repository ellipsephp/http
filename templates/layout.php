<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title><?= $title ?></title>
        <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
        <style>
            body { font-family: 'Titillium Web', sans-serif; }
            a { color: blue; text-decoration: none; }
            small { color: #555; }
            div.container { margin: 0 auto; }
            @media print { div.container { margin: 0 1em; } }
            @media screen and (max-width: 960px) { div.container { width: 100%; padding-bottom: 1024px; } }
            @media screen and (min-width: 960px) and (max-width: 1224px) { div.container { width: 80%; padding-bottom: 1024px; } }
            @media screen and (min-width: 1224px) { div.container { width: 60%; padding-bottom: 1024px; } }
            <?= $this->section('style') ?>
        </style>
    </head>
    <body>
        <div class="container">
            <?= $this->section('content') ?>
        </div>
    </body>
</html>
