<?php
namespace Core;

/**
 * Clase base para los controladores.
 * Contiene utilidades comunes como:
 * - renderizar vistas dentro de la plantilla
 * - redirecciones
 * - manejo de POST
 */
abstract class Controller {
    protected array $config;

    public function __construct(array $config) {
        $this->config = $config;
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start(); // Asegura que la sesión esté activa
        }
    }

    /**
     * Renderiza una vista dentro de la plantilla principal.
     */
    protected function render(string $vista, array $data = [], string $titulo = 'App'): void {
        extract($data, EXTR_SKIP);
        ob_start();
        require __DIR__ . '/../views/' . $vista . '.php'; // Carga la vista
        $contenido = ob_get_clean();
        require __DIR__ . '/../views/plantilla.php'; // Inserta dentro de plantilla
        exit;
    }

    /**
     * Redirige a otra ruta dentro de la app.
     */
    protected function redirect(string $path): void {
        $base = rtrim($this->config['app']['base_url'], '/');
        header('Location: ' . $base . $path);
        exit;
    }

    /**
     * Verifica si la petición fue por POST.
     */
    protected function isPost(): bool {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    /**
     * Obtiene un campo del formulario POST.
     */
    protected function post(string $key, $default = '') {
        return $_POST[$key] ?? $default;
    }
    
}
