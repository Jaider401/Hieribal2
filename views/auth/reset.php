<div class="login-page">
  <div class="login">
    <div class="left-panel">
      <img src="<?= $this->config['app']['base_url'] ?>/assets/img/logo.png" class="logo" alt="Logo">

      <div class="form-content">
        <h2>Restablecer contraseña</h2>
        <p>Escribe tu nueva contraseña.</p>

        <?php if (!empty($error)): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="?r=do_reset" autocomplete="off">
          <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
          <input type="password" name="password"  placeholder="Nueva contraseña (mín. 8)" required>
          <input type="password" name="password2" placeholder="Confirmar contraseña" required>
          <button type="submit" class="submit-btn">Guardar</button>
        </form>

        <p class="bottom-link"><a href="?r=login">Volver a iniciar sesión</a></p>
      </div>
    </div>

    <div class="right-panel">
      <video autoplay muted loop playsinline disablepictureinpicture controlslist="nodownload nofullscreen noremoteplayback">
        <source src="<?= $this->config['app']['base_url'] ?>/assets/video/video.mp4" type="video/mp4">
      </video>
    </div>
  </div>
</div>
