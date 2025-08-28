<?php $base = $this->config['app']['base_url']; ?>

<!-- HERO / BIENVENIDA -->
<section id="top" class="bienvenida">
  <div class="texto-bienvenida">
    <h1>¡Hola <span>somos Hieribal</span>!</h1>
    <p>Cuidarte naturalmente es la mejor forma de quererte. Hierbal lo hace posible.</p>
    <a href="<?= $base ?>/?r=login" class="btn-ver-todo">Iniciar sesión (Cliente)</a>
    <a href="<?= $base ?>/?r=admin_login" class="btn-ver-todo">Modo Administrador</a>

  </div>

  <div class="imagenes-bienvenida">
    <div class="img-card grande"><img src="<?= $base ?>/assets/img/IA 1.jpg" alt="Persona 1"></div>
    <div class="img-card"><img src="<?= $base ?>/assets/img/IA 2.jpg" alt="Persona 2"></div>
    <div class="img-card"><img src="<?= $base ?>/assets/img/IA 3.jpg" alt="Persona 3"></div>
    <div class="img-card grande"><img src="<?= $base ?>/assets/img/persona4.jpg" alt="Persona 4"></div>
  </div>
</section>

<!-- QUIÉNES SOMOS -->
<main id="quienes-somos" class="main-content">
  <section class="text-section">
    <h1>¿Quiénes somos?</h1>
    <p>
      Somos MI HIERBAL, un oasis de bienestar natural. Creemos que la salud es un viaje, no un destino.
      Y en cada paso de ese camino, queremos acompañarte con productos naturales de la más alta calidad.
      En nuestra tienda, encontrarás más que simples productos; encontrarás un compromiso con tu bienestar integral.
    </p>
    <div class="text-section-buttons">
      <a href="https://wa.me/573212322978?text=Hola%2C%20me%20gustaría%20más%20información%20sobre%20sus%20productos" class="btn btn-contactanos">Contáctanos</a>
      <button class="btn btn-mas-nosotros" onclick="document.getElementById('nosotros').scrollIntoView({ behavior: 'smooth' })">Más sobre nosotros</button>
    </div>
  </section>
  <section class="image-section">
    <img src="<?= $base ?>/assets/img/nature.jpg" alt="Nature background" />
  </section>
</main>

<!-- NOSOTROS -->
<section id="nosotros" class="nosotros-section">
  <div class="nosotros-about-section">
    <div>
      <div class="nosotros-text-block">
        <h2>Misión</h2>
        <p>Nuestra misión es mejorar la calidad de vida de nuestros clientes...</p>
      </div>
      <div class="nosotros-text-block">
        <h2>Visión</h2>
        <p>Ser la droguería naturista líder en la zona...</p>
      </div>
    </div>
    <div>
      <div class="commitment-card">
        <h3>Compromiso</h3>
        <p>Nuestro lema es ser comprometidos de una manera eficiente con nuestros clientes.</p>
      </div>
    </div>
  </div>
</section>

<!-- CALL TO ACTION -->
<section class="call-to-action-section">
  <h2>Únete a Nuestra Comunidad Saludable</h2>
  <p>Recibe las últimas noticias, ofertas exclusivas y consejos de bienestar directamente en tu bandeja de entrada.</p>
  <div class="form-container">
    <input type="text" placeholder="Tu Nombre" />
    <input type="email" placeholder="Tu Correo Electrónico" />
    <button type="submit">Suscribirse</button>
  </div>
</section>
<script src="../../public/js/homepage.js"></script>
