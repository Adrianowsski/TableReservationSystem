<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle ?? 'Moja Restauracja'); ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <link rel="stylesheet" href="../assets/style.css">

    <?php if (!empty($customCss)): ?>
        <link rel="stylesheet" href="/restaurant_reservation/assets/css/<?php echo htmlspecialchars($customCss); ?>">
    <?php endif; ?>

</head>
<body>
