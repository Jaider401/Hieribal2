<section style="margin-top:20px;">
  <h2 class="h1">Hola, <?= htmlspecialchars($_SESSION['cliente']['nombres'] ?? 'cliente') ?></h2>
  <p class="lead">Este es tu panel.</p>
</section>
