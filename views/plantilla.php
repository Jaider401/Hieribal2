<?php
/** Layout principal */
$full = $full ?? false;                    // Vistas full-bleed (login/registro)
$base = $this->config['app']['base_url'];  // Atajo para rutas
$isAdmin = !empty($esAdmin);               // Flag para panel admin

// Clases para <body>
$bodyClasses = [];
$bodyClasses[] = $full ? 'admin-login' : 'app';
if ($isAdmin) $bodyClasses[] = 'admin-layout';
$bodyClassAttr = implode(' ', $bodyClasses);
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($titulo ?? 'App') ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSS global del sitio público -->
  <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">

  <?php if ($isAdmin): ?>
    <!-- CSS del panel admin -->
    <link rel="stylesheet" href="<?= $base ?>/assets/css/dashboard.css">
  <?php endif; ?>

  <!-- CSS extra por página -->
  <?php if (!empty($extra_css) && is_array($extra_css)): ?>
    <?php foreach ($extra_css as $href): ?>
      <link rel="stylesheet" href="<?= htmlspecialchars($href) ?>">
    <?php endforeach; ?>
  <?php endif; ?>
</head>

<body class="<?= htmlspecialchars($bodyClassAttr) ?>">

  <?php if (!$full && !$isAdmin): ?>
    <!-- ===== Header / navegación pública (NO en admin ni en full) ===== -->
    <header class="site-header header">
      <div class="container header-wrap" style="display:flex;align-items:center;justify-content:space-between;">
        <a class="logo" href="<?= $base ?>/?r=home" aria-label="Ir al inicio">
          <img src="<?= $base ?>/assets/img/logo1.png" alt="Logo MI HIERBAL" style="height:50px;">
        </a>

        <nav aria-label="Navegación principal">
          <ul style="list-style:none;display:flex;gap:25px;margin:0;padding:0;">
            <li><a href="<?= $base ?>/?r=home#top">Inicio</a></li>
            <li><a href="<?= $base ?>/?r=home#quienes-somos">Quiénes Somos</a></li>

            <?php if (!empty($_SESSION['cliente'])): ?>
              <li><a class="nav-link" href="<?= $base ?>/?r=dashboard">Panel</a></li>
              <li><a class="btn btn-sm btn-ghost" href="<?= $base ?>/?r=logout">Salir</a></li>
            <?php else: ?>
              <li><a class="btn btn-sm" href="<?= $base ?>/?r=login">Ingresar</a></li>
              <li><a class="btn btn-sm btn-ghost" href="<?= $base ?>/?r=register">Registro</a></li>
              <li><a class="btn btn-sm" href="<?= $base ?>/?r=login">Comprar Ahora</a></li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </header>
  <?php endif; ?>

  <!-- ===== Contenido ===== -->
  <main class="site-main <?= $full ? 'site-main--full' : '' ?>">
    <?php if ($full || $isAdmin): ?>
      <!-- Sin .container en login ni en admin (las vistas ya traen su layout) -->
      <?= $contenido ?? '' ?>
    <?php else: ?>
      <div class="container">
        <?= $contenido ?? '' ?>
      </div>
    <?php endif; ?>
  </main>

  <?php if (!$full && !$isAdmin): ?>
    <!-- ===== Footer público (no en admin ni full) ===== -->
    <footer class="site-footer">
      <div class="container">
        <p style="margin:0;">© <?= date('Y') ?> MI HIERBAL • Bienestar natural</p>
      </div>
    </footer>
  <?php endif; ?>

  <!-- JS global -->
  <script src="<?= $base ?>/assets/js/app.js"></script>

  <!-- JS extra por página -->
  <?php if (!empty($extra_js) && is_array($extra_js)): ?>
    <?php foreach ($extra_js as $src): ?>
      <script src="<?= htmlspecialchars($src) ?>"></script>
    <?php endforeach; ?>
  <?php endif; ?>
</body>
</html>
