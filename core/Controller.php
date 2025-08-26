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
     * Construye una URL absoluta a partir de una ruta relativa.
     */
    protected function baseUrl(string $path = ''): string {
        $base = rtrim((string)($this->config['app']['base_url'] ?? ''), '/');
        if ($path === '') return $base;
        return $base . (str_starts_with($path, '/') ? '' : '/') . $path;
    }

    /**
     * Redirige a otra ruta/URL.
     * - Si $to es absoluta (http/https), NO antepone base_url.
     * - Si $to es relativa, la une correctamente con base_url.
     */
    protected function redirect(string $to, int $code = 302): void {
        $to = trim($to);

        // Si ya viene con esquema http/https, usar tal cual
        if (preg_match('#^https?://#i', $to)) {
            header('Location: ' . $to, true, $code);
            exit;
        }

        // Si es relativa, prepende base_url (sin duplicar barras)
        $destino = $this->baseUrl($to);
        header('Location: ' . $destino, true, $code);
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
