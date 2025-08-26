<?php
namespace Controllers;

use Core\Controller;
use Models\Usuario;

final class AdminDashboardController extends Controller {
    private Usuario $usuarios;

    public function __construct(array $config) {
        parent::__construct($config);
        $this->usuarios = new Usuario($config);
    }

public function index(): void {
    if (empty($_SESSION['admin'])) {
        $_SESSION['admin_error'] = 'Inicia sesión para continuar.';
        $this->redirect('/?r=admin_login');
    }

    $this->render('admin/dashboard', [
        'admin'          => $_SESSION['admin'],
        'totalUsuarios'  => $this->usuarios->totalUsuarios(),
        'totalActivos'   => $this->usuarios->totalPorEstado('Activo'),
        'totalAdmins'    => $this->usuarios->totalPorRol('Admin'),
        'totalEmpleados' => $this->usuarios->totalPorRol('Empleado'),
        'totalCajeros'   => $this->usuarios->totalPorRol('Cajero'),
        'esAdmin'        => true,
        'extra_js'       => [
            // Chart.js (CDN)
            'https://cdn.jsdelivr.net/npm/chart.js',
            // Tu script del panel
            $this->config['app']['base_url'] . '/assets/js/admin-dashboard.js',
        ],
    ], 'Dashboard');


        $this->render('admin/dashboard', [
        'admin'          => $_SESSION['admin'],
        'totalUsuarios'  => $this->usuarios->totalUsuarios(),
        'totalActivos'   => $this->usuarios->totalPorEstado('Activo'),
        'totalAdmins'    => $this->usuarios->totalPorRol('Admin'),
        'totalEmpleados' => $this->usuarios->totalPorRol('Empleado'),
        'totalCajeros'   => $this->usuarios->totalPorRol('Cajero'),
        'esAdmin'        => true, // <- clave
        ], 'Dashboard');

        }

}
