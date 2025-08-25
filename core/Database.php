<?php
namespace Core;

use PDO;

/**
 * Clase Database
 * - Se encarga de abrir la conexión a la base de datos usando PDO.
 * - Utiliza el patrón Singleton para no abrir múltiples conexiones.
 */
final class Database {
    private static ?PDO $pdo = null;

    public static function get(array $cfg): PDO {
        if (!self::$pdo) {
            self::$pdo = new PDO(
                $cfg['dsn'],
                $cfg['user'],
                $cfg['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lanza excepciones en errores
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve arrays asociativos
                ]
            );
        }
        return self::$pdo;
    }
}
