<?php
declare(strict_types=1);

use Controllers\AuthController;
use Controllers\HomeController;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../services/ServicioCorreo.php';

if (!class_exists(\Services\ServicioCorreo::class)) {
    die('No carga Services\\ServicioCorreo. Revisa autoload y ruta.');
}
$config = require __DIR__ . '/../config/env.php';

$auth = new AuthController($config);
$home = new HomeController($config);

// Ruta por defecto: HOME (antes era 'login')
$r = $_GET['r'] ?? 'home';

switch ($r) {
  // Home público y (si quieres) dashboard privado
  case 'home':        $home->index();      break;      // muestra la portada
  case 'dashboard':   $home->dashboard();  break;      // requiere sesión (opcional)

  // Auth
  case 'login':       $auth->loginForm();    break;
  case 'do_login':    $auth->login();        break;
  case 'register':    $auth->registroForm(); break;
  case 'do_register': $auth->registrar();    break;
  case 'logout':      $auth->logout();       break;
  case 'check_field': $auth->checkField();   break;
  case 'forgot':      $auth->forgotForm();   break;   // muestra el formulario (ingresar correo)
  case 'do_forgot':   $auth->forgot();       break;   // procesa envío del correo con enlace
  case 'reset':       $auth->resetForm();    break;   // muestra formulario para nueva clave (con token)
  case 'do_reset':    $auth->reset();        break;   // guarda la nueva contraseña
  case 'google_start':    $auth->googleStart();    break;
  case 'google_callback': $auth->googleCallback(); break;
  case 'verify':        $auth->verify();  






  default:
    http_response_code(404);
    echo '404 Página no encontrada';
}
