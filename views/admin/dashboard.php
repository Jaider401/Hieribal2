<?php /* views/admin/dashboard.php */ ?>

<div class="dashboard-layout">
  <!-- Sidebar -->
  <nav class="sidebar">
    <div class="logo">
      <img src="<?= htmlspecialchars($this->config['app']['base_url']) ?>/assets/img/logo.png" alt="Logo Hieribal">
    </div>
    <a href="/?r=admin_dashboard"><i class="bi bi-house-door"></i> Inicio</a>
    <a href="#"><i class="bi bi-box-seam"></i> Inventario</a>
    <a href="#"><i class="bi bi-basket3"></i> Productos</a>
    <a href="#"><i class="bi bi-people"></i> Usuarios</a>
    <a href="#"><i class="bi bi-gear"></i> Configuración</a>
  </nav>

  <!-- Contenido principal -->
  <div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <button class="menu-toggle btn btn-outline-success d-md-none" data-action="toggle-sidebar">☰</button>
        <strong>Panel de inicio</strong>
      </div>
      <div class="dropdown">
        <span class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i> <?= htmlspecialchars($admin['nombre'] ?? '') ?>
        </span>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Mi cuenta</a></li>
          <li><a class="dropdown-item" href="#"><i class="bi bi-sliders"></i> Preferencias</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="/?r=admin_logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
        </ul>
      </div>
    </div>

    <main class="p-4">
      <h4>Bienvenido, <?= htmlspecialchars($admin['nombre'] ?? '') ?> (<?= htmlspecialchars($admin['rol'] ?? '') ?>)</h4>
      <p class="text-muted">Accede a tus módulos desde el menú lateral.</p>

      <!-- Tarjetas -->
      <section class="cards">
        <article class="card-stat card-blue">
          <h6>Total Usuarios</h6>
          <p class="num"><?= (int)($totalUsuarios ?? 0) ?></p>
        </article>

        <article class="card-stat card-green">
          <h6>Usuarios Activos</h6>
          <p class="num"><?= (int)($totalActivos ?? 0) ?></p>
        </article>

        <article class="card-stat card-amber">
          <h6>Administradores</h6>
          <p class="num"><?= (int)($totalAdmins ?? 0) ?></p>
        </article>

        <article class="card-stat card-cyan">
          <h6>Empleados</h6>
          <p class="num"><?= (int)($totalEmpleados ?? 0) ?></p>
        </article>
      </section>

      <!-- Gráfico -->
      <section class="panel">
        <h5 class="text-center mb-2">Distribución de usuarios por rol</h5>
        <div class="chart-container">
          <canvas id="graficoRoles"
            data-admins="<?= (int)($totalAdmins ?? 0) ?>"
            data-empleados="<?= (int)($totalEmpleados ?? 0) ?>"
            data-cajeros="<?= (int)($totalCajeros ?? 0) ?>"
            width="360" height="360"></canvas>
        </div>
      </section>
    </main>
  </div>
</div>
