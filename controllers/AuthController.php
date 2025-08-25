<?php
namespace Controllers;

use Core\Controller;
use Models\Cliente;
use Google\Client as GoogleClient;
use Google\Service\Oauth2 as GoogleOauth2;
use Services\ServicioCorreo;

final class AuthController extends Controller
{
    private Cliente $clientes;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->clientes = new Cliente($config);
    }

    /** Muestra formulario de login */
    public function loginForm(): void
    {
        $error = $_SESSION['error'] ?? null; unset($_SESSION['error']);
        $this->render('auth/login', ['error' => $error, 'full' => true], 'Login');
    }

    /** Inicia flujo OAuth con Google */
    public function googleStart(): void
    {
        $g = $this->config['google'];

        $client = new GoogleClient();
        $client->setClientId(trim($g['client_id']));
        $client->setClientSecret(trim($g['client_secret']));
        $client->setRedirectUri(trim($g['redirect_uri']));
        $client->setAccessType('offline');
        $client->setPrompt('select_account');
        $client->addScope(['email', 'profile']);

        header('Location: ' . $client->createAuthUrl());
        exit;
    }

    /** Callback de Google OAuth */
    public function googleCallback(): void
    {
        $g = $this->config['google'];

        $client = new GoogleClient();
        $client->setClientId(trim($g['client_id']));
        $client->setClientSecret(trim($g['client_secret']));
        $client->setRedirectUri(trim($g['redirect_uri']));

        if (empty($_GET['code'])) {
            $_SESSION['error'] = 'Error en autenticación con Google.';
            $this->redirect('/?r=login');
        }

        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        if (!empty($token['error'])) {
            $_SESSION['error'] = 'No se pudo obtener token de Google.';
            $this->redirect('/?r=login');
        }

        $client->setAccessToken($token);

        $oauth2 = new GoogleOauth2($client);
        $me     = $oauth2->userinfo->get();

        $email = $me->email ?? '';
        $name  = $me->name  ?? '';
        if ($email === '') {
            $_SESSION['error'] = 'Google no devolvió un correo válido.';
            $this->redirect('/?r=login');
        }

        // Buscar/crear cuenta mínima para Google
        $c = $this->clientes->buscarPorCorreo($email);
        if (!$c) {
            // Método del modelo que inserta con valores seguros (verificado=1)
            $this->clientes->crearDesdeGoogle($name, $email);
            $c = $this->clientes->buscarPorCorreo($email);
            if (!$c) {
                $_SESSION['error'] = 'No se pudo crear el perfil con Google.';
                $this->redirect('/?r=login');
            }
        }

        session_regenerate_id(true);
        $_SESSION['cliente'] = [
            'id_cliente' => (int)($c['id_cliente'] ?? 0),
            'nombres'    => $c['nombres'] ?: $name,
            'correo'     => $c['correo'],
        ];

        $this->redirect('/?r=home');
    }

    /** Procesa login tradicional */
    public function login(): void
    {
        if (!$this->isPost()) { $this->redirect('/?r=login'); }

        unset($_SESSION['cliente']);

        $email = trim((string)$this->post('correo'));
        $pass  = (string)$this->post('password');

        if ($email === '' || $pass === '') {
            $_SESSION['error'] = 'Correo y contraseña son obligatorios.';
            $this->redirect('/?r=login');
        }

        $cliente = $this->clientes->verificarPassword($email, $pass);
        if ($cliente === false) {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            $this->redirect('/?r=login');
        }

        // Si usas verificación por email para registro normal, bloquea no verificados:
        if ((int)($cliente['verificado'] ?? 1) !== 1) {
            $_SESSION['error'] = '⚠️ Debes verificar tu cuenta desde tu correo.';
            $this->redirect('/?r=login');
        }

        session_regenerate_id(true);
        $_SESSION['cliente'] = [
            'id_cliente' => (int)$cliente['id_cliente'],
            'nombres'    => $cliente['nombres'] ?? '',
            'correo'     => $cliente['correo'],
        ];

        $this->redirect('/?r=home');
    }

    /** Endpoint AJAX: verifica existencia de correo/cedula */
    public function checkField(): void
    {
        header('Content-Type: application/json');
        $type   = $_GET['type']  ?? '';
        $value  = $_GET['value'] ?? '';
        $exists = false;

        if ($type === 'correo' && $this->clientes->correoExiste($value)) {
            $exists = true;
        }
        if ($type === 'cedula' && $this->clientes->cedulaExiste($value)) {
            $exists = true;
        }

        echo json_encode(['exists' => $exists]);
        exit;
    }

    /** Muestra formulario de registro */
    public function registroForm(): void
    {
        $error = $_SESSION['error'] ?? null; unset($_SESSION['error']);

        $base = $this->config['app']['base_url'];
        $this->render(
            'auth/registro',
            [
                'error'    => $error,
                'full'     => true,
                'extra_js' => [ $base . '/assets/js/validarRegistro.js' ],
            ],
            'Registro'
        );
    }

    /** Procesa registro (con verificación por email) */
    public function registrar(): void
    {
        if (!$this->isPost()) { $this->redirect('/?r=register'); }

        $data = [
            'cedula'    => trim((string)$this->post('cedula')),
            'nombres'   => trim((string)$this->post('nombres')),
            'apellidos' => trim((string)$this->post('apellidos')),
            'telefono'  => trim((string)$this->post('telefono')),
            'correo'    => trim((string)$this->post('correo')),
            'password'  => (string)$this->post('password'),
        ];

        // Validaciones mínimas
        if ($data['cedula']==='' || $data['nombres']==='' || $data['correo']==='' || $data['password']==='') {
            $_SESSION['error'] = 'Completa los campos obligatorios.';
            $this->redirect('/?r=register');
        }
        if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Correo inválido.';
            $this->redirect('/?r=register');
        }
        if ($this->clientes->correoExiste($data['correo'])) {
            $_SESSION['error'] = 'El correo ya está registrado.';
            $this->redirect('/?r=register');
        }
        if ($this->clientes->cedulaExiste($data['cedula'])) {
            $_SESSION['error'] = 'La cédula ya está registrada.';
            $this->redirect('/?r=register');
        }

        // Genera token y guarda verificado=0
        $token = bin2hex(random_bytes(32));
        $this->clientes->crearConVerificacion($data, $token);

        // Enviar correo de verificación
        $base = rtrim($this->config['app']['base_url'], '/');
        $link = $base . '/?r=verify&token=' . urlencode($token);

        $mailer = new ServicioCorreo($this->config);
        $mailer->enviarVerificacion($data['correo'], $data['nombres'], $link);

        $_SESSION['msg'] = '✅ Perfil creado con éxito. Te enviamos un correo para activar tu cuenta.';
        $this->redirect('/?r=login');
    }

    /** Verifica cuenta por token */
    public function verify(): void
    {
        $token = $_GET['token'] ?? '';
        if ($token === '') {
            $_SESSION['error'] = 'Enlace inválido.';
            $this->redirect('/?r=login');
        }

        if ($this->clientes->marcarVerificadoPorToken($token)) {
            $_SESSION['msg'] = '✅ Tu cuenta ha sido verificada. Ya puedes iniciar sesión.';
        } else {
            $_SESSION['error'] = 'El enlace no es válido o ya fue utilizado.';
        }
        $this->redirect('/?r=login');
    }

    /** Logout */
    public function logout(): void
    {
        unset($_SESSION['cliente']);
        session_regenerate_id(true);
        $this->redirect('/?r=login');
    }

    // ================== Recuperación de contraseña ==================

    /** Formulario: "olvidé mi contraseña" */
    public function forgotForm(): void
    {
        $error = $_SESSION['error'] ?? null; unset($_SESSION['error']);
        $msg   = $_SESSION['msg']   ?? null; unset($_SESSION['msg']);

        $this->render('auth/forgot', ['error' => $error, 'msg' => $msg, 'full' => true], 'Recuperar contraseña');
    }

    /** Procesa solicitud: genera token + expiración y envía correo */
    public function forgot(): void
    {
        if (!$this->isPost()) { $this->redirect('/?r=forgot'); }

        $correo = trim((string)$this->post('correo'));
        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Ingresa un correo válido.';
            $this->redirect('/?r=forgot');
        }

        $token  = bin2hex(random_bytes(32));
        $expira = (new \DateTime('+1 hour'));
        $this->clientes->setTokenRecuperacion($correo, $token, $expira);

        $base = rtrim($this->config['app']['base_url'], '/');
        $link = $base . '/?r=reset&token=' . urlencode($token);

        $mailer = new ServicioCorreo($this->config);
        $mailer->enviarRecuperacion($correo, $link);

        $_SESSION['msg'] = 'Si el correo existe, te enviamos un enlace para restablecer tu contraseña.';
        $this->redirect('/?r=forgot');
    }

    /** Formulario: nueva contraseña (con token) */
    public function resetForm(): void
    {
        $token = $_GET['token'] ?? '';
        if ($token === '') { $this->redirect('/?r=forgot'); }

        $cliente = $this->clientes->buscarPorTokenRecuperacion($token);
        if (!$cliente) {
            $_SESSION['error'] = 'El enlace no es válido o ya fue utilizado.';
            $this->redirect('/?r=forgot');
        }
        if (!empty($cliente['recuperacion_expira']) && new \DateTime() > new \DateTime($cliente['recuperacion_expira'])) {
            $_SESSION['error'] = 'El enlace de recuperación ha expirado.';
            $this->redirect('/?r=forgot');
        }

        $error = $_SESSION['error'] ?? null; unset($_SESSION['error']);
        $this->render('auth/reset', ['token' => $token, 'error' => $error, 'full' => true], 'Restablecer contraseña');
    }

    /** Procesa reseteo: guarda nueva contraseña e invalida token */
    public function reset(): void
    {
        if (!$this->isPost()) { $this->redirect('/?r=forgot'); }

        $token = (string)$this->post('token');
        $pass1 = (string)$this->post('password');
        $pass2 = (string)$this->post('password2');

        if ($token === '') {
            $_SESSION['error'] = 'Token inválido.';
            $this->redirect('/?r=forgot');
        }
        if ($pass1 === '' || strlen($pass1) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
            $this->redirect('/?r=reset&token=' . urlencode($token));
        }
        if ($pass1 !== $pass2) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            $this->redirect('/?r=reset&token=' . urlencode($token));
        }

        $cliente = $this->clientes->buscarPorTokenRecuperacion($token);
        if (!$cliente) {
            $_SESSION['error'] = 'El enlace no es válido o ya fue utilizado.';
            $this->redirect('/?r=forgot');
        }

        $ok = $this->clientes->actualizarPasswordPorToken($token, $pass1);
        if ($ok) {
            $_SESSION['cliente'] = [
                'id_cliente' => (int)$cliente['id_cliente'],
                'nombres'    => $cliente['nombres'] ?? '',
                'correo'     => $cliente['correo'],
            ];
            $this->redirect('/?r=home');
        } else {
            $_SESSION['error'] = 'No se pudo actualizar la contraseña.';
            $this->redirect('/?r=reset&token=' . urlencode($token));
        }
    }
}
