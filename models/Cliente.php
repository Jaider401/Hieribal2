<?php
namespace Models;

use Core\Database;
use PDO;

final class Cliente {
    private PDO $pdo;

    public function __construct(array $config) {
        $this->pdo = Database::get($config['db']);
    }

    // ---------------- EXISTENTES ---------------- //
    public function buscarPorCorreo(string $correo): ?array {
        $st = $this->pdo->prepare('SELECT * FROM clientes WHERE correo = :c LIMIT 1');
        $st->execute([':c' => $correo]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function correoExiste(string $correo): bool {
        $st = $this->pdo->prepare('SELECT 1 FROM clientes WHERE correo = :c LIMIT 1');
        $st->execute([':c'=>$correo]);
        return $st->fetchColumn() !== false;
    }

    public function cedulaExiste(string $cedula): bool {
        $st = $this->pdo->prepare('SELECT 1 FROM clientes WHERE cedula = :d LIMIT 1');
        $st->execute([':d'=>$cedula]);
        return $st->fetchColumn() !== false;
    }

    public function crear(array $d): int {
        $hash = password_hash($d['password'], PASSWORD_DEFAULT);
        $st = $this->pdo->prepare(
          'INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, `contraseña`, fecha_registro, verificado)
           VALUES (:cedula, :nombres, :apellidos, :telefono, :correo, :pass, NOW(), 1)'
        );
        $st->execute([
          ':cedula'    => $d['cedula'],
          ':nombres'   => $d['nombres'],
          ':apellidos' => $d['apellidos'],
          ':telefono'  => $d['telefono'],
          ':correo'    => $d['correo'],
          ':pass'      => $hash,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function verificarPassword(string $correo, string $plain): false|array {
        $c = $this->buscarPorCorreo($correo);
        if (!$c) return false;
        $hash = $c['contraseña'] ?? '';
        if (password_verify($plain, $hash)) {
            return $c;
        }
        return false;
    }

    // ---------------- NUEVOS PARA GOOGLE ---------------- //
    public function crearDesdeGoogle(string $nombre, string $correo): int {
        $st = $this->pdo->prepare(
          'INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, `contraseña`, fecha_registro, verificado)
           VALUES ("", :nombres, "", "", :correo, :pass, NOW(), 1)'
        );
        $st->execute([
          ':nombres' => $nombre,
          ':correo'  => $correo,
          ':pass'    => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT)
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // ---------------- NUEVOS PARA VERIFICACIÓN POR EMAIL ---------------- //
    public function crearConVerificacion(array $d, string $token): int {
        $hash = password_hash($d['password'], PASSWORD_DEFAULT);
        $st = $this->pdo->prepare(
          'INSERT INTO clientes (cedula, nombres, apellidos, telefono, correo, `contraseña`, fecha_registro, verificado, token_verificacion)
           VALUES (:cedula, :nombres, :apellidos, :telefono, :correo, :pass, NOW(), 0, :token)'
        );
        $st->execute([
          ':cedula'    => $d['cedula'],
          ':nombres'   => $d['nombres'],
          ':apellidos' => $d['apellidos'],
          ':telefono'  => $d['telefono'],
          ':correo'    => $d['correo'],
          ':pass'      => $hash,
          ':token'     => $token,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function marcarVerificadoPorToken(string $token): bool {
        $st = $this->pdo->prepare(
          'UPDATE clientes SET verificado = 1, token_verificacion = NULL WHERE token_verificacion = :t'
        );
        $st->execute([':t'=>$token]);
        return $st->rowCount() > 0;
    }

    // ---------------- NUEVOS PARA RECUPERAR CONTRASEÑA ---------------- //
    public function setTokenRecuperacion(string $correo, string $token, \DateTime $expira): bool {
        $st = $this->pdo->prepare(
          'UPDATE clientes SET token_recuperacion = :t, recuperacion_expira = :e WHERE correo = :c'
        );
        return $st->execute([
          ':t'=>$token,
          ':e'=>$expira->format('Y-m-d H:i:s'),
          ':c'=>$correo,
        ]);
    }

    public function buscarPorTokenRecuperacion(string $token): ?array {
        $st = $this->pdo->prepare(
          'SELECT * FROM clientes WHERE token_recuperacion = :t LIMIT 1'
        );
        $st->execute([':t'=>$token]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function actualizarPasswordPorToken(string $token, string $nueva): bool {
        $hash = password_hash($nueva, PASSWORD_DEFAULT);
        $st = $this->pdo->prepare(
          'UPDATE clientes
           SET `contraseña` = :pwd, token_recuperacion = NULL, recuperacion_expira = NULL
           WHERE token_recuperacion = :t'
        );
        return $st->execute([
          ':pwd'=>$hash,
          ':t'=>$token,
        ]);
    }
}
