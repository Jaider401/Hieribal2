<?php
namespace Models;

use PDO;

final class Usuario {
    private PDO $pdo;

    public function __construct(array $config) {
        // Asegúrate de que Core\Database::get($config['db']) devuelve un PDO con ERRMODE_EXCEPTION
        $this->pdo = \Core\Database::get($config['db']);
    }

    /**
     * Verifica credenciales devolviendo el row del usuario si SON válidas.
     * Si no, devuelve false.
     */
    public function verificarPassword(string $usuarioOCorreo, string $password) {
        $sql = "SELECT 
                    id_usuario,
                    usuario,
                    correo,
                    nombres,
                    apellidos,
                    rol,
                    estado,
                    password
                FROM usuarios
                WHERE (usuario = :u OR correo = :u)
                LIMIT 1";

        // ❌ NO uses prepare(query: $sql)
        $st = $this->pdo->prepare($sql);

        // ❌ NO uses execute(params: [...])
        $st->execute([':u' => $usuarioOCorreo]);

        // ❌ NO uses fetch(mode: PDO::FETCH_ASSOC)
        $row = $st->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false; // usuario no existe
        }

        // Estado debe ser 'Activo'
        if (strcasecmp((string)$row['estado'], 'Activo') !== 0) {
            return false;
        }

        // Verificar contraseña:
        // Si ya migraste a hashes (recomendado), usa password_verify:
        if (!password_verify($password, (string)$row['password'])) {
            return false;
        }

        // Si aún tienes algunas contraseñas en texto plano y quieres soportarlas
        // durante la migración, sustituye el bloque anterior por este:
        /*
        $stored = (string)$row['password'];
        $pareceHash = str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon2') || strlen($stored) >= 50;
        if ($pareceHash) {
            if (!password_verify($password, $stored)) return false;
        } else {
            if (!hash_equals($stored, $password)) return false;
            // Opcional: migrar al vuelo
            //$this->actualizarPasswordHash((int)$row['id_usuario'], password_hash($password, PASSWORD_DEFAULT));
        }
        */

        return $row;
    }

    // Opcional: si quieres rehashear al vuelo
    private function actualizarPasswordHash(int $idUsuario, string $nuevoHash): void {
        $up = $this->pdo->prepare("UPDATE usuarios SET password = :h WHERE id_usuario = :id");
        $up->execute([':h' => $nuevoHash, ':id' => $idUsuario]);
    }


    // dentro de class Usuario { ... }

// dentro de class Usuario { ... }

public function totalUsuarios(): int {
    return (int)$this->pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
}

public function totalPorEstado(string $estado): int {
    $st = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE estado = :e");
    $st->execute([':e' => $estado]); // 'Activo' o 'Inactivo'
    return (int)$st->fetchColumn();
}

public function totalPorRol(string $rol): int {
    // En tu tabla los enums son 'Admin','Empleado','Cajero'
    $st = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE rol = :r");
    $st->execute([':r' => $rol]);
    return (int)$st->fetchColumn();
}

public function ultimos(int $limit = 5): array {
    $st = $this->pdo->prepare(
        "SELECT id_usuario, usuario, rol, estado, fecha_creacion 
           FROM usuarios 
       ORDER BY fecha_creacion DESC 
          LIMIT :lim"
    );
    $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
    $st->execute();
    return $st->fetchAll(\PDO::FETCH_ASSOC);
}


}
