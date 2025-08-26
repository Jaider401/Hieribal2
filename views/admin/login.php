<?php
$base = rtrim((string)$this->config['app']['base_url'], '/');
ob_start();
?>
<div class="login-wrapper">
  <div class="left-panel">
    <img src="<?= $base ?>/assets/img/logo.png" alt="Logo Hieribal">
    <h1>Hieribal · Usuarios</h1>
    <p>Inicio de sesión para administradores, empleados y cajeros.</p>
  </div>

  <div class="right-panel">
    <form method="post" action="<?= $base ?>/?r=admin_do_login" class="login-box">
      <h3 class="mb-3">Iniciar sesión</h3>

      <?php if (!empty($error)): ?>
        <div class="error-msg" id="error-msg"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>

      <div class="position-relative">
        <input type="password" name="password" id="admin-password" class="form-control" placeholder="Contraseña" required>
        <i class="bi bi-eye toggle-password" onclick="toggleAdminPassword()" aria-label="Mostrar/ocultar contraseña"></i>
      </div>

      <div class="text-end mb-3">
        <a href="<?= $base ?>/?r=forgot">¿Olvidaste tu contraseña?</a>
      </div>

      <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    </form>
  </div>
</div>
<?php
$contenido = ob_get_clean();   // tu plantilla usa $contenido / $titulo
$titulo    = 'Login Admin';
include __DIR__ . '/../plantilla.php';
