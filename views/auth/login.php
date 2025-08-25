<?php
// $error y $msg pueden venir del controlador (si usas flashes en $_SESSION)
$error = $error ?? ($_SESSION['error'] ?? null);
unset($_SESSION['error']);
?>
<div class="login-page">
  <div class="login">

    <!-- Panel izquierdo: formulario -->
    <div class="left-panel">
      <img src="<?= $this->config['app']['base_url'] ?>/assets/img/logo.png" class="logo" alt="Logo">

      <div class="form-content">
        <h2>Ingresa sesión</h2>
        <p>Por favor ingresa tus credenciales</p>

        <?php if (!empty($error)): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($msg)): ?>
          <div class="success-msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <a href="<?= $this->config['app']['base_url'] ?>/?r=google_start" class="google-btn">
      <img src="<?= $this->config['app']['base_url'] ?>/assets/img/google.png" alt="Google">
      <span>Ingresar con Google</span>
    </a>


        <div class="separator"><span>O</span></div>

        <form method="POST" action="?r=do_login">
          <input type="email" name="correo" placeholder="Email address" required>
          <input type="password" name="password" placeholder="Contraseña" required>

          <div class="extras">
            <label><input type="checkbox" name="recordar"> Recuérdame por 30 días</label>
            <a href="<?= $this->config['app']['base_url'] ?>/?r=forgot">Olvidé mi contraseña</a>
          </div>

          <button type="submit" class="submit-btn">Ingresar</button>
        </form>

        <p class="bottom-link">¿No tienes una cuenta?
          <a href="?r=register">Regístrate aquí</a>
        </p>
      </div>
    </div>

    <!-- Panel derecho: video -->
    <div class="right-panel">
<video
  autoplay
  muted
  loop
  playsinline
  preload="auto"
  disablepictureinpicture
  controlslist="nodownload nofullscreen noremoteplayback"
  aria-hidden="true"
  tabindex="-1"
>
  <source src="<?= $this->config['app']['base_url'] ?>/assets/video/video.mp4" type="video/mp4">
</video>


    </div>

  </div>
</div>
