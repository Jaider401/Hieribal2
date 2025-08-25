<?php
namespace Controllers;

use Core\Controller;

final class HomeController extends Controller
{
    /** Home público (no requiere sesión) */
    public function index(): void
    {
        $base = $this->config['app']['base_url'];
        $this->render(
            'home/index',
            [
                // Carga CSS específico de la portada
                'extra_css' => [ $base . '/assets/css/home.css' ],
                // Si algún día necesitas JS propio de la home:
                // 'extra_js'  => [ $base . '/assets/js/home.js' ],
            ],
            'Inicio'
        );
    }

    /** Dashboard privado (solo logueados) */
    public function dashboard(): void
    {
        if (!isset($_SESSION['cliente'])) {
            // Redirige a login con base_url
            $this->redirect('/?r=login');
        }

        $this->render(
            'home/dashboard',
            [
                'cliente' => $_SESSION['cliente'] ?? null,
            ],
            'Panel'
        );
    }
}
