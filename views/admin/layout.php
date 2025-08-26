<?php
/** Layout admin (login + dashboard) */
$base      = rtrim((string)($this->config['app']['base_url'] ?? ''), '/');
$title     = $title     ?? 'Admin';
$content   = $content   ?? '';
$bodyClass = $bodyClass ?? '';   // ej. 'admin-login' desde la vista de login
$admin     = $_SESSION['admin'] ?? null;
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- (Opcional) Bootstrap para estilos base -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Iconos (para el ojito de contraseña) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- CSS del panel admin -->
  <link rel="stylesheet" href="<?= $base ?>/assets/admin.css">
</head>
<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">

<?php if ($admin): ?>
  <!-- Topbar minimal para dashboard -->
  <header class="admin-header">
    <div class="brand">Hieribal · Admin</div>
    <nav class="admin-nav">
      <span><?= htmlspecialchars($admin['nombre'] ?: $admin['usuario'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($admin['rol'], ENT_QUOTES, 'UTF-8') ?>)</span>
      <a href="<?= $base ?>/?r=admin_dashboard">Dashboard</a>
      <a class="logout" href="<?= $base ?>/?r=admin_logout">Salir</a>
    </nav>
  </header>
<?php endif; ?>

<main class="admin-main">
  <?= $content ?>
</main>

<script src="<?= $base ?>/assets/admin.js"></script>
</body>
</html>
