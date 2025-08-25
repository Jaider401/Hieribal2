<div class="login-page">
  <div class="login">
    <div class="left-panel">
      <img src="<?= $this->config['app']['base_url'] ?>/assets/img/logo.png" class="logo" alt="Logo">

      <div class="form-content">
        <h2>Crea tu cuenta</h2>
        <p>Completa tus datos para empezar</p>

        <?php if (!empty($error)): ?>
          <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="?r=do_register" autocomplete="off">
          <!-- Fila 1: 3 columnas -->
          <div class="form-row form-3">
            <input type="text"   name="cedula"    placeholder="Cédula"   required>
            <input type="text"   name="nombres"   placeholder="Nombres"  required>
            <input type="text"   name="apellidos" placeholder="Apellidos">
          </div>

          <!-- Fila 2: 2 columnas -->
          <div class="form-row form-2">
            <input type="tel"    name="telefono"  placeholder="Teléfono" inputmode="tel">
            <input type="email"  name="correo"    placeholder="Correo"   required autocomplete="email">
          </div>

          <!-- Fila 3: 1 columna -->
          <input type="password" name="password"  placeholder="Contraseña" required autocomplete="new-password">

          <button type="submit" class="submit-btn">Crear cuenta</button>
        </form>


        <p class="bottom-link">¿Ya tienes una cuenta?
          <a href="?r=login">Inicia sesión</a>
        </p>
      </div>
    </div>

    <div class="right-panel">
      <video autoplay muted loop playsinline>
        <source src="<?= $this->config['app']['base_url'] ?>/assets/video/video.mp4" type="video/mp4">
      </video>
    </div>
  </div>
</div>
