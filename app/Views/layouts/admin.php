<!DOCTYPE html>
<html lang="en" data-theme="dim">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'Admin') ?> | MovPrima Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css">

    <?= $this->renderSection('styles') ?>
</head>

<body class="min-h-screen bg-base-100">



    <?= $this->renderSection('scripts') ?>
</body>

</html>