<!doctype html>
<html lang="en" data-theme="dim">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $title ?? 'CI4 App' ?></title>

  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="bg-base-200">

  <?= $this->renderSection('content') ?>

</body>

</html>