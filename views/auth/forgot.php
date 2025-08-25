<div class="login-page">
  <div class="login">
    <div class="left-panel">
      <img src="<?= $this->config['app']['base_url'] ?>/assets/img/logo.png" class="logo" alt="Logo">

      <div class="form-content">
        <h2>Recuperar contraseña</h2>
        <p>Ingresa tu correo y te enviaremos un enlace para restablecerla.</p>

        <?php if (!empty($error)): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($msg)): ?>
          <div class="success-msg"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <form method="POST" action="?r=do_forgot">
          <input type="email" name="correo" placeholder="Correo" required>
          <button type="submit" class="submit-btn">Enviar enlace</button>
        </form>

        <p class="bottom-link">¿Ya la recordaste?
          <a href="?r=login">Inicia sesión</a>
        </p>
      </div>
    </div>

    <div class="right-panel">
      <video autoplay muted loop playsinline disablepictureinpicture controlslist="nodownload nofullscreen noremoteplayback">
        <source src="<?= $this->config['app']['base_url'] ?>/assets/video/video.mp4" type="video/mp4">
      </video>
    </div>
  </div>
</div>
