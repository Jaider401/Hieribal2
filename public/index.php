<?php
declare(strict_types=1);

use Controllers\AuthController;
use Controllers\HomeController;
use Controllers\AdminAuthController;
use Controllers\AdminDashboardController;

require __DIR__ . '/../vendor/autoload.php';

// 🔹 NO hace falta requerir ServicioCorreo manualmente si usas PSR-4
// require_once __DIR__ . '/../services/ServicioCorreo.php';

$config = require __DIR__ . '/../config/env.php';

// Controladores
$auth   = new AuthController($config);
$home   = new HomeController($config);
$adminA = new AdminAuthController($config);
$adminD = new AdminDashboardController($config);

// Ruta por defecto
$r = $_GET['r'] ?? 'home';

switch ($r) {
  // ====== Público / Home ======
  case 'home':        $home->index();      break;
  case 'dashboard':   $home->dashboard();  break; // si es tu dashboard público/cliente

  // ====== Auth de clientes ======
  case 'login':         $auth->loginForm();    break;
  case 'do_login':      $auth->login();        break;
  case 'register':      $auth->registroForm(); break;
  case 'do_register':   $auth->registrar();    break;
  case 'logout':        $auth->logout();       break;
  case 'check_field':   $auth->checkField();   break;
  case 'forgot':        $auth->forgotForm();   break;
  case 'do_forgot':     $auth->forgot();       break;
  case 'reset':         $auth->resetForm();    break;
  case 'do_reset':      $auth->reset();        break;
  case 'google_start':  $auth->googleStart();  break;
  case 'google_callback': $auth->googleCallback(); break;
  case 'verify':        $auth->verify();       break;   // <-- faltaba el break

  // ====== Admin ======
  case 'admin_login':      $adminA->loginForm();   break;
  case 'admin_do_login':   $adminA->login();       break;
  case 'admin_logout':     $adminA->logout();      break;
  case 'admin_dashboard':  $adminD->index();       break;

  // ====== 404 ======
  default:
    http_response_code(404);
    echo '404 Página no encontrada';
}
