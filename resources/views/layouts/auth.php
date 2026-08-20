<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Healthy Bite restaurant management">
    <title><?= e($title ?? 'Healthy Bite') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(url('/assets/css/app.css')) ?>" rel="stylesheet">
</head>
<body>
    <div class="auth-bg">
        <div class="auth-blob auth-blob-1"></div>
        <div class="auth-blob auth-blob-2"></div>
        
        <main class="auth-shell container">
            <div class="text-center mb-4">
                <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="<?= e(url('/')) ?>">
                    <span class="brand-mark">HB</span>
                    <span class="fs-3 fw-bold display-font text-dark">Healthy Bite</span>
                </a>
            </div>
            <?= $content ?>
        </main>
    </div>
</body>
</html>
